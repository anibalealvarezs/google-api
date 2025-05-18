<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\TextFormat;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells\HyperlinkDisplayType;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells\TextDirection;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells\WrapStrategy;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other\HorizontalAlign;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other\VerticalAlign;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#cellformat
 */
class CellFormat implements Jsonable
{
    public ColorStyle|array $backgroundColorStyle;
    public TextFormat|array|null $textFormat;
    public Padding|array|null $padding;
    public TextRotation|array|null $textRotation;
    public Borders|array|null $borders;
    public NumberFormat|array|null $numberFormat;
    public HorizontalAlign|string $horizontalAlignment;
    public VerticalAlign|string $verticalAlignment;
    public WrapStrategy|string $wrapStrategy;
    public TextDirection|string $textDirection;
    public HyperlinkDisplayType|string|null $hyperlinkDisplayType;
    
    public function __construct(
        ColorStyle|array $backgroundColorStyle,
        TextFormat|array|null $textFormat = null,
        Padding|array|null $padding = null,
        TextRotation|array|null $textRotation = null,
        Borders|array|null $borders = null,
        NumberFormat|array|null $numberFormat = null,
        HorizontalAlign|string $horizontalAlignment = HorizontalAlign::CENTER,
        VerticalAlign|string $verticalAlignment = VerticalAlign::MIDDLE,
        WrapStrategy|string $wrapStrategy = WrapStrategy::OVERFLOW_CELL,
        TextDirection|string $textDirection = TextDirection::LEFT_TO_RIGHT,
        HyperlinkDisplayType|string|null $hyperlinkDisplayType = null,
        mixed $backgroundColor = null,
    ) {
        $this->backgroundColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $backgroundColorStyle);
        $this->padding = $this->arrayToObject(class: Padding::class, var: $padding);
        $this->textFormat = $this->arrayToObject(class: TextFormat::class, var: $textFormat);
        $this->textRotation = $this->arrayToObject(class: TextRotation::class, var: $textRotation);
        $this->borders = $this->arrayToObject(class: Borders::class, var: $borders);
        $this->numberFormat = $this->arrayToObject(class: NumberFormat::class, var: $numberFormat);
        $this->horizontalAlignment = $this->stringToEnum(enum: HorizontalAlign::class, var: $horizontalAlignment);
        $this->verticalAlignment = $this->stringToEnum(enum: VerticalAlign::class, var: $verticalAlignment);
        $this->wrapStrategy = $this->stringToEnum(enum: WrapStrategy::class, var: $wrapStrategy);
        $this->textDirection = $this->stringToEnum(enum: TextDirection::class, var: $textDirection);
        $this->hyperlinkDisplayType = $this->stringToEnum(enum: HyperlinkDisplayType::class, var: $hyperlinkDisplayType);
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
