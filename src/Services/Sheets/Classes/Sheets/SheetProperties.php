<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Sheets\SheetType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#sheetproperties
 */
class SheetProperties implements Jsonable
{
    public string $title;
    public GridProperties|array $gridProperties;
    public ColorStyle|array|null $tabColorStyle;
    public ?int $index;
    public ?int $sheetId;
    public DataSourceSheetProperties|array|null $dataSourceSheetProperties;
    public bool $hidden;
    public bool $rightToLeft;
    public readonly SheetType|string $sheetType;

    public function __construct(
        string $title,
        GridProperties|array $gridProperties,
        ColorStyle|array|null $tabColorStyle = null,
        ?int $index = null,
        ?int $sheetId = null,
        DataSourceSheetProperties|array|null $dataSourceSheetProperties = null,
        bool $hidden = false,
        bool $rightToLeft = false,
        SheetType|string $sheetType = SheetType::GRID,
    ) {
        $this->title = $title;
        $this->gridProperties = $this->arrayToObject(class: GridProperties::class, var: $gridProperties);
        $this->tabColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $tabColorStyle);
        $this->index = $index;
        $this->sheetId = $sheetId;
        $this->dataSourceSheetProperties = $this->arrayToObject(class: DataSourceSheetProperties::class, var: $dataSourceSheetProperties);
        $this->hidden = $hidden;
        $this->rightToLeft = $rightToLeft;
        $this->sheetType = $this->stringToEnum(enum: SheetType::class, var: $sheetType);
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
