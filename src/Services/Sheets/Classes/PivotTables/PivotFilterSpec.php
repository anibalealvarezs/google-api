<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\PivotTables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\DataSourceColumnReference;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivotfilterspec
 */
class PivotFilterSpec implements Jsonable
{
    public PivotFilterCriteria|array $filterCriteria;
    public ?int $columnOffsetIndex;
    public DataSourceColumnReference|array|null $dataSourceColumnReference;
    
    public function __construct(
        PivotFilterCriteria|array $filterCriteria,
        ?int $columnOffsetIndex = null,
        DataSourceColumnReference|array|null $dataSourceColumnReference = null,
    ) {
        $this->filterCriteria = $this->arrayToObject(class: PivotFilterCriteria::class, var: $filterCriteria);
        $this->columnOffsetIndex = $columnOffsetIndex;
        $this->dataSourceColumnReference = $this->arrayToObject(class: DataSourceColumnReference::class, var: $dataSourceColumnReference);

        $this->keepOneOfKind([
            'columnOffsetIndex',
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
