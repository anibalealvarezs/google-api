<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#masterproperties
 */
class MasterProperties implements Jsonable
{
    public string $displayName;
    
    public function __construct(
        string $displayName,
    ) {
        $this->displayName = $displayName;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
