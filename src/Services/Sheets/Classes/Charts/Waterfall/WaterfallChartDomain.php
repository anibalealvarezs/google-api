<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Waterfall;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\ChartData;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#waterfallchartdomain
 */
class WaterfallChartDomain implements Jsonable
{
    public ChartData|array $data;
    public bool $reversed;
    
    public function __construct(
        ChartData|array $data,
        bool $reversed = false,
    ) {
        $this->data = $this->arrayToObject(class: ChartData::class, var: $data);
        $this->reversed = $reversed;
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
