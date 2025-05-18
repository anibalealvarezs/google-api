<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Charts;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\ImageProperties;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/charts#sheetschartproperties
 */
class SheetsChartProperties implements Jsonable
{
    public ImageProperties|array $chartImageProperties;

    public function __construct(
        ImageProperties|array $chartImageProperties,
    ) {
        $this->chartImageProperties = $this->arrayToObject(class: ImageProperties::class, var: $chartImageProperties);
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
