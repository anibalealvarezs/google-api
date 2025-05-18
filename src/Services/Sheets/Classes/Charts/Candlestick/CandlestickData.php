<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Candlestick;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#candlestickdata
 */
class CandlestickData implements Jsonable
{
    public CandlestickSeries|array $lowSeries;
    public CandlestickSeries|array $openSeries;
    public CandlestickSeries|array $closeSeries;
    public CandlestickSeries|array $highSeries;
    
    public function __construct(
        CandlestickSeries|array $lowSeries,
        CandlestickSeries|array $openSeries,
        CandlestickSeries|array $closeSeries,
        CandlestickSeries|array $highSeries,
    ) {
        $this->lowSeries = $this->arrayToObject(class: CandlestickSeries::class, var: $lowSeries);
        $this->openSeries = $this->arrayToObject(class: CandlestickSeries::class, var: $openSeries);
        $this->closeSeries = $this->arrayToObject(class: CandlestickSeries::class, var: $closeSeries);
        $this->highSeries = $this->arrayToObject(class: CandlestickSeries::class, var: $highSeries);
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
