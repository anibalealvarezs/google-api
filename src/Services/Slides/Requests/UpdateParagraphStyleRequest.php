<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellLocation;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Text\ParagraphStyle;
use Chmw\GoogleApi\Services\Slides\Classes\Presentations\Request\Range;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#updateparagraphstylerequest
 */
class UpdateParagraphStyleRequest implements Jsonable
{
    public string $objectId;
    public ParagraphStyle|array $style;
    public TableCellLocation|array|null $cellLocation;
    public Range|array|null $textRange;
    public string $fields;
    
    public function __construct(
        string $objectId,        
        ParagraphStyle|array $style,
        TableCellLocation|array|null $cellLocation = null,
        Range|array|null $textRange = null,
        string $fields = "*",
    ) {
        $this->objectId = $objectId;
        $this->style = $this->arrayToObject(class: ParagraphStyle::class, var: $style);
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
