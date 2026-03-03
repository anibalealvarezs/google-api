# Google Sheets

## Index

- [Google Sheets](#google-sheets)
  - [Index](#index)
  - [Raw requests](#raw-requests)
    - [Raw: Create sheet](#raw-create-sheet)
    - [Raw: Write cells](#raw-write-cells)
    - [Raw: Update cells](#raw-update-cells)
    - [Raw: Add chart](#raw-add-chart)
  - [Request methods](#request-methods)
    - [Spreadsheets](#spreadsheets)
      - [createSpreadsheet](#createspreadsheet)
    - [Sheets](#sheets)
    - [Cells](#cells)
      - [appendCells](#appendcells)
      - [readMultipleCells](#readmultiplecells)
      - [updateMultipleCellsValues](#updatemultiplecellsvalues)
      - [clearMultipleCells](#clearmultiplecells)
    - [Charts](#charts)
  - [Objects](#objects)
    - [Spreadsheets](#spreadsheets-1)
      - [Spreadsheet](#spreadsheet)
    - [Sheets](#sheets-1)
      - [addSheet](#addsheet)
    - [Cells](#cells-1)
      - [updateCells](#updatecells)
      - [ValueRange](#valuerange)
      - [BatchUpdateValuesRequest](#batchupdatevaluesrequest)
      - [BatchClearValuesRequest](#batchclearvaluesrequest)
    - [Charts](#charts-1)
      - [EmbeddedChart](#embeddedchart)
      - [ChartData](#chartdata)
      - [DataLabel](#datalabel)
    - [Others](#others)
      - [ExtendedValue](#extendedvalue)
      - [Border](#border)
      - [TextFormat](#textformat)
      - [ColorStyle](#colorstyle)
      - [GridRange](#gridrange)
  - [Enums](#enums)

---

## Raw requests

The following examples show the simplest requests possible depending on the action you want to perform.

---

### Raw: Create sheet

The following is quite a simple example of adding a sheet, according to the [object structure](#addsheet).

```php
$this->performRequest(
    method: "POST",
    endpoint: '1234567890:batchUpdate',
    body: json_encode(
      [
        "requests" => [
          [
            "addSheet": [
              "properties" => [
                "title" => "My sheet"
              ]
            ]
          ]
        ]
      ]
    ),
);
```

---

### Raw: Write cells

The request body contains an instance of [ValueRange](/src/Services/Sheets/Classes/Cells/ValueRange.php)

```php
$this->performRequest(
    method: "PUT",
    endpoint: '1234567890/values/Sheet1!A1:B2',
    query: [
        "valueInputOption" => 'USER_ENTERED',
    ],
    body: json_encode([
      "range": 'Sheet1!A1:B2',
      "values" => [
          [
              "A1", "B1", "C1"
          ],
          [
              "A2", "B2", "C2"
          ],
          [
              "A3", "B3", "C3"
          ]
      ],
      "majorDimension": "ROWS"
    ]),
);
```

---

### Raw: Update cells

The following is quite a simple example of updating cells, according to the [object structure](#updatecells).

```php
$this->performRequest(
    method: "POST",
    endpoint: '1234567890:batchUpdate',
    body: json_encode(
      [
        "requests" => [
          [
            "updateCells": [
              "range": [
                  "sheetId": 0,
                  "startRowIndex": 0,
                  "endRowIndex": 3,
                  "startColumnIndex": 0,
                  "endColumnIndex": 3
              ],
              "rows": [
                  [
                      "values": [
                          [
                              "userEnteredValue": [
                                  "stringValue": "A1"
                              ]
                          ],
                          [
                              "userEnteredValue": [
                                  "stringValue": "B1"
                              ]
                          ],
                          [
                              "userEnteredValue": [
                                  "stringValue": "C1"
                              ]
                          ]
                      ]
                  ],
                  [
                      "values": [
                          [
                              "userEnteredValue": [
                                  "stringValue": "A2"
                              ]
                          ],
                          [
                              "userEnteredValue": [
                                  "stringValue": "B2"
                              ]
                          ],
                          [
                              "userEnteredValue": [
                                  "stringValue": "C2"
                              ]
                          ]
                      ]
                  ],
                  [
                      "values": [
                          [
                              "userEnteredValue": [
                                  "stringValue": "A3"
                              ]
                          ],
                          [
                              "userEnteredValue": [
                                  "stringValue": "B3"
                              ]
                          ],
                          [
                              "userEnteredValue": [
                                  "stringValue": "C3"
                              ]
                          ]
                      ]
                  ]
              ],
              "fields": "userEnteredValue"
            ]
          ]
        ]
      ]
    ),
);
```

---

### Raw: Add chart

The following is quite a simple example of adding a chart to a sheet, according to the [object structure](#embeddedchart).

```php
$this->performRequest(
    method: "POST",
    endpoint: '1234567890:batchUpdate',
    body: json_encode(
      [
        "requests" => [
          [
            "addChart": [
              "chart": [
                  "spec": [
                      "title": "Chart title",
                      "basicChart": [
                          "chartType": "COLUMN",
                          "legendPosition": "BOTTOM_LEGEND",
                          "axis": [
                              [
                                  "position": "BOTTOM_AXIS",
                                  "title": "X axis"
                              ],
                              [
                                  "position": "LEFT_AXIS",
                                  "title": "Y axis"
                              ]
                          ],
                          "domains": [
                              [
                                  "domain": [
                                      "sourceRange": [
                                          "sources": [
                                              [
                                                  "sheetId": 0,
                                                  "startRowIndex": 0,
                                                  "endRowIndex": 1,
                                                  "startColumnIndex": 0,
                                                  "endColumnIndex": 1
                                              ]
                                          ]
                                      ]
                                  ]
                              ]
                          ],
                          "series": [
                              [
                                  "series": [
                                      "sourceRange": [
                                          "sources": [
                                              [
                                                  "sheetId": 0,
                                                  "startRowIndex": 0,
                                                  "endRowIndex": 1,
                                                  "startColumnIndex": 1,
                                                  "endColumnIndex": 2
                                              ]
                                          ]
                                      ]
                                  ],
                                  "targetAxis": "LEFT_AXIS"
                              ]
                          ]
                      ]
                  ],
                  "position": [
                      "newSheet": true
                  ],
              ]
            ]
          ]
        ]
      ]
    ),
);
```

---

## Request methods

The following are non-static methods that can be called from instances (client instances) of the [SheetsApi](/src/Services/Sheets/SheetsApi.php) class.

---

### Spreadsheets

- ### getSpreadsheetData: _Array_

  `Gets the spreadsheet data.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required

        - `spreadsheetId`: *Integer*
        ID of the Spreadsheet to get data from.

    </details><br>

- ### createSpreadsheet(): _Array_

  `Creates a new spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Optional

        - `spreadsheet`: *Spreadsheet | Array*
        The [Spreadsheet](#spreadsheet) object or array with the properties for the new spreadsheet.

    </details><br>

---

### Sheets

- ### clearSheet(): _Array_

  `Clears the sheet.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _String_  
      ID of the spreadsheet where the sheet is located.
  - Optional

        #### Union field (only one of the following is allowed)
        *Note:* If none of the following is specified, the first sheet will be cleared

        - `sheetId`: *Integer | null*
            The ID of the sheet to be cleared.
            Defaults to `null`.

        - `sheetIndex`: *Integer | null*
            The index of the sheet to be cleared.
            Defaults to `null`.

        - `sheetTitle`: *String | null*
            Title of the sheet to be cleared.
            Defaults to `null`.

        #### End union field

        - `method`: [*ClearSheetMethods*](/src/Services/Sheets/Enums/ClearSheetMethods.md)
        The method to use to clear the sheet.
        Defaults to `ClearSheetMethods::CLEAR_CELLS`.

    </details><br>

- ### copySheet(): _Array_

  `Copies a sheet from one spreadsheet to another (or duplicates it in the same spreadsheet).`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `sourceSpreadsheetId`: _String_  
      ID of the Spreadsheet to copy the sheet from.
  - `sheetId`: _Integer_  
    ID of the sheet to be copied.
  - Optional

        - `destinySpreadsheetId`: *Integer | null*
        ID of the Spreadsheet to copy the sheet to. If not specified, the sheet will be copied to the same spreadsheet.
        Defaults to `null`.

    </details><br>

- ### createSheet(): _Array_

  `Creates a new sheet in the spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _String_  
      ID of the Spreadsheet to add the chart to.
  - `index`: _Integer_  
    The zero-based index where the sheet should be inserted.
  - Optional

        - `title`: *String*,
        The title of the chart.
        Defaults to `"New Chart"`.

        - `sheetId`: *Integer | null*
        A custom ID to identify the sheet. If not specified, an ID will be randomly generated.
        Defaults to `null`.

        - `spreadsheetData`: *Array | null*
        The spreadsheet data, which can be obtained through the [getSpreadsheetData()](/docs/Services/Sheets/Requests/Spreadsheets/getSpreadsheetData.md) method. Submitting this data will save a request to the API.
        If this parameter is not specified, the data will be fetched from the spreadsheet when needed.
        Defaults to `null`.

    </details><br>

- ### createSheetAtStart(): _Array_

  `Creates a new sheet at the start of the spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _String_  
      ID of the Spreadsheet to add the chart to.
  - Optional

        - `title`: *String*,
        The title of the chart.
        Defaults to `"New Chart"`.

        - `sheetId`: *Integer | null*
        A custom ID to identify the sheet. If not specified, an ID will be randomly generated.
        Defaults to `null`.

    </details><br>

- ### createSheetAtTheEnd(): _Array_

  `Creates a new sheet at the end of the spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _String_  
      ID of the Spreadsheet to add the chart to.
  - Optional

        - `title`: *String*,
        The title of the chart.
        Defaults to `"New Chart"`.

        - `sheetId`: *Integer | null*
        A custom ID to identify the sheet. If not specified, an ID will be randomly generated.
        Defaults to `null`.

        - `spreadsheetData`: *Array | null*
        The spreadsheet data, which can be obtained through the [getSpreadsheetData()](/docs/Services/Sheets/Requests/Spreadsheets/getSpreadsheetData.md) method. Submitting this data will save a request to the API.
        If this parameter is not specified, the data will be fetched from the spreadsheet when needed.
        Defaults to `null`.

    </details><br>

- ### deleteSheet(): _Array_

  `Deletes a sheet from the spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _String_  
      ID of the Spreadsheet to add the chart to.
  - `sheetId`: _Integer_  
    ID of the sheet to delete.
  - Optional

        - `spreadsheetData`: *Array | null*
        The spreadsheet data, which can be obtained through the [getSpreadsheetData()](/docs/Services/Sheets/Requests/Spreadsheets/getSpreadsheetData.md) method. Submitting this data will save a request to the API.
        If this parameter is not specified, the data will be fetched from the spreadsheet when needed.
        Defaults to `null`.

    </details><br>

---

### Cells

- ### clearCells(): _Array_

  `Clears a range of cells.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _String_  
      ID of the spreadsheet where the sheet is located.
  - `sheetId`: _Integer_  
    ID of the sheet to delete.
  - Optional

        #### Union field (only one of the following is allowed)
        *Note:* If none of the following is specified, the first sheet will be cleared

        - `sheetId`: *Integer | null*
            The ID of the sheet to clear cells from.
            Defaults to `null`.

        - `sheetIndex`: *Integer | null*
            The index of the sheet to clear cells from.
            Defaults to `null`.

        - `sheetTitle`: *String | null*
            Title of the sheet to clear cells from.
            Defaults to `null`.

        #### End union field

        - `startColumnIndex`: *String | Integer*
        The column index of the first cell to clear.
        Defaults to `"A"`.

        - `startRowIndex`: *Integer*
        The row index of the first cell to clear.
        Defaults to `1`.

        - `endColumnIndex`: *String | Integer*
        The column index of the last cell to clear.
        Defaults to `"ZZ"`.

        - `endRowIndex`: *Integer*
        The row index of the first cell to clear.
        Defaults to `1000000`.

        - `spreadsheetData`: *Array | null*
        The spreadsheet data, which can be obtained through the [getSpreadsheetData()](/docs/Services/Sheets/Requests/Spreadsheets/getSpreadsheetData.md) method. Submitting this data will save a request to the API.
        If this parameter is not specified, the data will be fetched from the spreadsheet when needed.
        Defaults to `null`.

    </details><br>

- ### readCells(): _Array_

  `Reads a range of cells.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _String_  
      ID of the spreadsheet where the sheet is located.
  - `sheetId`: _Integer_  
    ID of the sheet to delete.
  - Optional

        #### Union field (only one of the following is allowed)
        *Note:* If none of the following is specified, the first sheet will be cleared

        - `sheetId`: *Integer | null*
            The ID of the sheet to read cells from.
            Defaults to `null`.

        - `sheetIndex`: *Integer | null*
            The index of the sheet to read cells from.
            Defaults to `null`.

        - `sheetTitle`: *String | null*
            Title of the sheet to read cells from.
            Defaults to `null`.

        #### End union field

        - `startColumnIndex`: *String | Integer*
        The column index of the first cell to read.
        Defaults to `"A"`.

        - `startRowIndex`: *Integer*
        The row index of the first cell to read.
        Defaults to `1`.

        - `endColumnIndex`: *String | Integer*
        The column index of the last cell to read.
        Defaults to `"ZZ"`.

        - `endRowIndex`: *Integer*
        The row index of the first cell to read.
        Defaults to `1000000`.

        - `spreadsheetData`: *Array | null*
        The spreadsheet data, which can be obtained through the [getSpreadsheetData()](/docs/Services/Sheets/Requests/Spreadsheets/getSpreadsheetData.md) method. Submitting this data will save a request to the API.
        If this parameter is not specified, the data will be fetched from the spreadsheet when needed.
        Defaults to `null`.

    </details><br>

- ### updateCells(): _Array_

  `Updates a range of cells.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _String_  
      ID of the spreadsheet where the sheet is located.
  - `sheetId`: _Integer_  
    ID of the sheet to delete.
  - Optional

        - `rows`: *Array*
        Updating rows' data and attributes.
        Defaults to `[]`.

        - `data`: *Array*
        Updating rows' data and attributes.  Simpler array format where each cell is an array of values that specifies the type and the value. Ex: `[['type' => 'string', 'value' => 'Hello'], ['type' => 'number', 'value' => 123]]`.
        If "type" is not specified, it will be considered as `string`.
        Defaults to `[]`.

        - `fields`: *String*
        Fields to update. Fields not specified will be ignored.
        Defaults to `'*'`.

        #### Union field (only one of the following is allowed)
        *Note:* If none of the following is specified, the first sheet will be cleared

        - `sheetId`: *Integer | null*
            The ID of the sheet to update cells to.
            Defaults to `null`.

        - `sheetIndex`: *Integer | null*
            The index of the sheet to update cells to.
            Defaults to `null`.

        - `sheetTitle`: *String | null*
            Title of the sheet to update cells to.
            Defaults to `null`.

        #### End union field

        - `startColumnIndex`: *String | Integer*
        The column index of the first cell to be updated.
        Defaults to `1`.

        - `startRowIndex`: *Integer*
        The row index of the first cell to be updated.
        Defaults to `1`.

        - `endColumnIndex`: *String | Integer*
        The column index of the last cell to be updated.
        Defaults to `1000000`.

        - `endRowIndex`: *Integer*
        The row index of the first cell to be updated.
        Defaults to `1000000`.

        - `spreadsheetData`: *Array | null*
        The spreadsheet data, which can be obtained through the [getSpreadsheetData()](/docs/Services/Sheets/Requests/Spreadsheets/getSpreadsheetData.md) method. Submitting this data will save a request to the API.
        If this parameter is not specified, the data will be fetched from the spreadsheet when needed.
        Defaults to `null`.

    </details><br>

- ### writeCells(): _Array_

  `Updates a range of cells.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _String_  
      ID of the spreadsheet where the sheet is located.
  - Optional

        - `data`: *Array*
        Data to be written to the cells.
        Defaults to `[]`.

        - `majorDimension`: [*Dimension*](/docs/Services/Sheets/Requests/Dimension.md)
        The major dimension that results should use.
        Defaults to `Dimension::ROWS`.

        #### Union field (only one of the following is allowed)
        *Note:* If none of the following is specified, the first sheet will be cleared

        - `sheetId`: *Integer | null*
            The ID of the sheet to write data to.
            Defaults to `null`.

        - `sheetIndex`: *Integer | null*
            The index of the sheet to write data to.
            Defaults to `null`.

        - `sheetTitle`: *String | null*
            Title of the sheet to write data to.
            Defaults to `null`.

        #### End union field

        - `startColumnIndex`: *String | Integer*
        The column index of the first cell to be written into.
        Defaults to `"A"`.

        - `startRowIndex`: *Integer*
        The row index of the first cell to be written into.
        Defaults to `1`.

        - `endColumnIndex`: *String | Integer*
        The column index of the last cell to be written into.
        Defaults to `"ZZ"`.

        - `endRowIndex`: *Integer*
        The row index of the first cell to be written into.
        Defaults to `1000000`.

        - `spreadsheetData`: *Array | null*
        The spreadsheet data, which can be obtained through the [getSpreadsheetData()](/docs/Services/Sheets/Requests/Spreadsheets/getSpreadsheetData.md) method. Submitting this data will save a request to the API.
        If this parameter is not specified, the data will be fetched from the spreadsheet when needed.
        Defaults to `null`.

        - `valueInputOption`: [*ValueInputOption*](/docs/Services/Sheets/Requests/ValueInputOption.md)
        How the input data should be interpreted.
        Defaults to `ValueInputOption::USER_ENTERED`.

        - `responseValueRenderOption`: [*ValueRenderOption*](/docs/Services/Sheets/Requests/ValueRenderOption.md)
        How values should be represented in the output.
        Defaults to `ValueRenderOption::FORMATTED_VALUE`.

        - `responseDateTimeRenderOption`: [*DateTimeRenderOption*](/docs/Services/Sheets/Requests/DateTimeRenderOption.md)
        How dates, times, and durations should be represented in the output.
        Defaults to `DateTimeRenderOption::SERIAL_NUMBER`.

- ### appendCells(): _Array_

  `Appends values to a spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _String_  
      ID of the spreadsheet where the sheet is located.
  - Optional

        - `data`: *Array*
        Data to be appended.

        - `range`: *String*
        The range to append the data to. Defaults to `"A1"`.

        - `valueInputOption`: [ValueInputOption](#valueinputoption)
        How the input data should be interpreted. Defaults to `ValueInputOption::USER_ENTERED`.

        - `insertDataOption`: [InsertDataOption](#insertdataoption)
        How the input data should be inserted. Defaults to `InsertDataOption::INSERT_ROWS`.

    </details><br>

- ### readMultipleCells(): _Array_

  `Returns one or more ranges of values from a spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _String_  
      ID of the spreadsheet where the sheet is located.
  - Optional

        - `ranges`: *Array*
        The ranges to retrieve from the spreadsheet.

        - `majorDimension`: [Dimension](#dimension)
        The major dimension that results should use. Defaults to `Dimension::ROWS`.

    </details><br>

- ### updateMultipleCellsValues(): _Array_

  `Sets values in one or more ranges of a spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required

        - `spreadsheetId`: *String*
        ID of the spreadsheet where the sheet is located.

        - `request`: *BatchUpdateValuesRequest | Array*
        The [BatchUpdateValuesRequest](#batchupdatevaluesrequest) object or array.

    </details><br>

- ### clearMultipleCells(): _Array_

  `Clears values from a spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required

        - `spreadsheetId`: *String*
        ID of the spreadsheet where the sheet is located.

        - `request`: *BatchClearValuesRequest | Array*
        The [BatchClearValuesRequest](#batchclearvaluesrequest) object or array.

    </details><br>

---

### Charts

- ### addChart(): _Array_

  `Adds a chart to a spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>
  - Required
    - `spreadsheetId`: _Integer_  
      ID of the Spreadsheet to add the chart to
  - `chartId`: _Integer_  
    Custom ID to identify the chart
  - `chartData`: _Array_  
    The chart's data in array format
  - Optional

        - `dataSourceId`: *String | null*
        The ID of the data source to use for the chart. If not specified, the chart will use the first data source in the spreadsheet.
        Defaults to `null`.

        - `filterSpecs`: *Array | null*
        The filters to apply to the chart.
        Defaults to `null`.

        - `sortSpecs`: *Array | null*
        If specified, the chart will be sorted by the specified data source.
        Defaults to `null`.

        - `title`: *String*,
        The title of the chart.
        Defaults to `"New Chart"`.

        - `subtitle`: *String*,
        The subtitle of the chart.
        Defaults to `""`.

        - `fontName`: *String*,
        The name of the font to use by default for all chart text (e.g. title, axis labels, legend).
        Defaults to `"Roboto"`.

        #### Union field (only one of the following is allowed)
        *Note:* If none of the following is specified, the first sheet will be cleared

        - `sheetId`: *Integer | null*
            The ID of the sheet to add the chart to.
            Defaults to `null`.

        - `sheetIndex`: *Integer | null*
            The index of the sheet to add the chart to.
            Defaults to `null`.

        - `sheetTitle`: *String | null*
            Title of the sheet to add the chart to.
            Defaults to `null`.

        #### End union field

        - `spreadsheetData`: *Array | null*
        The spreadsheet data, which can be obtained through the [getSpreadsheetData()](/docs/Services/Sheets/Requests/Spreadsheets/getSpreadsheetData.md) method. Submitting this data will save a request to the API.
        If this parameter is not specified, the data will be fetched from the spreadsheet when needed.
        Defaults to `null`.

        - `chartType`: [*ChartTypes*](/src/Services/Sheets/Enums/Charts/ChartTypes.md)
        The type of the chart to be added.
        Defaults to `ChartTypes::BASIC`.

    </details><br>

---

## Objects

---

### Spreadsheets

---

#### Spreadsheet

- `spreadsheetId`: _String_
- `properties`: [SpreadsheetProperties](#spreadsheetproperties)
- `sheets`: _Array_ of [Sheet](#sheet) objects.

---

### Sheets

---

#### addSheet

- `properties`: [_SheetProperties_](/src/Services/Sheets/Classes/Sheets/SheetProperties.php)
  - `sheetId`: _Integer_
  - `title`: _String_
  - `index`: _Integer_
  - `sheetType`: [_SheetType_](/src/Services/Sheets/Enums/Sheets/SheetType.php)
  - `gridProperties`: [_GridProperties_](/src/Services/Sheets/Classes/Sheets/GridProperties.php)
    - `rowCount`: _Integer_
    - `columnCount`: _Integer_
    - `frozenRowCount`: _Integer_
    - `frozenColumnCount`: _Integer_
    - `hideGridlines`: _Boolean_
    - `rowGroupControlAfter`: _Boolean_
    - `columnGroupControlAfter`: _Boolean_
  - `hidden`: _Boolean_
  - `tabColorStyle`: [_ColorStyle_](#colorstyle)
  - `rightToLeft`: _Boolean_
  - `dataSourceSheetProperties`: [_DataSourceSheetProperties_](../../../src/Services/Sheets/Classes/Sheets/DataSourceSheetProperties.php)
    - `dataSourceId`: _String_
    - `columns`: _Array_  
      _DataSourceColumn_
      - `reference`: [_DataSourceColumnReference_](/src/Services/Sheets/Classes/Sheets/DataSourceColumnReference.php)
        - `columnIndex`: _Integer_
        - `sheetId`: _Integer_
      - `formula`: _String_
    - `dataExecutionStatus`: _[DataExecutionStatus](/src/Services/Sheets/Classes/Other/DataExecutionStatus.php)_
      - `state`: _**enum**: [DataExecutionState](/src/Services/Sheets/Enums/Other/DataExecutionState.php)_
      - `errorCode`: _**enum**: [DataExecutionErrorCode](/src/Services/Sheets/Enums/Other/DataExecutionErrorCode.php)_
      - `errorMessage`: _String_
      - `lastRefreshTime`: _String_

---

### Cells

---

#### updateCells

- `rows`: _Array_  
  [_RowData_](/src/Services/Sheets/Classes/Sheets/RowData.php)
  - `values`: _Array_
    [_CellData_](/src/Services/Sheets/Classes/Cells/CellData.php)
    - `userEnteredValue`: [_ExtendedValue_](#extendedvalue)
    - `effectiveValue`: [_ExtendedValue_](#extendedvalue)
    - `formattedValue`: _String_
    - `userEnteredFormat`: [_CellFormat_](/src/Services/Sheets/Classes/Cells/CellFormat.php)
      - `numberFormat`: [_NumberFormat_](/src/Services/Sheets/Classes/Cells/NumberFormat.php)
        - `type`: _**enum**: [NumberFormatType](/src/Services/Sheets/Enums/Cells/NumberFormatType.php)_
        - `pattern`: _String_
      - `backgroundColorStyle`: [_ColorStyle_](#colorstyle)
      - `borders`: [_Borders_](/src/Services/Sheets/Classes/Cells/Borders.php)
        - `top`: [_Border_](#border)
        - `bottom`: [_Border_](#border)
        - `left`: [_Border_](#border)
        - `right`: [_Border_](#border)
      - `padding`: [_Padding_](/src/Services/Sheets/Classes/Cells/Padding.php)
        - `top`: _Integer_
        - `right`: _Integer_
        - `bottom`: _Integer_
        - `left`: _Integer_
      - `horizontalAlignment`: _**enum**: [HorizontalAlign](/src/Services/Sheets/Enums/Other/HorizontalAlign.php)_
      - `verticalAlignment`: _**enum**: [VerticalAlign](/src/Services/Sheets/Enums/Other/VerticalAlign.php)_
      - `wrapStrategy`: _**enum**: [WrapStrategy](/src/Services/Sheets/Enums/Cells/WrapStrategy.php)_
      - `textDirection`: _**enum**: [TextDirection](/src/Services/Sheets/Enums/Cells/TextDirection.php)_
      - `textFormat`: _[TextFormat](#textformat)_
      - `hyperlinkDisplayType`: _**enum**: [HyperlinkDisplayType](/src/Services/Sheets/Enums/Cells/HyperlinkDisplayType.php)_
      - `textRotation`: [_TextRotation_](/src/Services/Sheets/Classes/Cells/TextRotation.php)
        - `angle`: _Integer_
        - `vertical`: _Boolean_

---

#### ValueRange

- `range`: _String_
- `values`: _Array_ (2D array of values)
- `majorDimension`: [Dimension](#dimension)

---

#### BatchUpdateValuesRequest

- `valueInputOption`: [ValueInputOption](#valueinputoption)
- `data`: _Array_ of [ValueRange](#valuerange) objects.
- `includeValuesInResponse`: _Boolean_
- `responseValueRenderOption`: [ValueRenderOption](#valuerenderoption)
- `responseDateTimeRenderOption`: [DateTimeRenderOption](#datetimerenderoption)

---

#### BatchClearValuesRequest

- `ranges`: _Array_ of _String_ (A1 notation ranges).

---

### Charts

---

#### [EmbeddedChart](../../../src/Services/Sheets/Classes/Spreadsheets/EmbeddedChart.php)

- `chartId`: _Integer_
- `spec`: _[ChartSpec](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Spreadsheets/EmbeddedChart.php)_
  - `title`: _String_
  - `altText`: _String_
  - `titleTextFormat`: _[TextFormat](#textformat)_
  - `subtitle`: _String_
  - `subtitleTextFormat`: _[TextFormat](#textformat)_
  - `subtitlePosition`: _[TextPosition](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Other/TextPosition.php)_
    - `horizontalAlignment`: _**enum**: [HorizontalAlign](/src/Services/Sheets/Enums/Other/HorizontalAlign.php)_
  - `fontName`: _String_
  - `maximized`: _Boolean_
  - `backgroundColorStyle`: _[ColorStyle](#colorstyle)_
  - `dataSourceChartProperties`: _[DataSourceChartProperties](/src/Services/Sheets/Classes/Charts/DataSourceChartProperties.php)_
    - `dataSourceId`: _String_
    - `dataExecutionStatus`: _[DataExecutionStatus](/src/Services/Sheets/Classes/Other/DataExecutionStatus.php)_
      - `state`: _**enum**: [DataExecutionState](/src/Services/Sheets/Enums/Other/DataExecutionState.php)_
      - `errorCode`: _**enum**: [DataExecutionErrorCode](/src/Services/Sheets/Enums/Other/DataExecutionErrorCode.php)_
      - `errorMessage`: _String_
      - `lastRefreshTime`: _String_
  - `filterSpecs`: _Array_  
    _[FilterSpec](/src/Services/Sheets/Classes/Other/FilterSpec.php)_
    - `filterCriteria`: _[FilterCriteria](/src/Services/Sheets/Classes/Other/FilterCriteria.php)_
      - `hiddenValues`: _Array_
        _String_
      - `condition`: _[BooleanCondition](/src/Services/Sheets/Classes/Other/BooleanCondition.php)_
      - `visibleBackgroundColorStyle`: _[ColorStyle](#colorstyle)_
      - `visibleForegroundColorStyle`: _[ColorStyle](#colorstyle)_
    - `columnIndex`: _Integer_
    - `dataSourceColumnReference`: _[DataSourceColumnReference](/src/Services/Sheets/Classes/Charts/DataSourceColumnReference.php)_
      - `name`: _String_
  - `sortSpecs`: _Array_  
    _[SortSpec](/src/Services/Sheets/Classes/Other/SortSpec.php)_
    - `sortOrder`: _**enum**: [SortOrder](/src/Services/Sheets/Enums/Other/SortOrder.php)_
    - `foregroundColorStyle`: _[ColorStyle](#colorstyle)_
    - `backgroundColorStyle`: _[ColorStyle](#colorstyle)_
    - `dimensionIndex`: _Integer_
    - `dataSourceColumnReference`: _[DataSourceColumnReference](/src/Services/Sheets/Classes/Charts/DataSourceColumnReference.php)_
      - `name`: _String_
  - `hiddenDimensionStrategy`: _**enum**: [ChartHiddenDimensionStrategy](/src/Services/Sheets/Enums/Charts/ChartHiddenDimensionStrategy.php)_
  - `basicChart`: _[BasicChartSpec](/src/Services/Sheets/Classes/Charts/Basic/BasicChartSpec.php)_
    - `chartType`: _**enum**: [BasicChartType](/src/Services/Sheets/Enums/Charts/Basic/BasicChartType.php)_
    - `legendPosition`: _**enum**: [BasicChartLegendPosition](/src/Services/Sheets/Enums/Charts/Basic/BasicChartLegendPosition.php)_
    - `axis`: _Array_
      _[BasicChartAxis](/src/Services/Sheets/Classes/Charts/Basic/BasicChartAxis.php)_
      - `position`: _**enum**: BasicChartAxisPosition_
      - `title`: _String_
      - `format`: _[TextFormat](#textformat)_
      - `titleTextPosition`: _[TextPosition](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Other/TextPosition.php)_
        - `horizontalAlignment`: _**enum**: [HorizontalAlign](/src/Services/Sheets/Enums/Other/HorizontalAlign.php)_
      - `viewWindowOptions`: _[ChartAxisViewWindowOptions](/src/Services/Sheets/Classes/Charts/ChartAxisViewWindowOptions.php)_
        - `viewWindowMin`: _Float_
        - `viewWindowMax`: _Float_
        - `viewWindowMode`: _**enum**: [ViewWindowMode](/src/Services/Sheets/Enums/Charts/ViewWindowMode.php)_
    - `domains`: _Array_
      _[BasicChartDomain](/src/Services/Sheets/Classes/Charts/Basic/BasicChartDomain.php)_
      - `domain`: _[ChartData](#chartdata)_
      - `reversed`: _Boolean_
    - `series`: _Array_  
      _[BasicChartSeries](/src/Services/Sheets/Classes/Charts/Basic/BasicChartSeries.php)_
      - `series`: _[ChartData](#chartdata)_
      - `targetAxis`: _**enum**: [BasicChartAxisPosition](/src/Services/Sheets/Enums/Charts/Basic/BasicChartAxisPosition.php)_
      - `type`: _**enum**: [BasicChartType](/src/Services/Sheets/Enums/Charts/Basic/BasicChartType.php)_
      - `lineStyle`: _[LineStyle](/src/Services/Sheets/Classes/Charts/LineStyle.php)_
        - `width`: _Integer_
        - `type`: _**enum**: [LineDashType](/src/Services/Sheets/Enums/Charts/LineDashType.php)_
      - `dataLabel`: _[DataLabel](#datalabel)_
    - `headerCount`: _Integer_
    - `threeDimensional`: _Boolean_
    - `interpolateNulls`: _Boolean_
    - `stackedType`: _**enum**: [BasicChartStackedType](/src/Services/Sheets/Enums/Charts/Basic/BasicChartStackedType.php)_
    - `lineSmoothing`: _Boolean_
    - `compareMode`: _**enum**: [BasicChartCompareMode](/src/Services/Sheets/Enums/Charts/Basic/BasicChartCompareMode.php)_
    - `totalDataLabel`: _[DataLabel](#datalabel)_
  - `pieChart`: _[PieChartSpec](/src/Services/Sheets/Classes/Charts/Pie/PieChartSpec.php)_
    - `legendPosition`: _**enum**: [PieChartLegendPosition](/src/Services/Sheets/Enums/Charts/Pie/PieChartLegendPosition.php)_
    - `domain`: _[ChartData](#chartdata)_
    - `series`: _[ChartData](#chartdata)_
    - `threeDimensional`: _Boolean_
    - `pieHole`: _Float_
  - `bublbeChart`: _[BubbleChartSpec](/src/Services/Sheets/Classes/Charts/Bubble/BubbleChartSpec.php)_
    - `legendPosition`: _**enum**: [BubbleChartLegendPosition](/src/Services/Sheets/Enums/Charts/Bubble/BubbleChartLegendPosition.php)_
    - `bubbleLabels`: _[ChartData](#chartdata)_
    - `domain`: _[ChartData](#chartdata)_
    - `series`: _[ChartData](#chartdata)_
    - `groupIds`: _[ChartData](#chartdata)_
    - `bubleOpacity`: _Float_
    - `bubbleBorderColorStyle`: _[ColorStyle](#colorstyle)_
    - `bubleMaxRadiusSize`: _Integer_
    - `bubbleMinRadiusSize`: _Integer_
    - `bubbleTextStyle`: _[TextFormat](#textformat)_
  - `candlestickChart`: _[CandlestickChartSpec](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickChartSpec.php)_
    - `domain`: _[CandlestickDomain](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickDomain.php)_
      - `data`: _[ChartData](#chartdata)_
      - `reversed`: _Boolean_
    - `data`: _Array_
      _[CandlestickData](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickData.php)_
      - `lowSeries`: _[CandlestickSeries](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickSeries.php)_
        - `data`: _[ChartData](#chartdata)_
      - `openSeries`: _[CandlestickSeries](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickSeries.php)_
        - `data`: _[ChartData](#chartdata)_
    - `closeSeries`: _[CandlestickSeries](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickSeries.php)_
      - `data`: _[ChartData](#chartdata)_
    - `highSeries`: _[CandlestickSeries](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickSeries.php)_
      - `data`: _[ChartData](#chartdata)_
  - `orgChart`: _[OrgChartSpec](/src/Services/Sheets/Classes/Charts/Org/OrgChartSpec.php)_
    - `nodeSize`: _**enum**: [OrgChartNodeSize](/src/Services/Sheets/Enums/Charts/Org/OrgChartNodeSize.php)_
    - `nodeColorStyle`: _[ColorStyle](#colorstyle)_
    - `selectedNodeColorStyle`: _[ColorStyle](#colorstyle)_
    - `labels`: _[ChartData](#chartdata)_
    - `parentLabels`: _[ChartData](#chartdata)_
  - `tooltips`: _[ChartData](#chartdata)_
  - `histogramChart`: _[HistogramChartSpec](/src/Services/Sheets/Classes/Charts/Histogram/HistogramChartSpec.php)_
    - `series`: _Array_
      _[HistogramSeries](/src/Services/Sheets/Classes/Charts/Histogram/HistogramSeries.php)_
      - `barColorStyle`: _[ColorStyle](#colorstyle)_
      - `data`: _[ChartData](#chartdata)_
    - `legendPosition`: _**enum**: HistogramChartLegendPosition_
    - `showItemDividers`: _Boolean_
    - `bucketSize`: _Float_
    - `outlierPercentile`: _Float_
  - `waterfallChart`: _[WaterfallChartSpec](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartSpec.php)_
    - `domain`: _[WaterfallChartDomain](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartDomain.php)_
    - `series`: _Array_
      _[WaterfallChartSeries](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartSeries.php)_
      - `data`: _[ChartData](#chartdata)_
      - `positiveColumnsStyle`: _[WaterfallChartColumnStyle](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartColumnStyle.php)_
      - `negativeColumnsStyle`: _[WaterfallChartColumnStyle](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartColumnStyle.php)_
      - `subtotalColumnsStyle`: _[WaterfallChartColumnStyle](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartColumnStyle.php)_
      - `hideTrailingSubtotal`: _Boolean_
      - `customSubtotals`: _Array_
        _[WaterfallChartCustomSubtotal](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartCustomSubtotal.php)_
        - `subtotalIndex`: _Integer_
        - `label`: _String_
        - `dataIsSubtotal`: _Boolean_
      - `dataLabel`: _[DataLabel](#datalabel)_
  - `treemapChart`: _[TreemapChartSpec](/src/Services/Sheets/Classes/Charts/Treemap/TreemapChartSpec.php)_
    - `labels`: _[ChartData](#chartdata)_
    - `parentLabels`: _[ChartData](#chartdata)_
    - `sizeData`: _[ChartData](#chartdata)_
    - `colorData`: _[ChartData](#chartdata)_
    - `textFormat`: _[TextFormat](#textformat)_
    - `levels`: _Integer_
    - `hintedLevels`: _Integer_
    - `minValue`: _Float_
    - `maxValue`: _Float_
    - `headerColorStyle`: _[ColorStyle](#colorstyle)_
    - `colorScale`: _[TreemapChartColorScale](/src/Services/Sheets/Classes/Charts/Treemap/TreemapChartColorScale.php)_
    - `hideTooltips`: _Boolean_
  - `scorecardChart`: _[ScorecardChartSpec](/src/Services/Sheets/Classes/Charts/Scorecard/ScorecardChartSpec.php)_
    - `keyValueData`: _[ChartData](#chartdata)_
    - `baselineValueData`: _[ChartData](#chartdata)_
    - `aggregateType`: _**enum**: [ChartAggregateType](/src/Services/Sheets/Enums/Charts/ChartAggregateType.php)_
    - `keyValueFormat`: _[KeyValueFormat](/src/Services/Sheets/Classes/Charts/KeyValueFormat.php)_
      - `textFormat`: _[TextFormat](#textformat)_
      - `position`: _[TextPosition](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Other/TextPosition.php)_
        - `horizontalAlignment`: _**enum**: [HorizontalAlignment](/src/Services/Sheets/Enums/Other/HorizontalAlign.php)_
    - `baselineValueFormat`: _[BaselineValueFormat](/src/Services/Sheets/Classes/Charts/BaselineValueFormat.php)_
      - `comparisonType`: _**enum**: [ComparisonType](/src/Services/Sheets/Enums/Charts/ComparisonType.php)_
      - `textFormat`: _[TextFormat](#textformat)_
      - `position`: _[TextPosition](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Other/TextPosition.php)_
        - `horizontalAlignment`: _**enum**: [HorizontalAlignment](/src/Services/Sheets/Enums/Other/HorizontalAlign.php)_
      - `description`: _String_
      - `positiveColorStyle`: _[ColorStyle](#colorstyle)_
      - `negativeColorStyle`: _[ColorStyle](#colorstyle)_
    - `scaleFactor`: _Float_
    - `numberFormatSource`: \_**enum**: [ChartNumberFormatSource](/src/Services/Sheets/Enums/Charts/ChartNumberFormatSource.php)
    - `customFormatOptions`: _[ChartCustomNumberFormatOptions](/src/Services/Sheets/Classes/Charts/ChartCustomNumberFormatOptions.php)_
      - `prefix`: _String_
      - `suffix`: _String_
- `position`: _[EmbeddedObjectPosition](/src/Services/Sheets/Classes/Other/EmbeddedObjectPosition.php)_
  - `sheetId`: _Integer_
  - `overlayPosition`: _[OverlayPosition](/src/Services/Sheets/Classes/Other/OverlayPosition.php)_
    - `anchorCell`: _[GridCoordinate](/src/Services/Sheets/Classes/Other/GridCoordinate.php)_
      - `sheetId`: _Integer_
      - `rowIndex`: _Integer_
      - `columnIndex`: _Integer_
    - `offsetXPixels`: _Integer_
    - `offsetYPixels`: _Integer_
    - `widthPixels`: _Integer_
    - `heightPixels`: _Integer_
  - `newSheet`: _Boolean_
- `border`: _[EmbeddedObjectBorder](/src/Services/Sheets/Classes/Charts/EmbeddedObjectBorder.php)_
  - `colorStyle`: _[ColorStyle](#colorstyle)_

---

#### [ChartData](/src/Services/Sheets/Classes/Charts/ChartData.php)

- `groupRule`: _[ChartGroupRule](/src/Services/Sheets/Classes/Charts/ChartGroupRule.php)_
  - `dateTimeRule`: _[ChartDateTimeRule](/src/Services/Sheets/Classes/Charts/ChartDateTimeRule.php)_
    - `type`: _**enum**: [ChartDateTimeRuleType](/src/Services/Sheets/Classes/Charts/ChartDateTimeRule.php)_
  - `histogramRule`: _[ChartHistogramRule](/src/Services/Sheets/Classes/Charts/ChartHistogramRule.php)_ - _(Histogram Chart only)_
    - `minValue`: _Float_
    - `maxValue`: _Float_
    - `intervalSize`: _Float_
- `aggregateType`: _**enum**: [ChartAggregateType](/src/Services/Sheets/Enums/Charts/ChartAggregateType.php)_
- `sourceRange`: _[ChartSourceRange](/src/Services/Sheets/Classes/Charts/ChartSourceRange.php)_
  - `sources`: _Array_
    _[GridRange](#gridrange)_
- `columnReference`: _[DataSourceColumnReference](/src/Services/Sheets/Classes/Charts/DataSourceColumnReference.php)_
  - `name`: _String_

---

#### [DataLabel](/src/Services/Sheets/Classes/Charts/DataLabel.php)

- `type`: _**enum**: [DataLabelType](/src/Services/Sheets/Enums/Charts/DataLabelType.php)_
- `textFormat`: _[TextFormat](#textformat)_
- `placement`: _**enum**: [DataLabelPlacement](/src/Services/Sheets/Enums/Charts/DataLabelPlacement.php)_
- `customLabelData`: _[ChartData](#chartdata)_
- `colorStyle`: _[ColorStyle](#colorstyle)_ - _(Basic Chart only)_
- `pointStyle`: _[PointStyle](/src/Services/Sheets/Classes/Charts/PointStyle.php)_ - _(Basic Chart only)_
  - `size`: _Float_
  - `shape`: _**enum**: [PointShape](/src/Services/Sheets/Enums/Charts/PointShape.php)_
- `styleOverrides`: _Array_ - _(Basic Chart only)_  
  _[BasicSeriesDataPointStyleOverride](/src/Services/Sheets/Classes/Charts/Basic/BasicSeriesDataPointStyleOverride.php)_
  - `index`: _Integer_
  - `colorStyle`: _[ColorStyle](#colorstyle)_
  - `pointStyle`: _[PointStyle](/src/Services/Sheets/Classes/Charts/PointStyle.php)_
    - `size`: _Float_
    - `shape`: _**enum**: [PointShape](/src/Services/Sheets/Enums/Charts/PointShape.php)_

---

### Others

---

#### [ExtendedValue](/src/Services/Sheets/Other/ExtendedValue.php)

- `stringValue`: _String_
- `numberValue`: _Float_
- `boolValue`: _Boolean_
- `formulaValue`: _String_
- `errorValue`: [_ErrorValue_](/src/Services/Sheets/Other/ErrorValue.php)
  - `type`: _**enum**: [ErrorType](/src/Services/Sheets/Enums/Other/ErrorType.php)_
  - `message`: _String_

---

#### [Border](/src/Services/Sheets/Classes/Other/Border.php)

- `style`: _**enum**: [Style](/src/Services/Sheets/Enums/Cells/Style.php)_
- `width`: _Integer_
- `colorStyle`: [_ColorStyle_](#colorstyle)

---

#### [TextFormat](/src/Services/Sheets/Classes/Other/TextFormat.php)

- `foregroundColorStyle`: _[ColorStyle](#colorstyle)_
- `fontFamily`: _String_
- `fontSize`: _Integer_
- `bold`: _Boolean_
- `italic`: _Boolean_
- `strikethrough`: _Boolean_
- `underline`: _Boolean_
- `link`: _[Link](/src/Services/Sheets/Classes/Other/Link.php)_
  - `uri`: _String_

---

#### [ColorStyle](/src/Services/Sheets/Classes/Other/ColorStyle.php)

- `rgbColor`: _[Color](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Other/Color.php)_
  - `red`: _Float_
  - `green`: _Float_
  - `blue`: _Float_
  - `alpha`: _Float_
- `themeColor`: _**enum**: [ThemeColorType](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Enums/Other/ThemeColorType.php)_

---

#### [GridRange](/src/Services/Sheets/Classes/Other/GridRange.php)

- `sheetId`: _Integer_
- `startRowIndex`: _Integer_
- `endRowIndex`: _Integer_
- `startColumnIndex`: _Integer_
- `endColumnIndex`: _Integer_

---

## Enums

- [InsertDataOption](/src/Services/Sheets/Enums/Cells/InsertDataOption.php)
