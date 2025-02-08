<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Text;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#paragraphmarker
 */
class ParagraphMarker implements Jsonable
{
    public ParagraphStyle|array $style;
    public Bullet|array|null $bullet;
    
    public function __construct(
        ParagraphStyle|array $style,
        Bullet|array|null $bullet = null,
    ) {
        $this->style = $this->arrayToObject(class: ParagraphStyle::class, var: $style);
        $this->bullet = $this->arrayToObject(class: Bullet::class, var: $bullet);
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
