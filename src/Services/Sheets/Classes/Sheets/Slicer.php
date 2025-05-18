<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\EmbeddedObjectPosition;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#slicer
 */
class Slicer implements Jsonable
{
    public int $slicerId;
    public SlicerSpec|array $spec;
    public EmbeddedObjectPosition|array $position;
    
    public function __construct(
        int $slicerId,
        SlicerSpec|array $spec,
        EmbeddedObjectPosition|array $position,
    ) {
        $this->slicerId = $slicerId;
        $this->spec = $this->arrayToObject(class: SlicerSpec::class, var: $spec);
        $this->position = $this->arrayToObject(class: EmbeddedObjectPosition::class, var: $position);
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
