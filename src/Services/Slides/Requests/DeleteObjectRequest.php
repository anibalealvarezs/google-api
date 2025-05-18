<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Requests;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#DeleteObjectRequest
 */
class DeleteObjectRequest implements Jsonable
{
    public string $objectId;
    
    public function __construct(
        string $objectId
    ) {
        $this->objectId = $objectId;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
