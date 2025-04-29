<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#filterspec
 */
class FilterSpec implements Jsonable
{
    public FilterCriteria|array $filterCriteria;
    public int $columnIndex;
    public DataSourceColumnReference|array $dataSourceColumnReference;
    
    public function __construct(
        FilterCriteria|array $filterCriteria,
        int $columnIndex,
        DataSourceColumnReference|array $dataSourceColumnReference
    ) {
        $this->filterCriteria = $this->arrayToObject(class: FilterCriteria::class, var: $filterCriteria);
        $this->columnIndex = $columnIndex;
        $this->dataSourceColumnReference = $this->arrayToObject(class: DataSourceColumnReference::class, var: $dataSourceColumnReference);

        $this->keepOneOfKind([
            'columnIndex',
            'dataSourceColumnReference'
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
