<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#gridrange
 */
class GridRange implements Jsonable
{
    public int $sheetId;
    public ?int $startRowIndex;
    public ?int $endRowIndex;
    public ?int $startColumnIndex;
    public ?int $endColumnIndex;
    
    public function __construct(
        int $sheetId,
        ?int $startRowIndex = null,
        ?int $endRowIndex = null,
        ?int $startColumnIndex = null,
        ?int $endColumnIndex = null,
    ) {
        $this->sheetId = $sheetId;
        $this->startRowIndex = $startRowIndex;
        $this->endRowIndex = $endRowIndex;
        $this->startColumnIndex = $startColumnIndex;
        $this->endColumnIndex = $endColumnIndex;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
