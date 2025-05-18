<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Videos;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Videos\VideoProperties;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Videos\Source;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/videos#video
 */
class Video implements Jsonable
{
    public string $url;
    public string $id;
    public VideoProperties|array $videoProperties;
    public Source|string $source;

    public function __construct(
        string $url,
        string $id,
        VideoProperties|array $videoProperties,
        Source|string $source = Source::DRIVE,
    ) {
        $this->url = $url;
        $this->id = $id;
        $this->videoProperties = $this->arrayToObject(class: VideoProperties::class, var: $videoProperties);
        $this->source = $this->stringToEnum(enum: Source::class, var: $source);
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

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
