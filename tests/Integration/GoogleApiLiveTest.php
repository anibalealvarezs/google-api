<?php

declare(strict_types=1);

namespace Tests\Integration;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class GoogleApiLiveTest extends TestCase
{
    protected GoogleApi $googleApi;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $config = app_config();

        $this->googleApi = new GoogleApi(
            baseUrl: "https://www.googleapis.com/webmasters/v3/",
            redirectUrl: $config['google_redirect_uri'],
            clientId: $config['google_client_id'],
            clientSecret: $config['google_client_secret'],
            refreshToken: $config['google_refresh_token'],
            userId: $config['google_user_id'],
            scopes: [$config['search_console_scope']],
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testItRefreshesAccessToken(): void
    {
        $token = $this->googleApi->getNewToken();

        $this->assertIsString($token, 'Token must be a string');
        $this->assertNotEmpty($token, 'Token should not be empty');
        $this->assertStringNotContainsString('error', strtolower($token), 'Token should not contain errors');
    }
}
