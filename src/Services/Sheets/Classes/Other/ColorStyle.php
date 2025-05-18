<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other\ThemeColorType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#colorstyle
 */
class ColorStyle implements Jsonable
{
    public Color|array|null $rgbColor;
    public ThemeColorType|string|null $themeColor;
    
    public function __construct(
        Color|array|null $rgbColor = null,
        ThemeColorType|string|null $themeColor = null,
    ) {
        $this->rgbColor = $this->arrayToObject(class: Color::class, var: $rgbColor);
        $this->themeColor = $this->stringToEnum(enum: ThemeColorType::class, var: $themeColor);

        $this->keepOneOfKind([
            'rgbColor',
            'themeColor'
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
