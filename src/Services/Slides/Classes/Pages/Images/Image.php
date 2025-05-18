<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Images;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\ImageProperties;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\Placeholder;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/images#image
 */
class Image implements Jsonable
{
    public string $contentUrl;
    public ImageProperties|array $imageProperties;
    public string $sourceUrl;
    public Placeholder|array|null $placeholder;

    public function __construct(
        string $contentUrl,
        ImageProperties|array $imageProperties,
        string $sourceUrl = '',
        Placeholder|array|null $placeholder = null,
    ) {
        $this->contentUrl = $contentUrl;
        $this->imageProperties = $this->arrayToObject(class: ImageProperties::class, var: $imageProperties);
        $this->sourceUrl = $sourceUrl;
        $this->placeholder = $this->arrayToObject(class: Placeholder::class, var: $placeholder);
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
