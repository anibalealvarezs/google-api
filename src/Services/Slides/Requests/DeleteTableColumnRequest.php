<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Requests;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellLocation;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#DeleteTableColumnRequest
 */
class DeleteTableColumnRequest implements Jsonable
{
    public string $tableObjectId;
    public TableCellLocation|array $cellLocation;
    
    public function __construct(
        string $tableObjectId,
        TableCellLocation|array $cellLocation
    ) {
        $this->tableObjectId = $tableObjectId;
        $this->cellLocation = $this->arrayToObject(class: TableCellLocation::class, var: $cellLocation);
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
