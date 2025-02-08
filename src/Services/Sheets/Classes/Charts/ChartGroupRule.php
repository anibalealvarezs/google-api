<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#chartgrouprule
 */
class ChartGroupRule implements Jsonable
{
    public ChartDateTimeRule|array|null $dateTimeRule;
    public ChartHistogramRule|array|null $histogramRule;
    
    public function __construct(
        ChartDateTimeRule|array|null $dateTimeRule = null,
        ChartHistogramRule|array|null $histogramRule = null,
    ) {
        $this->dateTimeRule = $this->arrayToObject(class: ChartDateTimeRule::class, var: $dateTimeRule);
        $this->histogramRule = $this->arrayToObject(class: ChartHistogramRule::class, var: $histogramRule);

        $this->keepOneOfKind([
            'dateTimeRule',
            'histogramRule'
        ]);
    }

    public function keepOneOfKind(array $properties): void
    {
        if ($key = Helpers::getFirstNotNullPropertyFrom($this, $properties)) {
            Helpers::nullifyOtherProperties($this, $key);
        }
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
