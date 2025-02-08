<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#textrotation
 */
class TextRotation implements Jsonable
{
    public ?int $angle;
    public ?bool $vertical;
    
    public function __construct(
        ?int $angle = null,
        ?bool $vertical = null,
    ) {
        $this->angle = $angle;
        $this->vertical = $vertical;

        $this->keepOneOfKind([
            'angle',
            'vertical'
        ]);
    }

    public function keepOneOfKind(array $properties): void
    {
        if ($key = Helpers::getFirstNotNullPropertyFrom($this, $properties)) {
            Helpers::nullifyOtherProperties($this, $key);
        }
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
