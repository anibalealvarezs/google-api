<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\PivotTables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#histogramrule
 */
class HistogramRule implements Jsonable
{
    public float $interval;
    public ?float $start;
    public ?float $end;
    
    public function __construct(
        float $interval,
        ?float $start = null,
        ?float $end = null,
    ) {
        $this->interval = $interval;
        $this->start = $start;
        $this->end = $end;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
