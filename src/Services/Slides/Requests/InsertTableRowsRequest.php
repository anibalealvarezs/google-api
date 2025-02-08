<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellLocation;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#InsertTableRowsRequest
 */
class InsertTableRowsRequest implements Jsonable
{
    public string $tableObjectId;
    public TableCellLocation|array $cellLocation;
    public int $number;
    public bool $insertBelow;
    
    public function __construct(
        string $tableObjectId,
        TableCellLocation|array $cellLocation,
        int $number = 1,
        bool $insertBelow = true
    ) {
        $this->tableObjectId = $tableObjectId;
        $this->cellLocation = $this->arrayToObject(class: TableCellLocation::class, var: $cellLocation);
        $this->number = $number;
        $this->insertBelow = $insertBelow;
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
