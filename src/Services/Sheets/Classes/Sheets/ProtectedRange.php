<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\GridRange;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#protectedrange
 */
class ProtectedRange implements Jsonable
{
    public readonly int $protectedRangeId;
    public GridRange|array $range;
    public string $namedRangeId;
    public string $description;
    public array $unprotectedRanges;
    public Editors|array $editors;
    public bool $warningOnly;
    public readonly bool $requestingUserCanEdit;
    
    public function __construct(
        int $protectedRangeId,
        GridRange|array $range,
        string $namedRangeId,
        string $description,
        array $unprotectedRanges,
        Editors|array $editors,
        bool $warningOnly = true,
        bool $requestingUserCanEdit = false,
    ) {
        $this->protectedRangeId = $protectedRangeId;
        $this->range = $this->arrayToObject(class: GridRange::class, var: $range);
        $this->namedRangeId = $namedRangeId;
        $this->description = $description;
        $this->unprotectedRanges = $unprotectedRanges;
        $this->editors = $this->arrayToObject(class: Editors::class, var: $editors);
        $this->warningOnly = $warningOnly;
        $this->requestingUserCanEdit = $requestingUserCanEdit;
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
