<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\FilterCriteria;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\GridRange;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\TextFormat;
use Chmw\GoogleApi\Services\Sheets\Enums\Other\HorizontalAlign;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#slicerspec
 */
class SlicerSpec implements Jsonable
{
    public GridRange|array $dataRange;
    public FilterCriteria|array $filterCriteria;
    public int $columnIndex;
    public string $title;
    public TextFormat|array $textFormat;
    public ColorStyle|array $backgroundColorStyle;
    public bool $applyToPivotTables;
    public HorizontalAlign|string $horizontalAlignment;
    
    public function __construct(
        GridRange|array $dataRange,
        FilterCriteria|array $filterCriteria,
        int $columnIndex,
        string $title,
        TextFormat|array $textFormat,
        ColorStyle|array $backgroundColorStyle,
        bool $applyToPivotTables = true,
        HorizontalAlign|string $horizontalAlignment = HorizontalAlign::LEFT,
    ) {
        $this->dataRange = $this->arrayToObject(class: GridRange::class, var: $dataRange);
        $this->filterCriteria = $this->arrayToObject(class: FilterCriteria::class, var: $filterCriteria);
        $this->columnIndex = $columnIndex;
        $this->title = $title;
        $this->textFormat = $this->arrayToObject(class: TextFormat::class, var: $textFormat);
        $this->backgroundColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $backgroundColorStyle);
        $this->applyToPivotTables = $applyToPivotTables;
        $this->horizontalAlignment = $this->stringToEnum(enum: HorizontalAlign::class, var: $horizontalAlignment);
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
