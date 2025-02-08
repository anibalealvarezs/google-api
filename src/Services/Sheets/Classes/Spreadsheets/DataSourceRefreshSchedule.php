<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Spreadsheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets#datasourcerefreshschedule
 */
class DataSourceRefreshSchedule implements Jsonable
{
    public string $spreadsheetId;
    
    public function __construct(
        string $spreadsheetId,
    ) {
        $this->spreadsheetId = $spreadsheetId;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
