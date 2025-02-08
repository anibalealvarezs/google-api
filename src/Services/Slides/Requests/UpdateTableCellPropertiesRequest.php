<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellProperties;
use Chmw\GoogleApi\Services\Slides\Classes\Presentations\Request\TableRange;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#createsliderequest
 */
class UpdateTableCellPropertiesRequest implements Jsonable
{
    public string $objectId;
    public TableCellProperties|array $tableCellProperties;
    public string $fields;
    public TableRange|array|null $tableRange;
    
    public function __construct(
        string $objectId,
        TableCellProperties|array $tableCellProperties,
        string $fields = "*",
        TableRange|array|null $tableRange = null,
    ) {
        $this->objectId = $objectId;
        $this->tableCellProperties = $this->arrayToObject(class: TableCellProperties::class, var: $tableCellProperties);
        $this->fields = $fields;
        $this->tableRange = $this->arrayToObject(class: TableRange::class, var: $tableRange);
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
