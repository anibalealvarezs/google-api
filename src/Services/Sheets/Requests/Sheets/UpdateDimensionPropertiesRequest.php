<?php

namespace Chmw\GoogleApi\Services\Sheets\Requests\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\DataSourceSheetDimensionRange;
use Chmw\GoogleApi\Services\Sheets\Classes\DimensionRange;
use Chmw\GoogleApi\Services\Sheets\Classes\Sheets\DimensionProperties;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request?hl=es-419#updatedimensionpropertiesrequest
 */
class UpdateDimensionPropertiesRequest implements Jsonable
{
    public DimensionProperties $properties;
    public string $fields;
    public DimensionRange|array|null $range;
    public DataSourceSheetDimensionRange|array|null $dataSourceSheetRange;
    
    public function __construct(
        DimensionProperties $properties,
        string $fields = '*',
        DimensionRange|array|null $range = null,
        DataSourceSheetDimensionRange|array|null $dataSourceSheetRange = null,
    ) {
        $this->properties = $this->arrayToObject(class: DimensionProperties::class, var: $properties);
        $this->fields = $fields;
        $this->range = $this->arrayToObject(class: DimensionRange::class, var: $range);
        $this->dataSourceSheetRange = $this->arrayToObject(class: DataSourceSheetDimensionRange::class, var: $dataSourceSheetRange);

        $this->keepOneOfKind([
            'range',
            'dataSourceSheetRange'
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
