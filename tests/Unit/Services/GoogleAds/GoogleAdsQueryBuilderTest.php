<?php

declare(strict_types=1);

namespace Tests\Unit\Services\GoogleAds;

use Anibalealvarezs\GoogleApi\Services\GoogleAds\Support\GoogleAdsQueryBuilder;
use PHPUnit\Framework\TestCase;

class GoogleAdsQueryBuilderTest extends TestCase
{
    public function testBuildBasicQuery(): void
    {
        $query = GoogleAdsQueryBuilder::create()
            ->from('campaign')
            ->select(['campaign.id', 'campaign.name', 'campaign.status'])
            ->where("campaign.status = 'ENABLED'")
            ->orderBy('campaign.id', 'DESC')
            ->limit(50)
            ->build();

        $this->assertEquals(
            "SELECT campaign.id, campaign.name, campaign.status FROM campaign WHERE campaign.status = 'ENABLED' ORDER BY campaign.id DESC LIMIT 50",
            $query
        );
    }

    public function testBuildQueryWithDateRangeAndSegments(): void
    {
        $query = GoogleAdsQueryBuilder::create()
            ->from('ad_group')
            ->select('campaign.id, ad_group.id, metrics.cost_micros, metrics.impressions')
            ->dateRange('2026-01-01', '2026-01-07')
            ->where("ad_group.status != 'REMOVED'")
            ->build();

        $this->assertStringContainsString("FROM ad_group", $query);
        $this->assertStringContainsString("segments.date >= '2026-01-01' AND segments.date <= '2026-01-07'", $query);
        $this->assertStringContainsString("ad_group.status != 'REMOVED'", $query);
    }

    public function testSelectDeduplicatesFields(): void
    {
        $query = GoogleAdsQueryBuilder::create()
            ->from('customer')
            ->select(['customer.id', 'metrics.clicks'])
            ->select('customer.id, metrics.impressions')
            ->build();

        $this->assertEquals("SELECT customer.id, metrics.clicks, metrics.impressions FROM customer", $query);
    }
}
