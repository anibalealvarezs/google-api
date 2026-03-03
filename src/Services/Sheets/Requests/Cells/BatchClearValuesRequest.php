<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Cells;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.values/batchClear
 */
class BatchClearValuesRequest implements Jsonable
{
    public array $ranges;

    public function __construct(
        array $ranges
    ) {
        $this->ranges = $ranges;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
