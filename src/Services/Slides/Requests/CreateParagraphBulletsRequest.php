<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellLocation;
use Chmw\GoogleApi\Services\Slides\Classes\Presentations\Request\Range;
use Chmw\GoogleApi\Services\Slides\Enums\Presentations\Request\BulletGlyphPreset;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#createparagraphbulletsrequest
 */
class CreateParagraphBulletsRequest implements Jsonable
{
    public string $objectId;
    public Range|array|null $textRange;
    public TableCellLocation|array|null $cellLocation;
    public BulletGlyphPreset|string $bulletPreset;
    
    public function __construct(
        string $objectId,
        Range|array|null $textRange = null,
        TableCellLocation|array|null $cellLocation = null,
        BulletGlyphPreset|string $bulletPreset = BulletGlyphPreset::BULLET_DISC_CIRCLE_SQUARE
    ) {
        $this->objectId = $objectId;
        $this->textRange = $this->arrayToObject(class: Range::class, var: $textRange);
        $this->cellLocation = $this->arrayToObject(class: TableCellLocation::class, var: $cellLocation);
        $this->bulletPreset = $this->stringToEnum(enum: BulletGlyphPreset::class, var: $bulletPreset);
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

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
