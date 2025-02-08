<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Tables\TableRowProperties;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#UpdateTableRowPropertiesRequest
 * @param string $objectId
 * @param int[] $rowIndices
 * @param TableRowProperties|array $tableRowProperties
 * @param string $fields
 * @return UpdateTableRowPropertiesRequest
 */
class UpdateTableRowPropertiesRequest implements Jsonable
{
    public string $objectId;
    public array $rowIndices;
    public TableRowProperties|array $tableRowProperties;
    public string $fields;
    
    public function __construct(
        string $objectId,
        array $rowIndices,
        TableRowProperties|array $tableRowProperties,
        string $fields = "*"
    ) {
        $this->objectId = $objectId;
        $this->tableRowProperties = $this->arrayToObject(class: TableRowProperties::class, var: $tableRowProperties);
        $this->fields = $fields;
        // Format Row Indices
        $formattedRowIndices = [];
        if ($rowIndices) {
            foreach ($rowIndices as $rowIndex) {
                if(is_nan($rowIndex)) {
                    throw new \Exception("Invalid value for row index");                    
                }
                $formattedRowIndices[] = (int) $rowIndex;
            }
        }
        $this->rowIndices = $formattedRowIndices;
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
