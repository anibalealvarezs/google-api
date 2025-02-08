<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#link
 */
class Link implements Jsonable
{
    public string $uri;
    
    public function __construct(
        string $uri
    ) {
        $this->uri = $uri;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
