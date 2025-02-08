<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\DataSourceColumnReference;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#dimensionproperties
 */
class DimensionProperties implements Jsonable
{
    public int $pixelSize;
    public ?array $developerMetadata;
    public readonly DataSourceColumnReference|array|null $dataSourceColumnReference;
    public readonly bool $hiddenByFilter;
    public bool $hiddenByUser;
    
    public function __construct(
        int $pixelSize,
        array $developerMetadata = null,
        DataSourceColumnReference|array $dataSourceColumnReference = null,
        bool $hiddenByFilter = false,
        bool $hiddenByUser = false,
    ) {
        $this->pixelSize = $pixelSize;
        $this->developerMetadata = $developerMetadata;
        $this->dataSourceColumnReference = $this->arrayToObject(class: DataSourceColumnReference::class, var: $dataSourceColumnReference);
        $this->hiddenByFilter = $hiddenByFilter;
        $this->hiddenByUser = $hiddenByUser;
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
