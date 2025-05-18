<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Requests;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellLocation;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\Range;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#DeleteTextRequest
 */
class DeleteTextRequest implements Jsonable
{
    public string $objectId;
    public Range|array|null $textRange;
    public TableCellLocation|array|null $cellLocation;
    
    public function __construct(
        string $objectId,
        Range|array|null $textRange = null,
        TableCellLocation|array|null $cellLocation = null,
    ) {
        $this->objectId = $objectId;
        $this->textRange = $this->arrayToObject(class: Range::class, var: $textRange);
        $this->cellLocation = $this->arrayToObject(class: TableCellLocation::class, var: $cellLocation);
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
