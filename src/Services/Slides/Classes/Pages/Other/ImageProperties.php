<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\CropProperties;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\Link;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\Outline;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\Recolor;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\Shadow;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.ImageProperties
 */
class ImageProperties implements Jsonable
{
    public readonly CropProperties|array|null $cropProperties;
    public readonly float $transparency;
    public readonly float $brightness;
    public readonly float $contrast;
    public readonly Recolor|array|null $recolor;
    public readonly Outline|array|null $outline;
    public readonly Shadow|array|null $shadow;
    public Link|array|null $link;
    
    public function __construct(
        CropProperties|array|null $cropProperties = null,
        float $transparency = 0,
        float $brightness = 0,
        float $contrast = 0,
        Recolor|array|null $recolor = null,
        Outline|array|null $outline = null,
        Shadow|array|null $shadow = null,
        Link|array|null $link = null,
    ) {
        $this->cropProperties = $this->arrayToObject(class: CropProperties::class, var: $cropProperties);
        $this->transparency = $transparency;
        $this->brightness = $brightness;
        $this->contrast = $contrast;
        $this->recolor = $this->arrayToObject(class: Recolor::class, var: $recolor);
        $this->outline = $this->arrayToObject(class: Outline::class, var: $outline);
        $this->shadow = $this->arrayToObject(class: Shadow::class, var: $shadow);
        $this->link = $this->arrayToObject(class: Link::class, var: $link);
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
