<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellLocation;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#InsertTextRequest
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#Page.TextElement (for text indexes reference)
 */
class InsertTextRequest implements Jsonable
{
    public string $objectId;
    public string $text;
    public int $insertionIndex;
    public TableCellLocation|array|null $cellLocation;
    
    public function __construct(
        string $objectId,
        string $text,
        int $insertionIndex = 0,
        TableCellLocation|array|null $cellLocation = null,
    ) {
        $this->objectId = $objectId;
        $this->text = $text;
        $this->insertionIndex = $insertionIndex;
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
