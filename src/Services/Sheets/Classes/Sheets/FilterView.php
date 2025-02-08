<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\FilterSpec;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\GridRange;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#filterview
 */
class FilterView implements Jsonable
{
    public int $filterViewId;
    public string $title;
    public GridRange|array $range;
    public string $namedRangeId;
    public array $sortSpecs;
    public FilterSpec|array $filterSpecs;
    
    public function __construct(
        int $filterViewId,
        string $title,
        GridRange|array $range,
        string $namedRangeId,
        array $sortSpecs,
        FilterSpec|array $filterSpecs,
    ) {
        $this->filterViewId = $filterViewId;
        $this->title = $title;
        $this->range = $this->arrayToObject(class: GridRange::class, var: $range);
        $this->namedRangeId = $namedRangeId;
        $this->sortSpecs = $sortSpecs;
        $this->filterSpecs = $this->arrayToObject(class: FilterSpec::class, var: $filterSpecs);
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
