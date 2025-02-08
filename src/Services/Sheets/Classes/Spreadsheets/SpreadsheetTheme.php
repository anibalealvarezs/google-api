<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Spreadsheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets#spreadsheettheme
 */
class SpreadsheetTheme implements Jsonable
{
    public string $primaryFontFamily;
    public array $themeColors;
    
    public function __construct(
        string $primaryFontFamily,
        array $themeColors,
    ) {
        $this->primaryFontFamily = $primaryFontFamily;
        $this->themeColors = $themeColors;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
