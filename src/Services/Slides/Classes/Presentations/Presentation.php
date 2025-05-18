<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Page;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Size;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations
 * @param string $presentationId
 * @param Size|array $pageSize
 * @param Page[] $slides
 * @param Page[] $masters
 * @param Page[] $layouts
 * @param string|null $title
 * @param string|null $locale
 * @param Page|array|null $notesMaster
 * @param string|null $revisionId
 * @return Presentation
 */
class Presentation implements Jsonable
{
    public string $presentationId;
    public Size|array $pageSize;
    public array $slides;
    public array $masters;
    public array $layouts;
    public ?string $title;
    public ?string $locale;
    public Page|array|null $notesMaster;
    public ?string $revisionId;
    
    public function __construct(
        string $presentationId,
        Size|array $pageSize,
        array $slides,
        array $masters,
        array $layouts,
        ?string $title,
        ?string $locale,
        Page|array|null $notesMaster = null,
        ?string $revisionId = null,
    ) {
        $this->presentationId = $presentationId;
        $this->pageSize = $this->arrayToObject(class: Size::class, var: $pageSize);
        // Format Slides
        $formattedSlides = [];
        if ($slides) {
            foreach ($slides as $slide) {
                if (!($slide instanceof Page)) {
                    $formattedSlides[] = new Page(...$slide);
                } else {
                    $formattedSlides[] = $slide;
                }
            }
        }
        $this->slides = $formattedSlides;
        // Format Masters
        $formattedMasters = [];
        if ($masters) {
            foreach ($masters as $master) {
                if (!($master instanceof Page)) {
                    $formattedMasters[] = new Page(...$master);
                } else {
                    $formattedMasters[] = $master;
                }
            }
        }
        $this->masters = $formattedMasters;
        // Format Layouts
        $formattedLayouts = [];
        if ($layouts) {
            foreach ($layouts as $layout) {
                if (!($layout instanceof Page)) {
                    $formattedLayouts[] = new Page(...$layout);
                } else {
                    $formattedLayouts[] = $layout;
                }
            }
        }
        $this->layouts = $formattedLayouts;
        $this->notesMaster = $this->arrayToObject(class: Page::class, var: $notesMaster);
        $this->title = $title;
        $this->locale = $locale;
        $this->revisionId = $revisionId;
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
