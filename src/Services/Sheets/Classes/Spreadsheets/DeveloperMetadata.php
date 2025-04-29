<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Spreadsheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Spreadsheets\DeveloperMetadataVisibility;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.developerMetadata#resource:-developermetadata
 */
class DeveloperMetadata implements Jsonable
{
    public int $metadataId;
    public string $metadataKey;
    public string $metadataValue;
    public DeveloperMetadataLocation|array $location;
    public DeveloperMetadataVisibility|string $visibility;
    
    public function __construct(
        int $metadataId,
        string $metadataKey,
        string $metadataValue,
        DeveloperMetadataLocation|array $location,
        DeveloperMetadataVisibility|string $visibility = DeveloperMetadataVisibility::DOCUMENT,
    ) {
        $this->metadataId = $metadataId;
        $this->metadataKey = $metadataKey;
        $this->metadataValue = $metadataValue;
        $this->location = $this->arrayToObject(class: DeveloperMetadataLocation::class, var: $location);
        $this->visibility = $this->stringToEnum(enum: DeveloperMetadataVisibility::class, var: $visibility);
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
