<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\PivotTables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\DataSourceColumnReference;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other\SortOrder;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivotgroup
 */
class PivotGroup implements Jsonable
{
    public array $valueMetadata;
    public ?string $label;
    public PivotGroupRule|array|null $groupRule;
    public PivotGroupLimit|array|null $groupLimit;
    public ?int $sourceColumnOffset;
    public PivotGroupSortValueBucket|array|null $valueBucket;
    public DataSourceColumnReference|array|null $dataSourceColumnReference;
    public bool $showTotals;
    public SortOrder|string $sortOrder;
    public bool $repeatHeadings;
    
    public function __construct(
        array $valueMetadata = [],
        ?string $label = null,
        PivotGroupRule|array|null $groupRule = null,
        PivotGroupLimit|array|null $groupLimit = null,
        ?int $sourceColumnOffset = null,
        PivotGroupSortValueBucket|array|null $valueBucket = null,
        DataSourceColumnReference|array|null $dataSourceColumnReference = null,
        bool $showTotals = true,
        SortOrder|string $sortOrder = SortOrder::ASCENDING,
        bool $repeatHeadings = false,
    ) {
        $this->valueMetadata = $valueMetadata;
        $this->valueBucket = $this->arrayToObject(class: PivotGroupSortValueBucket::class, var: $valueBucket);
        $this->label = $label;
        $this->groupRule = $this->arrayToObject(class: PivotGroupRule::class, var: $groupRule);
        $this->groupLimit = $this->arrayToObject(class: PivotGroupLimit::class, var: $groupLimit);
        $this->sourceColumnOffset = $sourceColumnOffset;
        $this->dataSourceColumnReference = $this->arrayToObject(class: DataSourceColumnReference::class, var: $dataSourceColumnReference);
        $this->showTotals = $showTotals;
        $this->sortOrder = $this->stringToEnum(enum: SortOrder::class, var: $sortOrder);
        $this->repeatHeadings = $repeatHeadings;

        $this->keepOneOfKind([
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
