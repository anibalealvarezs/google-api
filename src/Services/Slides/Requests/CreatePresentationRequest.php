<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Requests;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Dimension;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Page;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Size;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/create
 * @param string|null $presentationId
 * @param Size|array|null $pageSize
 * @param Page[] $slides
 * @param string|null $title
 * @param Page[] $masters
 * @param Page[] $layouts
 * @param string|null $locale
 * @param string|null $revisionId
 * @param Page|array|null $notesMaster
 * @return CreatePresentationRequest
 */
class CreatePresentationRequest implements Jsonable
{
    public ?string $presentationId;
    public Size|array|null $pageSize;
    public ?array $slides;
    public ?string $title;
    public ?array $masters;
    public ?array $layouts;
    public ?string $locale;
    public ?string $revisionId;
    public readonly Page|array|null $notesMaster;
    
    public function __construct(
        ?string $presentationId = null,
        Size|array|null $pageSize = null,
        ?array $slides = [],
        ?string $title = 'Title',
        ?array $masters = null,
        ?array $layouts = [],
        ?string $locale = 'en',
        ?string $revisionId = null,
        Page|array|null $notesMaster = null
    ) {
        if (!$pageSize) {
            $pageSize = new Size(
                width: new Dimension(1),
                height: new Dimension(1)
            );
        }
        $this->presentationId = $presentationId;
        $this->pageSize = $this->arrayToObject(class: Size::class, var: $pageSize);
        $this->title = $title;
        $this->locale = $locale;
        $this->revisionId = $revisionId;
        $this->notesMaster = $this->arrayToObject(class: Page::class, var: $notesMaster);
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
