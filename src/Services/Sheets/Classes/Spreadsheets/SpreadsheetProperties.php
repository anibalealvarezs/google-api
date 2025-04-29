<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Spreadsheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells\CellFormat;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Spreadsheets\RecalculationInterval;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets#spreadsheetproperties
 */
class SpreadsheetProperties implements Jsonable
{
    public string $title;
    public string $locale;
    public string $timeZone;
    public CellFormat|array $defaultFormat;
    public IterativeCalculationSettings|array $iterativeCalculationSettings;
    public SpreadsheetTheme|array $spreadsheetTheme;
    public RecalculationInterval|string $autoRecalc;
    
    public function __construct(
        string $title,
        string $locale,
        string $timeZone,
        CellFormat|array $defaultFormat,
        IterativeCalculationSettings|array $iterativeCalculationSettings,
        SpreadsheetTheme|array $spreadsheetTheme,
        RecalculationInterval|string $autoRecalc = RecalculationInterval::MINUTE,
    ) {
        $this->title = $title;
        $this->locale = $locale;
        $this->timeZone = $timeZone;
        $this->defaultFormat = $this->arrayToObject(class: CellFormat::class, var: $defaultFormat);
        $this->iterativeCalculationSettings = $this->arrayToObject(class: IterativeCalculationSettings::class, var: $iterativeCalculationSettings);
        $this->spreadsheetTheme = $this->arrayToObject(class: SpreadsheetTheme::class, var: $spreadsheetTheme);
        $this->autoRecalc = $this->stringToEnum(enum: RecalculationInterval::class, var: $autoRecalc);
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
