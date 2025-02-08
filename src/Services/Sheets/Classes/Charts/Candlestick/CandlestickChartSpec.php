<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Candlestick;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#candlestickchartspec
 */
class CandlestickChartSpec implements Jsonable
{
    public CandlestickDomain|array $domain;
    public array $data;
    
    public function __construct(
        CandlestickDomain|array $domain,
        array $data,
    ) {
        $this->domain = $this->arrayToObject(class: CandlestickDomain::class, var: $domain);
        $this->data = $data;
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
