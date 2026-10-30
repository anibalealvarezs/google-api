<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\GoogleApi\Services\AnalyticsData\AnalyticsDataApi;
use PHPUnit\Framework\TestCase;

final class AnalyticsDataPaginationTest extends TestCase
{
    /** @dataProvider modes */
    public function testShortPagesContinueUntilAbsoluteRowCount(bool $stream): void
    {
        $api = $this->getMockBuilder(AnalyticsDataApi::class)->disableOriginalConstructor()->onlyMethods(['runReport'])->getMock();
        $offsets = [];
        $api->expects(self::exactly(2))->method('runReport')->willReturnCallback(function ($property, $payload) use (&$offsets) {
            $offsets[] = $payload['offset'];
            return ['rowCount' => 7, 'rows' => [['value' => $payload['offset']]]];
        });
        $rows = [];
        if ($stream) {
            $api->runAllReportsAndProcess('123', ['offset' => 5, 'limit' => 10], function ($page) use (&$rows) { $rows = array_merge($rows, $page['rows']); });
        } else {
            $rows = $api->runAllReports('123', ['offset' => 5, 'limit' => 10])['rows'];
        }
        self::assertSame([5, 6], $offsets);
        self::assertCount(2, $rows);
    }

    public static function modes(): array { return [[true], [false]]; }

    /** @dataProvider modes */
    public function testUnexpectedEmptyPageFailsInsteadOfReportingCompletion(bool $stream): void
    {
        $api = $this->getMockBuilder(AnalyticsDataApi::class)->disableOriginalConstructor()->onlyMethods(['runReport'])->getMock();
        $api->method('runReport')->willReturn(['rowCount' => 3, 'rows' => []]);
        $this->expectException(\RuntimeException::class);
        if ($stream) $api->runAllReportsAndProcess('123', [], fn () => null);
        else $api->runAllReports('123', []);
    }
}
