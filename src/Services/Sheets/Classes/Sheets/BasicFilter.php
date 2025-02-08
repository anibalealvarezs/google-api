<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\GridRange;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#basicfilter
 */
class BasicFilter implements Jsonable
{
    public GridRange|array $range;
    public array $sortSpecs;
    public array $filterSpecs;
    
    public function __construct(
        GridRange|array $range,
        array $sortSpecs,
        array $filterSpecs,
    ) {
        $this->range = $this->arrayToObject(class: GridRange::class, var: $range);
        $this->sortSpecs = $sortSpecs;
        $this->filterSpecs = $filterSpecs;
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
