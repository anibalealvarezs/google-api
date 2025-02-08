<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Candlestick;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\ChartData;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#CandlestickDomain
 */
class CandlestickDomain implements Jsonable
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
