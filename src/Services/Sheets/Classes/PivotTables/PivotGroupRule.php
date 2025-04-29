<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\PivotTables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivotgrouprule
 */
class PivotGroupRule implements Jsonable
{
    public ManualRule|array|null $manualRule;
    public HistogramRule|array|null $histogramRule;
    public DateTimeRule|array|null $dateTimeRule;
    
    public function __construct(
        ManualRule|array|null $manualRule = null,
        HistogramRule|array|null $histogramRule = null,
        DateTimeRule|array|null $dateTimeRule = null,
    ) {
        $this->manualRule = $this->arrayToObject(class: ManualRule::class, var: $manualRule);
        $this->histogramRule = $this->arrayToObject(class: HistogramRule::class, var: $histogramRule);
        $this->dateTimeRule = $this->arrayToObject(class: DateTimeRule::class, var: $dateTimeRule);

        $this->keepOneOfKind([
            'manualRule',
            'histogramRule',
            'dateTimeRule'
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
