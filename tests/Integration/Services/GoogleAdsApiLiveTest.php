<?php

declare(strict_types=1);

namespace Tests\Integration\Services;

use Anibalealvarezs\GoogleApi\Services\GoogleAds\GoogleAdsApi;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class GoogleAdsApiLiveTest extends TestCase
{
    protected ?GoogleAdsApi $api = null;
    protected ?string $testCustomerId = null;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $config = app_config();

        if (
            empty($config['google_client_id']) ||
            str_contains($config['google_client_id'], 'YOUR_') ||
            empty($config['google_refresh_token']) ||
            str_contains($config['google_refresh_token'], 'YOUR_') ||
            empty($config['google_ads_developer_token']) ||
            str_contains($config['google_ads_developer_token'], 'YOUR_')
        ) {
            $this->markTestSkipped('Live Google Ads credentials not configured or placeholder detected.');
        }

        $this->testCustomerId = $config['google_ads_test_customer_id'] ?? null;

        $this->api = new GoogleAdsApi(
            redirectUrl: $config['google_redirect_uri'] ?? 'https://oauth.pstmn.io/v1/browser-callback',
            clientId: $config['google_client_id'],
            clientSecret: $config['google_client_secret'],
            refreshToken: $config['google_refresh_token'],
            userId: $config['google_user_id'] ?? 'default_user',
            developerToken: $config['google_ads_developer_token'],
            loginCustomerId: $config['google_ads_login_customer_id'] ?? null,
            scopes: ['https://www.googleapis.com/auth/adwords'],
            token: $config['google_token'] ?? ''
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testGetAccessibleCustomersLive(): void
    {
        $response = $this->api->getAccessibleCustomers();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('resourceNames', $response);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetCampaignsLive(): void
    {
        if (!$this->testCustomerId) {
            $this->markTestSkipped('google_ads_test_customer_id is required for live campaign queries.');
        }

        $campaigns = $this->api->getCampaigns($this->testCustomerId);

        $this->assertIsArray($campaigns);
        $this->assertArrayHasKey('results', $campaigns);
    }
}
