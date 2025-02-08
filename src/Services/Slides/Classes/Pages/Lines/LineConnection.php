<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Lines;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/lines#lineconnection
 */
class LineConnection implements Jsonable
{
    public string $connectedObjectId;
    public int $connectionSiteIndex;

    public function __construct(
        string $connectedObjectId,
        int $connectionSiteIndex,
    ) {
        $this->connectedObjectId = $connectedObjectId;
        $this->connectionSiteIndex = $connectionSiteIndex;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
