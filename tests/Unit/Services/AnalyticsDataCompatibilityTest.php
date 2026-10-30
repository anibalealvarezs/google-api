<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\GoogleApi\Services\AnalyticsData\AnalyticsDataApi;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class AnalyticsDataCompatibilityTest extends TestCase
{
    public function testCompatibilityEndpointUsesOnlySupportedRequestFields(): void
    {
        $api = $this->getMockBuilder(AnalyticsDataApi::class)->disableOriginalConstructor()->onlyMethods(['performRequest'])->getMock();
        $api->expects(self::once())->method('performRequest')->willReturnCallback(function (...$arguments) {
            $parameters = (new \ReflectionMethod(AnalyticsDataApi::class, 'performRequest'))->getParameters();
            $arguments = array_combine(array_map(fn ($p) => $p->getName(), array_slice($parameters, 0, count($arguments))), $arguments);
            self::assertSame('POST', $arguments['method']);
            self::assertSame('properties/123:checkCompatibility', $arguments['endpoint']);
            $body = json_decode($arguments['body'], true);
            self::assertSame([['name' => 'sessions']], $body['metrics']);
            self::assertSame('COMPATIBLE', $body['compatibilityFilter']);
            self::assertArrayNotHasKey('dateRanges', $body);
            return new Response(200, [], '{"metricCompatibilities":[],"dimensionCompatibilities":[]}');
        });
        self::assertArrayHasKey('metricCompatibilities', $api->checkCompatibility('123', ['metrics' => [['name' => 'sessions']], 'dimensions' => [['name' => 'date']], 'compatibilityFilter' => 'COMPATIBLE', 'dateRanges' => [['startDate' => '2026-10-01']]]));
    }
}
