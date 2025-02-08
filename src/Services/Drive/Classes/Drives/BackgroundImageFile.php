<?php

namespace Anibalealvarezs\GoogleApi\Services\Drive\Classes\Drives;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/drive/api/v3/reference/drives#resource
 */
class BackgroundImageFile implements Jsonable
{
    public string $id;
    public float $xCoordinate;
    public float $yCoordinate;
    public float $width;
    
    public function __construct(
        string $id = '',
        float $xCoordinate = 0.0,
        float $yCoordinate = 0.0,
        float $width = 0.0
    ) {
        $this->id = $id;
        $this->xCoordinate = $xCoordinate;
        $this->yCoordinate = $yCoordinate;
        $this->width = $width;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
