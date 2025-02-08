<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\ChartDateTimeRuleType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#charthistogramrule
 */
class ChartHistogramRule implements Jsonable
{
    public float $minValue;
    public float $maxValue;
    public float $intervalSize;
    
    public function __construct(
        float $minValue,
        float $maxValue,
        float $intervalSize
    ) {
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
        $this->intervalSize = $intervalSize;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
