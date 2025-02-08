<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Spreadsheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\DimensionRange;
use Chmw\GoogleApi\Services\Sheets\Enums\Spreadsheets\DeveloperMetadataLocationType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.developerMetadata#developermetadatalocation
 */
class DeveloperMetadataLocation implements Jsonable
{
    public ?int $sheetId;
    public ?bool $spreadsheet;
    public DimensionRange|array|null $dimensionRange;
    public DeveloperMetadataLocationType|string $locationType;
    
    public function __construct(
        ?int $sheetId = null,
        ?bool $spreadsheet = null,
        DimensionRange|array|null $dimensionRange = null,
        DeveloperMetadataLocationType|string $locationType = DeveloperMetadataLocationType::ROW,
    ) {
        $this->sheetId = $sheetId;
        $this->spreadsheet = $spreadsheet;
        $this->dimensionRange = $this->arrayToObject(class: DimensionRange::class, var: $dimensionRange);
        $this->locationType = $this->stringToEnum(enum: DeveloperMetadataLocationType::class, var: $locationType);

        $this->keepOneOfKind([
            'sheetId',
            'spreadsheet',
            'dimensionRange'
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

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
