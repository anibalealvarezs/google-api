<?php

namespace Anibalealvarezs\GoogleApi\Services\BigQuery\Classes;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://cloud.google.com/bigquery/docs/reference/rest/v2/DataFormatOptions
 * @param bool $useInt64Timestamp
 * @return DataFormatOptions
 */
class DataFormatOptions implements Jsonable
{
    public bool $useInt64Timestamp;
    
    public function __construct(
        bool $useInt64Timestamp = false,
    ) {
        $this->useInt64Timestamp = $useInt64Timestamp;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
