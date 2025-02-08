<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#layoutproperties
 */
class LayoutProperties implements Jsonable
{
    public string $masterObjectId;
    public string $name;
    public string $displayName;
    
    public function __construct(
        string $masterObjectId,
        string $name,
        string $displayName,
    ) {
        $this->masterObjectId = $masterObjectId;
        $this->name = $name;
        $this->displayName = $displayName;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
