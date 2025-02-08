<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\PivotTables;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivotgrouplimit
 */
class PivotGroupLimit implements Jsonable
{
    public int $countLimit;
    public ?int $applyOrder;
    
    public function __construct(
        int $countLimit,
        ?int $applyOrder = null,
    ) {
        $this->countLimit = $countLimit;
        $this->applyOrder = $applyOrder;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
