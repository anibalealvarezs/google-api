<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Spreadsheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets#resource:-spreadsheet
 */
class Spreadsheet implements Jsonable
{
    public string $spreadsheetId;
    public SpreadsheetProperties|array $properties;
    public array $sheets;
    public array $namedRanges;
    public string $spreadsheetUrl;
    public array $developerMetadata;
    public array $dataSources;
    public array $dataSourceSchedules;
    
    public function __construct(
        string $spreadsheetId,
        SpreadsheetProperties|array $properties,
        array $sheets,
        array $namedRanges,
        array $spreadsheetUrl,
        array $developerMetadata,
        array $dataSources,
        array $dataSourceSchedules,
    ) {
        $this->spreadsheetId = $spreadsheetId;
        $this->properties = $this->arrayToObject(class: SpreadsheetProperties::class, var: $properties);
        $this->sheets = $sheets;
        $this->namedRanges = $namedRanges;
        $this->spreadsheetUrl = $spreadsheetUrl;
        $this->developerMetadata = $developerMetadata;
        $this->dataSources = $dataSources;
        $this->dataSourceSchedules = $dataSourceSchedules;
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
