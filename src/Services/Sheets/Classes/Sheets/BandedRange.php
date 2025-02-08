<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\GridRange;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#bandedrange
 */
class BandedRange implements Jsonable
{
    public int $bandedRangeId;
    public GridRange|array $range;
    public BandingProperties|array|null $rowProperties = null;
    public BandingProperties|array|null $columnProperties = null;
    
    public function __construct(
        int $bandedRangeId,
        GridRange|array $range,
        BandingProperties|array|null $rowProperties = null,
        BandingProperties|array|null $columnProperties = null,
    ) {
        $this->bandedRangeId = $bandedRangeId;
        $this->range = $this->arrayToObject(class: GridRange::class, var: $range);
        $this->rowProperties = $this->arrayToObject(class: BandingProperties::class, var: $rowProperties);
        $this->columnProperties = $this->arrayToObject(class: BandingProperties::class, var: $columnProperties);
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
