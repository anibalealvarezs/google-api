<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Videos;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\Outline;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/videos#Page.VideoProperties
 */
class VideoProperties implements Jsonable
{
    public Outline|array $outline;
    public bool $autoPlay;
    public ?int $start;
    public ?int $end;
    public bool $mute;
    
    public function __construct(
        Outline|array $outline,
        bool $autoPlay = false,
        ?int $start = null,
        ?int $end = null,
        bool $mute = false
    ) {
        $this->outline = $this->arrayToObject(class: Outline::class, var: $outline);
        $this->autoPlay = $autoPlay;
        $this->start = $start;
        $this->end = $end;
        $this->mute = $mute;
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
