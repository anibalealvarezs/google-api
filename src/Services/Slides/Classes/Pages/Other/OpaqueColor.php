<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\RgbColor;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\ThemeColorType;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.OpaqueColor
 */
class OpaqueColor implements Jsonable
{
    public RgbColor|array|null $rgbColor;
    public ThemeColorType|string|null $themeColor;
    
    public function __construct(
        RgbColor|array|null $rgbColor = null,
        ThemeColorType|string|null $themeColor = ThemeColorType::DARK1
    ) {
        $this->rgbColor = $this->arrayToObject(class: RgbColor::class, var: $rgbColor);
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
