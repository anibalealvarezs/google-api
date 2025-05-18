<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other\SortOrder;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#sortspec
 */
class SortSpec implements Jsonable
{
    public ColorStyle|array $foregroundColorStyle;
    public ColorStyle|array $backgroundColorStyle;
    public SortOrder $sortOrder;
    public ?int $dimensionIndex;
    public DataSourceColumnReference|array|null $dataSourceColumnReference;
    
    public function __construct(
        ColorStyle|array $foregroundColorStyle,
        ColorStyle|array $backgroundColorStyle,
        SortOrder $sortOrder = SortOrder::ASCENDING,
        ?int $dimensionIndex = null,
        DataSourceColumnReference|array|null $dataSourceColumnReference = null,
    ) {
        $this->foregroundColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $foregroundColorStyle);
        $this->backgroundColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $backgroundColorStyle);
        $this->sortOrder = $this->stringToEnum(enum: SortOrder::class, var: $sortOrder);
        $this->dimensionIndex = $dimensionIndex;
        $this->dataSourceColumnReference = $this->arrayToObject(class: DataSourceColumnReference::class, var: $dataSourceColumnReference);

        $this->keepOneOfKind([
            'dimensionIndex',
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
