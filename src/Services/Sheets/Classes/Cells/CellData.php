<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ExtendedValue;
use Chmw\GoogleApi\Services\Sheets\Classes\PivotTables\PivotTable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#celldata
 * @param ExtendedValue|array|null $userEnteredValue
 * @param ExtendedValue|array|null $effectiveValue
 * @param string|null $formattedValue
 * @param CellFormat|array|null $userEnteredFormat
 * @param CellFormat|array|null $effectiveFormat
 * @param array|null $textFormatRuns
 * @param PivotTable|array|null $pivotTable
 * @param DataSourceTable|array|null $dataSourceTable
 * @param DataSourceFormula|array|null $dataSourceFormula
 * @param string|null $hyperlink
 * @param string|null $note
 * @param DataValidationRule|array|null $dataValidation
 * @return CellData
 */
class CellData implements Jsonable
{
    public ExtendedValue|array|null $userEnteredValue;
    public readonly ExtendedValue|array|null $effectiveValue;
    public readonly ?string $formattedValue;
    public CellFormat|array|null $userEnteredFormat;
    public readonly CellFormat|array|null $effectiveFormat;
    public ?array $textFormatRuns;
    public PivotTable|array|null $pivotTable;
    public DataSourceTable|array|null $dataSourceTable;
    public readonly DataSourceFormula|array|null $dataSourceFormula;
    public ?string $hyperlink;
    public ?string $note;
    public DataValidationRule|array|null $dataValidation;
    
    public function __construct(
        ExtendedValue|array|null $userEnteredValue = null,
        ExtendedValue|array|null $effectiveValue = null,
        ?string $formattedValue = null,
        CellFormat|array|null $userEnteredFormat = null,
        CellFormat|array|null $effectiveFormat = null,
        ?array $textFormatRuns = null,
        PivotTable|array|null $pivotTable = null,
        DataSourceTable|array|null $dataSourceTable = null,
        DataSourceFormula|array|null $dataSourceFormula = null,
        ?string $hyperlink = null,
        ?string $note = null,
        DataValidationRule|array|null $dataValidation = null,
    ) {
        $this->userEnteredValue = $this->arrayToObject(class: ExtendedValue::class, var: $userEnteredValue);
        $this->effectiveValue = $this->arrayToObject(class: ExtendedValue::class, var: $effectiveValue);
        $this->formattedValue = $formattedValue;
        $this->userEnteredFormat = $this->arrayToObject(class: CellFormat::class, var: $userEnteredFormat);
        $this->effectiveFormat = $this->arrayToObject(class: CellFormat::class, var: $effectiveFormat);
        $this->textFormatRuns = $textFormatRuns;
        $this->pivotTable = $this->arrayToObject(class: PivotTable::class, var: $pivotTable);
        $this->dataSourceTable = $this->arrayToObject(class: DataSourceTable::class, var: $dataSourceTable);
        $this->dataSourceFormula = $this->arrayToObject(class: DataSourceFormula::class, var: $dataSourceFormula);
        $this->hyperlink = $hyperlink;
        $this->note = $note;
        $this->dataValidation = $this->arrayToObject(class: DataValidationRule::class, var: $dataValidation);
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
