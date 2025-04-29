<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\SolidFill;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/tables#tableborderfill
 */
class TableBorderFill implements Jsonable
{
    public SolidFill|array $solidFill;
    
    public function __construct(
        SolidFill|array $solidFill,
    ) {
        $this->solidFill = $this->arrayToObject(class: SolidFill::class, var: $solidFill);
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
