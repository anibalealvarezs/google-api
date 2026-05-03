<?php

namespace Anibalealvarezs\GoogleApi\Services\AnalyticsData\Classes;

class DateRange
{
    public function __construct(
        public ?string $startDate = null,
        public ?string $endDate = null,
        public ?string $name = null,
    ) {
    }
}
