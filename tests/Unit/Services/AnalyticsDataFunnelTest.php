<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\GoogleApi\Services\AnalyticsData\AnalyticsDataApi;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class AnalyticsDataFunnelTest extends TestCase
{
    public function testFunnelUsesAlphaEndpointAndKeepsItsOwnSourceResponse(): void
    {
        $api = $this->getMockBuilder(AnalyticsDataApi::class)->disableOriginalConstructor()->onlyMethods(['performRequest'])->getMock();
        $api->expects(self::once())->method('performRequest')->willReturnCallback(function (...$values) {
            $parameters = (new \ReflectionMethod(AnalyticsDataApi::class, 'performRequest'))->getParameters();
            $arguments = array_combine(array_map(fn ($p) => $p->getName(), array_slice($parameters, 0, count($values))), $values);
            self::assertSame('https://analyticsdata.googleapis.com/v1alpha/', $arguments['baseUrl']);
            self::assertSame('properties/123:runFunnelReport', $arguments['endpoint']);
            self::assertArrayHasKey('funnel', json_decode($arguments['body'], true));
            return new Response(200, [], '{"funnelTable":{"rows":[]}}');
        });
        self::assertArrayHasKey('funnelTable', $api->runFunnelReport('123', ['funnel' => ['steps' => []]]));
    }
}
