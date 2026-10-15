<?php

declare(strict_types=1);

namespace Tests\Unit\Services\GoogleAds;

use Anibalealvarezs\GoogleApi\Services\GoogleAds\GoogleAdsApi;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class GoogleAdsApiPaginationTest extends TestCase
{
    protected function createClientWithMockResponses(array $responses): GoogleAdsApi
    {
        // Prepend OAuth token refresh response so ApiClient doesn't fail attempting to refresh an empty token
        $allResponses = array_merge([
            new Response(200, [], json_encode(['access_token' => 'mock_access_token_xyz'])),
        ], $responses);

        $mock = new MockHandler($allResponses);
        $handler = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handler]);

        return new GoogleAdsApi(
            redirectUrl: 'https://example.com/callback',
            clientId: 'client-123',
            clientSecret: 'secret-123',
            refreshToken: 'refresh-123',
            userId: 'user-123',
            developerToken: 'dev-123',
            guzzleClient: $guzzle
        );
    }

    public function testSearchAllPaginatesSuccessfully(): void
    {
        $page1 = json_encode([
            'results' => [
                ['campaign' => ['id' => '101', 'name' => 'Camp 1']],
            ],
            'nextPageToken' => 'token_page_2',
        ]);

        $page2 = json_encode([
            'results' => [
                ['campaign' => ['id' => '102', 'name' => 'Camp 2']],
            ],
        ]);

        $client = $this->createClientWithMockResponses([
            new Response(200, [], $page1),
            new Response(200, [], $page2),
        ]);

        $results = $client->searchAll('1234567890', 'SELECT campaign.id FROM campaign');

        $this->assertCount(2, $results['results']);
        $this->assertEquals('101', $results['results'][0]['campaign']['id']);
        $this->assertEquals('102', $results['results'][1]['campaign']['id']);
    }

    public function testSearchAllHaltsOnInfiniteLoopToken(): void
    {
        // Page 1 returns nextPageToken = 'same_token'
        $page1 = json_encode([
            'results' => [
                ['campaign' => ['id' => '101']],
            ],
            'nextPageToken' => 'same_token',
        ]);

        // Page 2 erroneously returns the exact same nextPageToken = 'same_token'
        $page2 = json_encode([
            'results' => [
                ['campaign' => ['id' => '102']],
            ],
            'nextPageToken' => 'same_token',
        ]);

        // If loop detection fails, it would consume page 3
        $page3 = json_encode([
            'results' => [
                ['campaign' => ['id' => '103']],
            ],
        ]);

        $client = $this->createClientWithMockResponses([
            new Response(200, [], $page1),
            new Response(200, [], $page2),
            new Response(200, [], $page3),
        ]);

        $results = $client->searchAll('1234567890', 'SELECT campaign.id FROM campaign');

        // Should halt after page 2 when 'same_token' is repeated, ignoring page 3
        $this->assertCount(2, $results['results']);
        $this->assertEquals(['101', '102'], array_column(array_column($results['results'], 'campaign'), 'id'));
    }

    public function testSearchAllAndProcessBatches(): void
    {
        $page1 = json_encode([
            'results' => [['campaign' => ['id' => '101']]],
            'nextPageToken' => 'tok2',
        ]);
        $page2 = json_encode([
            'results' => [['campaign' => ['id' => '102']]],
        ]);

        $client = $this->createClientWithMockResponses([
            new Response(200, [], $page1),
            new Response(200, [], $page2),
        ]);

        $batches = [];
        $client->searchAllAndProcess('1234567890', 'SELECT campaign.id FROM campaign', function (array $batch) use (&$batches) {
            $batches[] = $batch;
        });

        $this->assertCount(2, $batches);
        $this->assertEquals('101', $batches[0][0]['campaign']['id']);
        $this->assertEquals('102', $batches[1][0]['campaign']['id']);
    }

    public function testSearchStreamAndProcess(): void
    {
        $streamResponse = json_encode([
            ['results' => [['campaign' => ['id' => '201']]]],
            ['results' => [['campaign' => ['id' => '202']]]],
        ]);

        $client = $this->createClientWithMockResponses([
            new Response(200, [], $streamResponse),
        ]);

        $collected = [];
        $client->searchStreamAndProcess('1234567890', 'SELECT campaign.id FROM campaign', function (array $batch) use (&$collected) {
            foreach ($batch as $row) {
                $collected[] = $row['campaign']['id'];
            }
        });

        $this->assertEquals(['201', '202'], $collected);
    }
}
