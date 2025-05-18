<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\TextFormat;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\TextPosition;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\ComparisonType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#baselinevalueformat
 */
class BaselineValueFormat implements Jsonable
{
    public TextFormat|array $textFormat;
    public ColorStyle|array $positiveColorStyle;
    public ColorStyle|array $negativeColorStyle;
    public ComparisonType|string $comparisonType;
    public TextPosition|array|null $position;
    public ?string $description;
    
    public function __construct(
        TextFormat|array $textFormat,
        ColorStyle|array $positiveColorStyle,
        ColorStyle|array $negativeColorStyle,
        ComparisonType|string $comparisonType = ComparisonType::ABSOLUTE_DIFFERENCE,
        TextPosition|array|null $position = null,
        ?string $description = null,
    ) {
        $this->textFormat = $this->arrayToObject(class: TextFormat::class, var: $textFormat);
        $this->positiveColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $positiveColorStyle);
        $this->negativeColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $negativeColorStyle);
        $this->comparisonType = $this->stringToEnum(enum: ComparisonType::class, var: $comparisonType);
        $this->position = $this->arrayToObject(class: TextPosition::class, var: $position);
        $this->description = $description;
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
