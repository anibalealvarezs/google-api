<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#pageproperties
 */
class PageProperties implements Jsonable
{
    public PageBackgroundFill|array|null $pageBackgroundFill = null;
    public ColorScheme|array|null $colorScheme = null;
    
    public function __construct(
        PageBackgroundFill|array|null $pageBackgroundFill = null,
        ColorScheme|array|null $colorScheme = null,
    ) {
        $this->pageBackgroundFill = $this->arrayToObject(class: PageBackgroundFill::class, var: $pageBackgroundFill);
        $this->colorScheme = $this->arrayToObject(class: ColorScheme::class, var: $colorScheme);
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
