<?php

namespace Tests\Integration\Services;

use Anibalealvarezs\GoogleApi\Services\SearchConsole\SearchConsoleApi;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class SearchConsoleApiLiveTest extends TestCase
{
    protected SearchConsoleApi $api;
    protected string $testSiteUrl;
    protected string $testRemovableSiteUrl;
    protected string $testSitemapUrl;
    protected string $testRemovableSitemapUrl;
    protected string $inspectUrl;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $config = app_config();

        $this->testSiteUrl = 'sc-domain:anibalalvarez.com';
        $this->testRemovableSiteUrl = 'sc-domain:loquesea.com';
        $this->testSitemapUrl = 'https://anibalalvarez.com/sitemap_index.xml';
        $this->testRemovableSitemapUrl = 'https://anibalalvarez.com/geo-sitemap.xml';
        $this->inspectUrl = 'https://anibalalvarez.com/ecommerce-conversion-optimization-businesses/';

        $this->api = new SearchConsoleApi(
            redirectUrl: $config['google_redirect_uri'],
            clientId: $config['google_client_id'],
            clientSecret: $config['google_client_secret'],
            refreshToken: $config['google_refresh_token'],
            userId: $config['google_user_id'],
        );
    }

    /**
     * @throws GuzzleException
     */
    public function testGetSites()
    {
        $response = $this->api->getSites();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('siteEntry', $response);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetSite()
    {
        $response = $this->api->getSite(siteUrl: $this->testSiteUrl);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('siteUrl', $response);
    }

    /**
     * @throws GuzzleException
     */
    public function testAddAndRemoveSite()
    {
        $siteToTest = $this->testRemovableSiteUrl;

        $addResponse = $this->api->addSite($siteToTest);
        $this->assertEmpty($addResponse); // Google returns empty string on success

        $removeResponse = $this->api->removeSite($siteToTest);
        $this->assertEmpty($removeResponse); // Also returns empty string on success
    }

    /**
     * @throws GuzzleException
     */
    public function testAddAndDeleteSitemap()
    {
        $sitemapUrl = $this->testRemovableSitemapUrl;

        $addResponse = $this->api->addSitemap($this->testSiteUrl, $sitemapUrl);
        $this->assertEmpty($addResponse);

        $deleteResponse = $this->api->removeSitemap($this->testSiteUrl, $sitemapUrl);
        $this->assertEmpty($deleteResponse);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetSearchQueryResults()
    {
        $response = $this->api->getSearchQueryResults(
            siteUrl: $this->testSiteUrl,
            startDate: date('Y-m-d', strtotime('-7 days')),
            endDate: date('Y-m-d'),
            rowLimit: 10,
            dimensions: ['query']
        );

        $this->assertIsArray($response);
        $this->assertArrayHasKey('rows', $response);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetSitemaps()
    {
        $response = $this->api->getSitemaps($this->testSiteUrl);
        $this->assertIsArray($response);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetSitemap()
    {
        $sitemapUrl = $this->testSitemapUrl;

        $response = $this->api->getSitemap($this->testSiteUrl, $sitemapUrl);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('path', $response);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetInspectionResults()
    {
        $response = $this->api->inspectUrl($this->testSiteUrl, $this->inspectUrl);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('inspectionResult', $response);
    }
}
