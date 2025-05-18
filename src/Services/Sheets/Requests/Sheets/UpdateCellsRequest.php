<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Sheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\GridCoordinate;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\GridRange;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets\RowData;
use http\Params;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request#UpdateCellsRequest
 * @param RowData[] $rows
 * @param GridCoordinate|array|null $start
 * @param GridRange|array|null $range
 * @param string $fields
 * @return UpdateCellsRequest
 */
class UpdateCellsRequest implements Jsonable
{
    public array $rows;
    public GridCoordinate|array|null $start;
    public GridRange|array|null $range;
    public string $fields;
    
    public function __construct(
        array $rows,
        GridCoordinate|array|null $start = null,
        GridRange|array|null $range = null,
        string $fields = '*',
    ) {
        // Format Rows
        $formattedRows = [];
        if ($rows) {
            foreach ($rows as $row) {
                if (!($row instanceof RowData)) {
                    $formattedRows[] = new RowData(...$row);
                } else {
                    $formattedRows[] = $row;
                }
            }
        }
        $this->rows = $formattedRows;
        $this->start = $this->arrayToObject(class: GridCoordinate::class, var: $start);
        $this->range = $this->arrayToObject(class: GridRange::class, var: $range);
        $this->fields = $fields;

        $this->keepOneOfKind([
            'start',
            'range'
        ]);
    }

    public function keepOneOfKind(array $properties): void
    {
        if ($key = Helpers::getFirstNotNullPropertyFrom($this, $properties)) {
            Helpers::nullifyOtherProperties($this, $key);
        }
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
