<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Requests;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellLocation;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Text\TextStyle;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\Range;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#updatetextstylerequest
 */
class UpdateTextStyleRequest implements Jsonable
{
    public string $objectId;
    public TextStyle|array $style;
    public TableCellLocation|array|null $cellLocation;
    public Range|array|null $textRange;
    public string $fields;
    
    public function __construct(
        string $objectId,        
        TextStyle|array $style,
        TableCellLocation|array|null $cellLocation = null,
        Range|array|null $textRange = null,
        string $fields = "*",
    ) {
        $this->objectId = $objectId;
        $this->style = $this->arrayToObject(class: TextStyle::class, var: $style);
        $this->cellLocation = $this->arrayToObject(class: TableCellLocation::class, var: $cellLocation);
        $this->textRange = $this->arrayToObject(class: Range::class, var: $textRange);
        $this->fields = $fields;
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
