<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\PivotTables;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\DataSourceColumnReference;
use Chmw\GoogleApi\Services\Sheets\Enums\PivotTables\PivotValueCalculatedDisplayType;
use Chmw\GoogleApi\Services\Sheets\Enums\PivotTables\PivotValueSummarizeFunction;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivotvalue
 */
class PivotValue implements Jsonable
{
    public ?string $name;
    public ?string $formula;
    public ?int $sourceColumnOffset;
    public DataSourceColumnReference|array|null $dataSourceColumnReference;
    public PivotValueSummarizeFunction|string $summarizeFunction;
    public PivotValueCalculatedDisplayType|string $calculatedDisplayType;
    
    public function __construct(
        ?string $name = null,
        ?string $formula = null,
        ?int $sourceColumnOffset = null,
        DataSourceColumnReference|array|null $dataSourceColumnReference = null,
        PivotValueSummarizeFunction|string $summarizeFunction = PivotValueSummarizeFunction::SUM,
        PivotValueCalculatedDisplayType|string $calculatedDisplayType = PivotValueCalculatedDisplayType::PIVOT_VALUE_CALCULATED_DISPLAY_TYPE_UNSPECIFIED,
    ) {
        $this->name = $name;
        $this->formula = $formula;
        $this->sourceColumnOffset = $sourceColumnOffset;
        $this->dataSourceColumnReference = $this->arrayToObject(class: DataSourceColumnReference::class, var: $dataSourceColumnReference);
        $this->summarizeFunction = $this->stringToEnum(enum: PivotValueSummarizeFunction::class, var: $summarizeFunction);
        $this->calculatedDisplayType = $this->stringToEnum(enum: PivotValueCalculatedDisplayType::class, var: $calculatedDisplayType);

        $this->keepOneOfKind([
            'formula',
            'sourceColumnOffset',
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

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
