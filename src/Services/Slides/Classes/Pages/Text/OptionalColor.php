<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Text;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\OpaqueColor;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#optionalcolor
 */
class OptionalColor implements Jsonable
{
    public OpaqueColor|array|null $opaqueColor;
    
    public function __construct(
        OpaqueColor|array|null $opaqueColor = null,
    ) {
        $this->opaqueColor = $this->arrayToObject(class: OpaqueColor::class, var: $opaqueColor);
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
