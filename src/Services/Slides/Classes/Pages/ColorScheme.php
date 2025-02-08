<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#colorscheme
 * @param ThemeColorPair[] $colors
 * @return ColorScheme
 */
class ColorScheme implements Jsonable
{
    public array $colors;
    
    public function __construct(
        array $colors,
    ) {
        // Format Colors
        $formattedColors = [];
        if ($colors) {
            foreach ($colors as $color) {
                if (!($color instanceof ThemeColorPair)) {
                    $formattedColors[] = new ThemeColorPair(...$color);
                } else {
                    $formattedColors[] = $color;
                }
            }
        }
        $this->colors = $formattedColors;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
