<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Requests;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables\TableColumnProperties;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#UpdateTableColumnPropertiesRequest
 * @param string $objectId
 * @param int[] $columnIndices
 * @param TableColumnProperties|array $tableColumnProperties
 * @param string $fields
 * @return UpdateTableColumnPropertiesRequest
 */
class UpdateTableColumnPropertiesRequest implements Jsonable
{
    public string $objectId;
    public array $columnIndices;
    public TableColumnProperties|array $tableColumnProperties;
    public string $fields;
    
    public function __construct(
        string $objectId,
        array $columnIndices,
        TableColumnProperties|array $tableColumnProperties,
        string $fields = "*"
    ) {
        $this->objectId = $objectId;
        $this->$tableColumnProperties = $this->arrayToObject(class: TableColumnProperties::class, var: $tableColumnProperties);
        $this->fields = $fields;
        // Format Column Indices
        $formattedColumnIndices = [];
        if ($columnIndices) {
            foreach ($columnIndices as $columnIndex) {
                if(is_nan($columnIndex)) {
                    throw new \Exception("Invalid value for column index");                    
                }
                $formattedColumnIndices[] = (int) $columnIndex;
            }
        }
        $this->columnIndices = $formattedColumnIndices;
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
