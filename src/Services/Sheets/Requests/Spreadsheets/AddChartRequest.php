<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Spreadsheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Spreadsheets\EmbeddedChart;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request#addchartrequest
 */
class AddChartRequest implements Jsonable
{
    public EmbeddedChart|array $chart;
    
    public function __construct(
        EmbeddedChart|array $chart
    ) {
        $this->chart = $this->arrayToObject(class: EmbeddedChart::class, var: $chart);
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }

    public function arrayToObject(string $class, mixed $var): mixed
    {
        if (is_array($var)) {
            return new $class(...$var);
        }
        return $var;
    }
}
