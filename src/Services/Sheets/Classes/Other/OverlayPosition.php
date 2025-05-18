<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#overlayposition
 */
class OverlayPosition implements Jsonable
{
    public GridCoordinate|array $anchorCell;
    public int $offsetXPixels;
    public int $offsetYPixels;
    public int $widthPixels;
    public int $heightPixels;
    
    public function __construct(
        GridCoordinate|array $anchorCell,
        int $offsetXPixels,
        int $offsetYPixels,
        int $widthPixels = 600,
        int $heightPixels = 371
    ) {
        $this->anchorCell = $this->arrayToObject(class: GridCoordinate::class, var: $anchorCell);
        $this->offsetXPixels = $offsetXPixels;
        $this->offsetYPixels = $offsetYPixels;
        $this->widthPixels = $widthPixels;
        $this->heightPixels = $heightPixels;
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
