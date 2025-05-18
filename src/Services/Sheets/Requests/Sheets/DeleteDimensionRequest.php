<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Sheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\DimensionRange;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request?hl=es-419#deletedimensionrequest
 */
class DeleteDimensionRequest implements Jsonable
{
    public DimensionRange|array|null $range;
    
    public function __construct(
        DimensionRange|array|null $range = null,
    ) {
        $this->range = $this->arrayToObject(class: DimensionRange::class, var: $range);
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
