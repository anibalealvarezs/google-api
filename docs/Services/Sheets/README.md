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
    - [Sheets](#sheets)
    - [Cells](#cells)
    - [Charts](#charts)
  - [Objects](#objects)
    - [Sheets](#sheets-1)
      - [addSheet](#addsheet)
    - [Cells](#cells-1)
      - [updateCells](#updatecells)
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

_______________________________________________________________________________________________

## Raw requests

The following examples show the simplest requests possible depending on the action you want to perform.

_______________________________________________________________________________________________

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

_______________________________________________________________________________________________

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

_______________________________________________________________________________________________

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

_______________________________________________________________________________________________

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

_______________________________________________________________________________________________

## Request methods

The following are non-static methods that can be called from instances (client instances) of the [SheetsApi](/src/Services/Sheets/SheetsApi.php) class.

_______________________________________________________________________________________________

### Spreadsheets

- ### getSpreadsheetData: *Array*

  `Gets the spreadsheet data.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `spreadsheetId`: *Integer*  
      ID of the Spreadsheet to get data from.
  </details><br>

_______________________________________________________________________________________________

### Sheets

- ### clearSheet(): *Array*

  `Clears the sheet.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `spreadsheetId`: *String*  
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

- ### copySheet(): *Array*

  `Copies a sheet from one spreadsheet to another (or duplicates it in the same spreadsheet).`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `sourceSpreadsheetId`: *String*  
      ID of the Spreadsheet to copy the sheet from.

      - `sheetId`: *Integer*  
      ID of the sheet to be copied.

  - Optional

      - `destinySpreadsheetId`: *Integer | null*  
      ID of the Spreadsheet to copy the sheet to. If not specified, the sheet will be copied to the same spreadsheet.  
      Defaults to `null`.
  </details><br>

- ### createSheet(): *Array*

  `Creates a new sheet in the spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `spreadsheetId`: *String*  
      ID of the Spreadsheet to add the chart to.

      - `index`: *Integer*  
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

- ### createSheetAtStart(): *Array*

  `Creates a new sheet at the start of the spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `spreadsheetId`: *String*  
      ID of the Spreadsheet to add the chart to.

  - Optional

      - `title`: *String*,  
      The title of the chart.  
      Defaults to `"New Chart"`.

      - `sheetId`: *Integer | null*  
      A custom ID to identify the sheet. If not specified, an ID will be randomly generated.  
      Defaults to `null`.
  </details><br>

- ### createSheetAtTheEnd(): *Array*

  `Creates a new sheet at the end of the spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `spreadsheetId`: *String*  
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

- ### deleteSheet(): *Array*

  `Deletes a sheet from the spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `spreadsheetId`: *String*  
      ID of the Spreadsheet to add the chart to.

      - `sheetId`: *Integer*  
      ID of the sheet to delete.

  - Optional

      - `spreadsheetData`: *Array | null*  
      The spreadsheet data, which can be obtained through the [getSpreadsheetData()](/docs/Services/Sheets/Requests/Spreadsheets/getSpreadsheetData.md) method. Submitting this data will save a request to the API.
      If this parameter is not specified, the data will be fetched from the spreadsheet when needed.  
      Defaults to `null`.
  </details><br>

_______________________________________________________________________________________________

### Cells

- ### clearCells(): *Array*

  `Clears a range of cells.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `spreadsheetId`: *String*  
      ID of the spreadsheet where the sheet is located.

      - `sheetId`: *Integer*  
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

- ### readCells(): *Array*

  `Reads a range of cells.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `spreadsheetId`: *String*  
      ID of the spreadsheet where the sheet is located.

      - `sheetId`: *Integer*  
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

- ### updateCells(): *Array*

  `Updates a range of cells.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `spreadsheetId`: *String*  
      ID of the spreadsheet where the sheet is located.

      - `sheetId`: *Integer*  
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

- ### writeCells(): *Array*

  `Updates a range of cells.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `spreadsheetId`: *String*  
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
  </details><br>

_______________________________________________________________________________________________

### Charts

- ### addChart(): *Array*

  `Adds a chart to a spreadsheet.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `spreadsheetId`: *Integer*  
      ID of the Spreadsheet to add the chart to

      - `chartId`: *Integer*  
      Custom ID to identify the chart

      - `chartData`: *Array*  
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

_______________________________________________________________________________________________

## Objects

_______________________________________________________________________________________________

### Sheets

_______________________________________________________________________________________________

#### addSheet

  - `properties`: [*SheetProperties*](/src/Services/Sheets/Classes/Sheets/SheetProperties.php)
    - `sheetId`: *Integer*
    - `title`: *String*
    - `index`: *Integer*
    - `sheetType`: [*SheetType*](/src/Services/Sheets/Enums/Sheets/SheetType.php)
    - `gridProperties`: [*GridProperties*](/src/Services/Sheets/Classes/Sheets/GridProperties.php)
      - `rowCount`: *Integer*
      - `columnCount`: *Integer*
      - `frozenRowCount`: *Integer*
      - `frozenColumnCount`: *Integer*
      - `hideGridlines`: *Boolean*
      - `rowGroupControlAfter`: *Boolean*
      - `columnGroupControlAfter`: *Boolean*
    - `hidden`: *Boolean*
    - `tabColorStyle`: [*ColorStyle*](#colorstyle)
    - `rightToLeft`: *Boolean*
    - `dataSourceSheetProperties`: [*DataSourceSheetProperties*](../../../src/Services/Sheets/Classes/Sheets/DataSourceSheetProperties.php)
      - `dataSourceId`: *String*
      - `columns`: *Array*  
      *DataSourceColumn*  
        - `reference`: [*DataSourceColumnReference*](/src/Services/Sheets/Classes/Sheets/DataSourceColumnReference.php)
          - `columnIndex`: *Integer*
          - `sheetId`: *Integer*
        - `formula`: *String*
      - `dataExecutionStatus`: *[DataExecutionStatus](/src/Services/Sheets/Classes/Other/DataExecutionStatus.php)*
        - `state`: _**enum**: [DataExecutionState](/src/Services/Sheets/Enums/Other/DataExecutionState.php)_
        - `errorCode`: _**enum**: [DataExecutionErrorCode](/src/Services/Sheets/Enums/Other/DataExecutionErrorCode.php)_
        - `errorMessage`: *String*
        - `lastRefreshTime`: *String*

_______________________________________________________________________________________________

### Cells

_______________________________________________________________________________________________

#### updateCells

  - `rows`: *Array*  
  [*RowData*](/src/Services/Sheets/Classes/Sheets/RowData.php)
    - `values`: *Array*
    [*CellData*](/src/Services/Sheets/Classes/Cells/CellData.php)
      - `userEnteredValue`: [*ExtendedValue*](#extendedvalue)
      - `effectiveValue`: [*ExtendedValue*](#extendedvalue)
      - `formattedValue`: *String*
      - `userEnteredFormat`: [*CellFormat*](/src/Services/Sheets/Classes/Cells/CellFormat.php)
        - `numberFormat`: [*NumberFormat*](/src/Services/Sheets/Classes/Cells/NumberFormat.php)
          - `type`: _**enum**: [NumberFormatType](/src/Services/Sheets/Enums/Cells/NumberFormatType.php)_
          - `pattern`: *String*
        - `backgroundColorStyle`: [*ColorStyle*](#colorstyle)
        - `borders`: [*Borders*](/src/Services/Sheets/Classes/Cells/Borders.php)
          - `top`: [*Border*](#border)
          - `bottom`: [*Border*](#border)
          - `left`: [*Border*](#border)
          - `right`: [*Border*](#border)
        - `padding`: [*Padding*](/src/Services/Sheets/Classes/Cells/Padding.php)
          - `top`: *Integer*
          - `right`: *Integer*
          - `bottom`: *Integer*
          - `left`: *Integer*
        - `horizontalAlignment`: _**enum**: [HorizontalAlign](/src/Services/Sheets/Enums/Other/HorizontalAlign.php)_
        - `verticalAlignment`: _**enum**: [VerticalAlign](/src/Services/Sheets/Enums/Other/VerticalAlign.php)_
        - `wrapStrategy`: _**enum**: [WrapStrategy](/src/Services/Sheets/Enums/Cells/WrapStrategy.php)_
        - `textDirection`: _**enum**: [TextDirection](/src/Services/Sheets/Enums/Cells/TextDirection.php)_
        - `textFormat`: *[TextFormat](#textformat)*
        - `hyperlinkDisplayType`: _**enum**: [HyperlinkDisplayType](/src/Services/Sheets/Enums/Cells/HyperlinkDisplayType.php)_
        - `textRotation`: [*TextRotation*](/src/Services/Sheets/Classes/Cells/TextRotation.php)
          - `angle`: *Integer*
          - `vertical`: *Boolean*

_______________________________________________________________________________________________

### Charts

_______________________________________________________________________________________________

#### [EmbeddedChart](../../../src/Services/Sheets/Classes/Spreadsheets/EmbeddedChart.php)

  - `chartId`: *Integer*
  - `spec`: *[ChartSpec](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Spreadsheets/EmbeddedChart.php)*
    - `title`: *String*
    - `altText`: *String*
    - `titleTextFormat`: *[TextFormat](#textformat)*
    - `subtitle`: *String*
    - `subtitleTextFormat`: *[TextFormat](#textformat)*
    - `subtitlePosition`: *[TextPosition](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Other/TextPosition.php)*
      - `horizontalAlignment`: _**enum**: [HorizontalAlign](/src/Services/Sheets/Enums/Other/HorizontalAlign.php)_
    - `fontName`: *String*
    - `maximized`: *Boolean*
    - `backgroundColorStyle`: *[ColorStyle](#colorstyle)*
    - `dataSourceChartProperties`: *[DataSourceChartProperties](/src/Services/Sheets/Classes/Charts/DataSourceChartProperties.php)*
      - `dataSourceId`: *String*
      - `dataExecutionStatus`: *[DataExecutionStatus](/src/Services/Sheets/Classes/Other/DataExecutionStatus.php)*
        - `state`: _**enum**: [DataExecutionState](/src/Services/Sheets/Enums/Other/DataExecutionState.php)_
        - `errorCode`: _**enum**: [DataExecutionErrorCode](/src/Services/Sheets/Enums/Other/DataExecutionErrorCode.php)_
        - `errorMessage`: *String*
        - `lastRefreshTime`: *String*
    - `filterSpecs`: *Array*  
      *[FilterSpec](/src/Services/Sheets/Classes/Other/FilterSpec.php)*  
      - `filterCriteria`: *[FilterCriteria](/src/Services/Sheets/Classes/Other/FilterCriteria.php)*
        - `hiddenValues`: *Array*
            *String*
        - `condition`: *[BooleanCondition](/src/Services/Sheets/Classes/Other/BooleanCondition.php)*
        - `visibleBackgroundColorStyle`: *[ColorStyle](#colorstyle)*
        - `visibleForegroundColorStyle`: *[ColorStyle](#colorstyle)*
      - `columnIndex`: *Integer*
      - `dataSourceColumnReference`: *[DataSourceColumnReference](/src/Services/Sheets/Classes/Charts/DataSourceColumnReference.php)*  
        - `name`: *String*
    - `sortSpecs`: *Array*  
      *[SortSpec](/src/Services/Sheets/Classes/Other/SortSpec.php)*
      - `sortOrder`: _**enum**: [SortOrder](/src/Services/Sheets/Enums/Other/SortOrder.php)_
      - `foregroundColorStyle`: *[ColorStyle](#colorstyle)*
      - `backgroundColorStyle`: *[ColorStyle](#colorstyle)*
      - `dimensionIndex`: *Integer*
      - `dataSourceColumnReference`: *[DataSourceColumnReference](/src/Services/Sheets/Classes/Charts/DataSourceColumnReference.php)*
        - `name`: *String*
    - `hiddenDimensionStrategy`: _**enum**: [ChartHiddenDimensionStrategy](/src/Services/Sheets/Enums/Charts/ChartHiddenDimensionStrategy.php)_
    - `basicChart`: *[BasicChartSpec](/src/Services/Sheets/Classes/Charts/Basic/BasicChartSpec.php)*
      - `chartType`: _**enum**: [BasicChartType](/src/Services/Sheets/Enums/Charts/Basic/BasicChartType.php)_
      - `legendPosition`: _**enum**: [BasicChartLegendPosition](/src/Services/Sheets/Enums/Charts/Basic/BasicChartLegendPosition.php)_
      - `axis`: *Array*
        *[BasicChartAxis](/src/Services/Sheets/Classes/Charts/Basic/BasicChartAxis.php)*  
        - `position`:  _**enum**: BasicChartAxisPosition_
        - `title`: *String*
        - `format`: *[TextFormat](#textformat)*
        - `titleTextPosition`: *[TextPosition](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Other/TextPosition.php)*
          - `horizontalAlignment`: _**enum**: [HorizontalAlign](/src/Services/Sheets/Enums/Other/HorizontalAlign.php)_
        - `viewWindowOptions`: *[ChartAxisViewWindowOptions](/src/Services/Sheets/Classes/Charts/ChartAxisViewWindowOptions.php)*
          - `viewWindowMin`: *Float*
          - `viewWindowMax`: *Float*
          - `viewWindowMode`: _**enum**: [ViewWindowMode](/src/Services/Sheets/Enums/Charts/ViewWindowMode.php)_
      - `domains`: *Array*
        *[BasicChartDomain](/src/Services/Sheets/Classes/Charts/Basic/BasicChartDomain.php)*  
        - `domain`: *[ChartData](#chartdata)*
        - `reversed`: *Boolean*
      - `series`: *Array*  
        *[BasicChartSeries](/src/Services/Sheets/Classes/Charts/Basic/BasicChartSeries.php)*  
        - `series`: *[ChartData](#chartdata)*
        - `targetAxis`: _**enum**: [BasicChartAxisPosition](/src/Services/Sheets/Enums/Charts/Basic/BasicChartAxisPosition.php)_
        - `type`: _**enum**: [BasicChartType](/src/Services/Sheets/Enums/Charts/Basic/BasicChartType.php)_
        - `lineStyle`: *[LineStyle](/src/Services/Sheets/Classes/Charts/LineStyle.php)*
          - `width`: *Integer*
          - `type`: _**enum**: [LineDashType](/src/Services/Sheets/Enums/Charts/LineDashType.php)_
        - `dataLabel`: *[DataLabel](#datalabel)*
      - `headerCount`: *Integer*
      - `threeDimensional`: *Boolean*
      - `interpolateNulls`: *Boolean*
      - `stackedType`: _**enum**: [BasicChartStackedType](/src/Services/Sheets/Enums/Charts/Basic/BasicChartStackedType.php)_
      - `lineSmoothing`: *Boolean*
      - `compareMode`: _**enum**: [BasicChartCompareMode](/src/Services/Sheets/Enums/Charts/Basic/BasicChartCompareMode.php)_
      - `totalDataLabel`: *[DataLabel](#datalabel)*
    - `pieChart`: *[PieChartSpec](/src/Services/Sheets/Classes/Charts/Pie/PieChartSpec.php)*
      - `legendPosition`: _**enum**: [PieChartLegendPosition](/src/Services/Sheets/Enums/Charts/Pie/PieChartLegendPosition.php)_
      - `domain`: *[ChartData](#chartdata)*
      - `series`: *[ChartData](#chartdata)*
      - `threeDimensional`: *Boolean*
      - `pieHole`: *Float*
    - `bublbeChart`: *[BubbleChartSpec](/src/Services/Sheets/Classes/Charts/Bubble/BubbleChartSpec.php)*
      - `legendPosition`: _**enum**: [BubbleChartLegendPosition](/src/Services/Sheets/Enums/Charts/Bubble/BubbleChartLegendPosition.php)_
      - `bubbleLabels`: *[ChartData](#chartdata)*
      - `domain`: *[ChartData](#chartdata)*
      - `series`: *[ChartData](#chartdata)*
      - `groupIds`: *[ChartData](#chartdata)*
      - `bubleOpacity`: *Float*
      - `bubbleBorderColorStyle`: *[ColorStyle](#colorstyle)*
      - `bubleMaxRadiusSize`: *Integer*
      - `bubbleMinRadiusSize`: *Integer*
      - `bubbleTextStyle`: *[TextFormat](#textformat)*
    - `candlestickChart`: *[CandlestickChartSpec](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickChartSpec.php)*
      - `domain`: *[CandlestickDomain](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickDomain.php)*
        - `data`: *[ChartData](#chartdata)*
        - `reversed`: *Boolean*
      - `data`: *Array*
        *[CandlestickData](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickData.php)*  
        - `lowSeries`: *[CandlestickSeries](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickSeries.php)*
          - `data`: *[ChartData](#chartdata)*
        - `openSeries`: *[CandlestickSeries](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickSeries.php)*
          - `data`: *[ChartData](#chartdata)*
      - `closeSeries`: *[CandlestickSeries](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickSeries.php)*
        - `data`: *[ChartData](#chartdata)*
      - `highSeries`: *[CandlestickSeries](/src/Services/Sheets/Classes/Charts/Candlestick/CandlestickSeries.php)*
        - `data`: *[ChartData](#chartdata)*
    - `orgChart`: *[OrgChartSpec](/src/Services/Sheets/Classes/Charts/Org/OrgChartSpec.php)*
      - `nodeSize`: _**enum**: [OrgChartNodeSize](/src/Services/Sheets/Enums/Charts/Org/OrgChartNodeSize.php)_
      - `nodeColorStyle`: *[ColorStyle](#colorstyle)*
      - `selectedNodeColorStyle`: *[ColorStyle](#colorstyle)*
      - `labels`: *[ChartData](#chartdata)*
      - `parentLabels`: *[ChartData](#chartdata)*
    - `tooltips`: *[ChartData](#chartdata)*
    - `histogramChart`: *[HistogramChartSpec](/src/Services/Sheets/Classes/Charts/Histogram/HistogramChartSpec.php)*
      - `series`: *Array*
        *[HistogramSeries](/src/Services/Sheets/Classes/Charts/Histogram/HistogramSeries.php)*  
        - `barColorStyle`: *[ColorStyle](#colorstyle)*
        - `data`: *[ChartData](#chartdata)*
      - `legendPosition`: _**enum**: HistogramChartLegendPosition_
      - `showItemDividers`: *Boolean*
      - `bucketSize`: *Float*
      - `outlierPercentile`: *Float*
    - `waterfallChart`: *[WaterfallChartSpec](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartSpec.php)*
      - `domain`: *[WaterfallChartDomain](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartDomain.php)*
      - `series`: *Array*
        *[WaterfallChartSeries](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartSeries.php)*  
        - `data`: *[ChartData](#chartdata)*
        - `positiveColumnsStyle`: *[WaterfallChartColumnStyle](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartColumnStyle.php)*
        - `negativeColumnsStyle`: *[WaterfallChartColumnStyle](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartColumnStyle.php)*
        - `subtotalColumnsStyle`: *[WaterfallChartColumnStyle](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartColumnStyle.php)*
        - `hideTrailingSubtotal`: *Boolean*
        - `customSubtotals`: *Array*
          *[WaterfallChartCustomSubtotal](/src/Services/Sheets/Classes/Charts/Waterfall/WaterfallChartCustomSubtotal.php)*  
          - `subtotalIndex`: *Integer*
          - `label`: *String*
          - `dataIsSubtotal`: *Boolean*
        - `dataLabel`: *[DataLabel](#datalabel)*
    - `treemapChart`: *[TreemapChartSpec](/src/Services/Sheets/Classes/Charts/Treemap/TreemapChartSpec.php)*
      - `labels`: *[ChartData](#chartdata)*
      - `parentLabels`: *[ChartData](#chartdata)*
      - `sizeData`: *[ChartData](#chartdata)*
      - `colorData`: *[ChartData](#chartdata)*
      - `textFormat`: *[TextFormat](#textformat)*
      - `levels`: *Integer*
      - `hintedLevels`: *Integer*
      - `minValue`: *Float*
      - `maxValue`: *Float*
      - `headerColorStyle`: *[ColorStyle](#colorstyle)*
      - `colorScale`: *[TreemapChartColorScale](/src/Services/Sheets/Classes/Charts/Treemap/TreemapChartColorScale.php)*
      - `hideTooltips`: *Boolean*
    - `scorecardChart`: *[ScorecardChartSpec](/src/Services/Sheets/Classes/Charts/Scorecard/ScorecardChartSpec.php)*
      - `keyValueData`: *[ChartData](#chartdata)*
      - `baselineValueData`: *[ChartData](#chartdata)*
      - `aggregateType`: _**enum**: [ChartAggregateType](/src/Services/Sheets/Enums/Charts/ChartAggregateType.php)_
      - `keyValueFormat`: *[KeyValueFormat](/src/Services/Sheets/Classes/Charts/KeyValueFormat.php)*
        - `textFormat`: *[TextFormat](#textformat)*
        - `position`: *[TextPosition](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Other/TextPosition.php)*
          - `horizontalAlignment`: _**enum**: [HorizontalAlignment](/src/Services/Sheets/Enums/Other/HorizontalAlign.php)_
      - `baselineValueFormat`: *[BaselineValueFormat](/src/Services/Sheets/Classes/Charts/BaselineValueFormat.php)*
        - `comparisonType`: _**enum**: [ComparisonType](/src/Services/Sheets/Enums/Charts/ComparisonType.php)_
        - `textFormat`: *[TextFormat](#textformat)*
        - `position`: *[TextPosition](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Other/TextPosition.php)*
          - `horizontalAlignment`: _**enum**: [HorizontalAlignment](/src/Services/Sheets/Enums/Other/HorizontalAlign.php)_
        - `description`: *String*
        - `positiveColorStyle`: *[ColorStyle](#colorstyle)*
        - `negativeColorStyle`: *[ColorStyle](#colorstyle)*
      - `scaleFactor`: *Float*
      - `numberFormatSource`: _**enum**: [ChartNumberFormatSource](/src/Services/Sheets/Enums/Charts/ChartNumberFormatSource.php)
      - `customFormatOptions`: *[ChartCustomNumberFormatOptions](/src/Services/Sheets/Classes/Charts/ChartCustomNumberFormatOptions.php)*
        - `prefix`: *String*
        - `suffix`: *String*
  - `position`: *[EmbeddedObjectPosition](/src/Services/Sheets/Classes/Other/EmbeddedObjectPosition.php)*
    - `sheetId`: *Integer*
    - `overlayPosition`: *[OverlayPosition](/src/Services/Sheets/Classes/Other/OverlayPosition.php)*
      - `anchorCell`: *[GridCoordinate](/src/Services/Sheets/Classes/Other/GridCoordinate.php)*
        - `sheetId`: *Integer*
        - `rowIndex`: *Integer*
        - `columnIndex`: *Integer*
      - `offsetXPixels`: *Integer*
      - `offsetYPixels`: *Integer*
      - `widthPixels`: *Integer*
      - `heightPixels`: *Integer*
    - `newSheet`: *Boolean*
  - `border`: *[EmbeddedObjectBorder](/src/Services/Sheets/Classes/Charts/EmbeddedObjectBorder.php)*
    - `colorStyle`: *[ColorStyle](#colorstyle)*

_______________________________________________________________________________________________

#### [ChartData](/src/Services/Sheets/Classes/Charts/ChartData.php)

  - `groupRule`: *[ChartGroupRule](/src/Services/Sheets/Classes/Charts/ChartGroupRule.php)*
    - `dateTimeRule`: *[ChartDateTimeRule](/src/Services/Sheets/Classes/Charts/ChartDateTimeRule.php)*
      - `type`: _**enum**: [ChartDateTimeRuleType](/src/Services/Sheets/Classes/Charts/ChartDateTimeRule.php)_
    - `histogramRule`: *[ChartHistogramRule](/src/Services/Sheets/Classes/Charts/ChartHistogramRule.php)* - _(Histogram Chart only)_
      - `minValue`: *Float*
      - `maxValue`: *Float*
      - `intervalSize`: *Float*
  - `aggregateType`: _**enum**: [ChartAggregateType](/src/Services/Sheets/Enums/Charts/ChartAggregateType.php)_
  - `sourceRange`: *[ChartSourceRange](/src/Services/Sheets/Classes/Charts/ChartSourceRange.php)*
    - `sources`: *Array*
      *[GridRange](#gridrange)*
  - `columnReference`: *[DataSourceColumnReference](/src/Services/Sheets/Classes/Charts/DataSourceColumnReference.php)*
    - `name`: *String*

_______________________________________________________________________________________________

#### [DataLabel](/src/Services/Sheets/Classes/Charts/DataLabel.php)

  - `type`: _**enum**: [DataLabelType](/src/Services/Sheets/Enums/Charts/DataLabelType.php)_
  - `textFormat`: *[TextFormat](#textformat)*
  - `placement`: _**enum**: [DataLabelPlacement](/src/Services/Sheets/Enums/Charts/DataLabelPlacement.php)_
  - `customLabelData`: *[ChartData](#chartdata)*
  - `colorStyle`: *[ColorStyle](#colorstyle)* - _(Basic Chart only)_
  - `pointStyle`: *[PointStyle](/src/Services/Sheets/Classes/Charts/PointStyle.php)* - _(Basic Chart only)_
    - `size`: *Float*
    - `shape`: _**enum**: [PointShape](/src/Services/Sheets/Enums/Charts/PointShape.php)_
  - `styleOverrides`: *Array* - _(Basic Chart only)_  
    *[BasicSeriesDataPointStyleOverride](/src/Services/Sheets/Classes/Charts/Basic/BasicSeriesDataPointStyleOverride.php)*  
    - `index`: *Integer*
    - `colorStyle`: *[ColorStyle](#colorstyle)*
    - `pointStyle`: *[PointStyle](/src/Services/Sheets/Classes/Charts/PointStyle.php)*
      - `size`: *Float*
      - `shape`: _**enum**: [PointShape](/src/Services/Sheets/Enums/Charts/PointShape.php)_

_______________________________________________________________________________________________

### Others

_______________________________________________________________________________________________

#### [ExtendedValue](/src/Services/Sheets/Other/ExtendedValue.php)
        
  - `stringValue`: *String*
  - `numberValue`: *Float*
  - `boolValue`: *Boolean*
  - `formulaValue`: *String*
  - `errorValue`: [*ErrorValue*](/src/Services/Sheets/Other/ErrorValue.php)
    - `type`: _**enum**: [ErrorType](/src/Services/Sheets/Enums/Other/ErrorType.php)_
    - `message`: *String*

_______________________________________________________________________________________________

#### [Border](/src/Services/Sheets/Classes/Other/Border.php)

  - `style`: _**enum**: [Style](/src/Services/Sheets/Enums/Cells/Style.php)_
  - `width`: *Integer*
  - `colorStyle`: [*ColorStyle*](#colorstyle)

_______________________________________________________________________________________________

#### [TextFormat](/src/Services/Sheets/Classes/Other/TextFormat.php)

  - `foregroundColorStyle`: *[ColorStyle](#colorstyle)*
  - `fontFamily`: *String*
  - `fontSize`: *Integer*
  - `bold`: *Boolean*
  - `italic`: *Boolean*
  - `strikethrough`: *Boolean*
  - `underline`: *Boolean*
  - `link`: *[Link](/src/Services/Sheets/Classes/Other/Link.php)*
    - `uri`: *String*

_______________________________________________________________________________________________

#### [ColorStyle](/src/Services/Sheets/Classes/Other/ColorStyle.php)

  - `rgbColor`: *[Color](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Classes/Other/Color.php)*
    - `red`: *Float*
    - `green`: *Float*
    - `blue`: *Float*
    - `alpha`: *Float*
  - `themeColor`: _**enum**: [ThemeColorType](../../src/Services/Sheets/Classes/Spreadsheets/../../..//src/Services/Sheets/Enums/Other/ThemeColorType.php)_

_______________________________________________________________________________________________

#### [GridRange](/src/Services/Sheets/Classes/Other/GridRange.php)

  - `sheetId`: *Integer*
  - `startRowIndex`: *Integer*
  - `endRowIndex`: *Integer*
  - `startColumnIndex`: *Integer*
  - `endColumnIndex`: *Integer*

_______________________________________________________________________________________________

## Enums
