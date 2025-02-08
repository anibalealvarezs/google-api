<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\PivotTables;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivotgroupsortvaluebucket
 */
class PivotGroupSortValueBucket implements Jsonable
{
    public int $valuesIndex;
    public array $buckets;
    
    public function __construct(
        int $valuesIndex,
        array $buckets = [],
    ) {
        $this->valuesIndex = $valuesIndex;
        $this->buckets = $buckets;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
