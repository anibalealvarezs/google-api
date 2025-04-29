<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\PivotTables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\DataExecutionStatus;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\GridRange;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\PivotTables\PivotValueLayout;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivottable
 */
class PivotTable implements Jsonable
{
    public array $rows;
    public array $columns;
    public array $filterSpecs;
    public array $values;
    public readonly DataExecutionStatus|array|null $dataExecutionStatus;
    public GridRange|array|null $source;
    public ?string $dataSourceId;
    public PivotValueLayout|string $valueLayout;
    
    public function __construct(
        array $rows,
        array $columns,
        array $filterSpecs,
        array $values,
        DataExecutionStatus|array|null $dataExecutionStatus = null,
        GridRange|array|null $source = null,
        ?string $dataSourceId = null,
        PivotValueLayout|string $valueLayout = PivotValueLayout::HORIZONTAL,
        ?array $criteria = null,
    ) {
        $this->rows = $rows;
        $this->columns = $columns;
        $this->filterSpecs = $filterSpecs;
        $this->values = $values;
        $this->dataExecutionStatus = $this->arrayToObject(class: DataExecutionStatus::class, var: $dataExecutionStatus);
        $this->source = $this->arrayToObject(class: GridRange::class, var: $source);
        $this->dataSourceId = $dataSourceId;
        $this->valueLayout = $this->stringToEnum(enum: PivotValueLayout::class, var: $valueLayout);

        $this->keepOneOfKind([
            'source',
            'dataSourceId'
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

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
