<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#embeddedobjectposition
 */
class EmbeddedObjectPosition implements Jsonable
{
    public ?int $sheetId;
    public OverlayPosition|array|null $overlayPosition;
    public ?bool $newSheet;
    
    public function __construct(
        ?int $sheetId = null,
        OverlayPosition|array|null $overlayPosition = null,
        ?bool $newSheet = null,
    ) {
        $this->sheetId = $sheetId;
        $this->overlayPosition = $this->arrayToObject(class: OverlayPosition::class, var: $overlayPosition);
        $this->newSheet = $newSheet;

        $this->keepOneOfKind([
            'sheetId',
            'overlayPosition',
            'newSheet'
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

    public function arrayToObject(string $class, mixed $var): mixed
    {
        if (is_array($var)) {
            return new $class(...$var);
        }
        return $var;
    }
}
