<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Dimension;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/tables#Page.TableColumnProperties
 */
class TableColumnProperties implements Jsonable
{
    public Dimension|array $columnWidth;
    
    public function __construct(
        Dimension|array $columnWidth
    ) {
        $this->columnWidth = $this->arrayToObject(class: Dimension::class, var: $columnWidth);
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
