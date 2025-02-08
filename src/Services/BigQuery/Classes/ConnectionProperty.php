<?php

namespace Chmw\GoogleApi\Services\BigQuery\Classes;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://cloud.google.com/bigquery/docs/reference/rest/v2/ConnectionProperty
 * @param string $key
 * @param string $value
 * @return ConnectionProperty
 */
class ConnectionProperty implements Jsonable
{
    public string $key;
    public string $value;
    
    public function __construct(
        string $key,
        string $value,
    ) {
        $this->key = $key;
        $this->value = $value;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
