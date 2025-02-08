<?php

namespace Chmw\GoogleApi\Services\Sheets\Requests\Spreadsheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\GridRange;
use Chmw\GoogleApi\Services\Sheets\Enums\Spreadsheets\PasteOrientation;
use Chmw\GoogleApi\Services\Sheets\Enums\Spreadsheets\PasteType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request#copypasterequest
 */
class CopyPasteRequest implements Jsonable
{
    public GridRange|array|null $source;
    public GridRange|array|null $destination;
    public PasteType $pasteType;
    public PasteOrientation $pasteOrientation;
    
    public function __construct(
        GridRange|array|null $source = null,
        GridRange|array|null $destination = null,
        PasteType $pasteType = PasteType::PASTE_NORMAL,
        PasteOrientation $pasteOrientation = PasteOrientation::NORMAL,
    ) {
        $this->source = $this->arrayToObject(class: GridRange::class, var: $source);
        $this->destination = $this->arrayToObject(class: GridRange::class, var: $destination);
        $this->pasteType = $pasteType;
        $this->pasteOrientation = $pasteOrientation;
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
