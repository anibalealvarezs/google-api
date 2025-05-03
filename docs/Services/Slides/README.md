# Google Slides

## Index

- [Google Slides](#google-slides)
  - [Index](#index)
  - [Testing](TESTS.md)
  - [Raw Requests](#raw-requests)
    - [Raw: CreateImage request](#raw-createimage-request)
      - [Enums included](#enums-included)
    - [Raw: CreateLine request](#raw-createline-request)
      - [Enums included](#enums-included-1)
    - [Raw: CreateParagraphBullets request](#raw-createparagraphbullets-request)
      - [Enums included](#enums-included-2)
    - [Raw: CreateShape request](#raw-createshape-request)
      - [Enums included](#enums-included-3)
    - [Raw: CreateSheetsChart request](#raw-createsheetschart-request)
      - [Enums included](#enums-included-4)
    - [Raw: CreateSlide request](#raw-createslide-request)
      - [Enums included](#enums-included-5)
    - [Raw: CreateTable request](#raw-createtable-request)
      - [Enums included](#enums-included-6)
    - [Raw: CreateVideo request](#raw-createvideo-request)
      - [Enums included](#enums-included-7)
    - [Raw: DeleteObject request](#raw-deleteobject-request)
    - [Raw: DeleteParagraphBullets request](#raw-deleteparagraphbullets-request)
      - [Enums included](#enums-included-8)
    - [Raw: DeleteTableColumn request](#raw-deletetablecolumn-request)
    - [Raw: DeleteTableRow request](#raw-deletetablerow-request)
    - [Raw: DeleteText request](#raw-deletetext-request)
      - [Enums included](#enums-included-9)
    - [Raw: InsertTableColumns request](#raw-inserttablecolumns-request)
    - [Raw: InsertTableRows request](#raw-inserttablerows-request)
    - [Raw: InsertText request](#raw-inserttext-request)
    - [Raw: UpdateImageProperties request](#raw-updateimageproperties-request)
      - [Enums included](#enums-included-10)
    - [Raw: UpdateShapeProperties request](#raw-updateshapeproperties-request)
      - [Enums included](#enums-included-11)
    - [Raw: UpdateTableCellProperties request](#raw-updatetablecellproperties-request)
      - [Enums included](#enums-included-12)
    - [Raw: UpdateTableColumnProperties request](#raw-updatetablecolumnproperties-request)
      - [Enums included](#enums-included-13)
    - [Raw: UpdateTableRowProperties request](#raw-updatetablerowproperties-request)
      - [Enums included](#enums-included-14)
    - [Raw: UpdateVideoProperties request](#raw-updatevideoproperties-request)
      - [Enums included](#enums-included-15)
  - [Request methods](#request-methods)
    - [Presentation](#presentation)
    - [Slides](#slides)

___

## Raw Requests

### Raw: CreateImage request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id", /* Page object ID */
    },
    "url": "https://url.to.the.image.jpg"
}
```

Full Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id", /* Page object ID */
        "size": [Optional] {
            "width": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            },
            "height": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            }
        },
        "transform": [Optional] {
            "scaleX": 0.123456,
            "scaleY": 0.123456,
            "shearX": [Optional] 0.123456,
            "shearY": [Optional] 0.123456,
            "translateX": [Optional] 0.123456,
            "translateY": [Optional] 0.123456,
            "unit": [Optional] "EMU"
        }
    },
    "url": "https://url.to.the.image.jpg",
    "objectId": [Optional] "custom_object_id" /* Custom ID for the object to be created */
}
```

#### Enums included

- [Unit](/src/Services/Slides/Enums/Unit.php)

</details>

___

### Raw: CreateLine request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id", /* Page object ID */
    }
}
```

Full Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id", /* Page object ID */
        "size": [Optional] {
            "width": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            },
            "height": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            }
        },
        "transform": [Optional] {
            "scaleX": 0.123456,
            "scaleY": 0.123456,
            "shearX": [Optional] 0.123456,
            "shearY": [Optional] 0.123456,
            "translateX": [Optional] 0.123456,
            "translateY": [Optional] 0.123456,
            "unit": [Optional] "EMU"
        }
    },
    "category": [Optional] "STRAIGHT",
    "objectId": [Optional] "custom_object_id" /* Custom ID for the object to be created */
}
```

#### Enums included

- [Unit](/src/Services/Slides/Enums/Unit.php)
- [LineCategory](/src/Services/Slides/Enums/LineCategory.php)

</details>

___

### Raw: CreateParagraphBullets request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "objectId": "object_id" /* Table cell or shape ID to insert the object into */
}
```

Full Object

```json
{
    "objectId": "object_id", /* Table cell or shape ID to insert the object into */
    "textRange": [Optional] {
        "startIndex": [Optional] 1,
        "endIndex": [Optional] 1,
        "type": [Optional] "ALL"
    },
    "cellLocation": [Optional] {
        "rowIndex": 1,
        "columnIndex": 1
    },
    "bulletPreset": [Optional] "BULLET_DISC_CIRCLE_SQUARE"
}
```

#### Enums included

- [RangeType](/src/Services/Slides/Enums/RangeType.php)
- [BulletGlyphPreset](/src/Services/Slides/Enums/BulletGlyphPreset.php)

</details>

___

### Raw: CreateShape request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id", /* Page object ID */
    }
}
```

Full Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id", /* Page object ID */
        "size": [Optional] {
            "width": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            },
            "height": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            }
        },
        "transform": [Optional] {
            "scaleX": 0.123456,
            "scaleY": 0.123456,
            "shearX": [Optional] 0.123456,
            "shearY": [Optional] 0.123456,
            "translateX": [Optional] 0.123456,
            "translateY": [Optional] 0.123456,
            "unit": [Optional] "EMU"
        }
    },
    "shapeType": [Optional] "RECTANGLE",
    "objectId": [Optional] "custom_object_id" /* Custom ID for the object to be created */
}
```

#### Enums included

- [Unit](/src/Services/Slides/Enums/Unit.php)
- [ShapeType](/src/Services/Slides/Enums/ShapeType.php)

</details>

___

### Raw: CreateSheetsChart request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id", /* Page object ID */
    },
    "spreadsheetId": "spreadsheet_id",
    "chartId": "chart_id"
}
```

Full Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id", /* Page object ID */
        "size": [Optional] {
            "width": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            },
            "height": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            }
        },
        "transform": [Optional] {
            "scaleX": 0.123456,
            "scaleY": 0.123456,
            "shearX": [Optional] 0.123456,
            "shearY": [Optional] 0.123456,
            "translateX": [Optional] 0.123456,
            "translateY": [Optional] 0.123456,
            "unit": [Optional] "EMU"
        }
    },
    "spreadsheetId": "spreadsheet_id",
    "chartId": "chart_id",
    "linkingMode": [Optional] "LINKED",
    "objectId": [Optional] "custom_object_id" /* Custom ID for the object to be created */
}
```

#### Enums included

- [Unit](/src/Services/Slides/Enums/Unit.php)
- [LinkingMode](/src/Services/Slides/Enums/LinkingMode.php)

</details>

___

### Raw: CreateSlide request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```markdown
**Note**: No object is required for creating a new slide
```

Full Object

```json
{
    "index": [Optional] 1,
    "slideLayoutReference": [Optional] {
        "layoutId": [Optional] "layout_id", /* ID of the layout to be taken as reference */
        "predefinedLayout": [Optional] "BLANK"
    },
    "placeholderIdMappings": [Optional] {
        "layoutPlaceholder": {
            "index": 1,
            "parentObjectId": [Optional] "parent_object_id", /* ID of the parent object */
            "type": [Optional] "BODY"
        },
        "layoutPlaceholderObjectId": [Optional] "layout_placeholder_id", /* The object ID of the placeholder on a layout that will be applied to a slide. */
        "objectId": [Optional] "custom_object_id" /* Custom ID for the object to be created */
    },
    "objectId": [Optional] "custom_slide_id" /* Custom ID for the slide to be created */
}
```

#### Enums included

- [PredefinedLayout](/src/Services/Slides/Enums/PredefinedLayout.php)
- [PlaceholderType](/src/Services/Slides/Enums/PlaceholderType.php)

</details>

___

### Raw: CreateTable request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id",
    },
    "rows": 1,
    "columns": 1
}
```

Full Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id",
        "size": [Optional] {
            "width": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            },
            "height": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            }
        },
        "transform": [Optional] {
            "scaleX": 0.123456,
            "scaleY": 0.123456,
            "shearX": [Optional] 0.123456,
            "shearY": [Optional] 0.123456,
            "translateX": [Optional] 0.123456,
            "translateY": [Optional] 0.123456,
            "unit": [Optional] "EMU"
        }
    },
    "rows": 1,
    "columns": 1,
    "objectId": [Optional] "custom_object_id" /* Custom ID for the object to be created */
}
```

#### Enums included

- [Unit](/src/Services/Slides/Enums/Unit.php)

</details>

___

### Raw: CreateVideo request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id", /* Page object ID */
    },
    "id": "video_id" /* ID of the video to be embedded */
}
```

Full Object

```json
{
    "elementProperties": {
        "objectId": "page_object_id", /* Page object ID */
        "size": [Optional] {
            "width": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            },
            "height": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            }
        },
        "transform": [Optional] {
            "scaleX": 0.123456,
            "scaleY": 0.123456,
            "shearX": [Optional] 0.123456,
            "shearY": [Optional] 0.123456,
            "translateX": [Optional] 0.123456,
            "translateY": [Optional] 0.123456,
            "unit": [Optional] "EMU"
        }
    },
    "id": "video_id", /* ID of the video to be embedded */
    "source": [Optional] "DRIVE",
    "objectId": [Optional] "custom_object_id" /* Custom ID for the object to be created */
}
```

#### Enums included

- [Unit](/src/Services/Slides/Enums/Unit.php)
- [Source](/src/Services/Slides/Enums/Source.php)

</details>

___

### Raw: DeleteObject request

<details>
<summary><strong>Parameters</strong></summary>

Object

```json
{
    "objectId": "object_id" /* ID of the object to be removed */
}
```

</details>

___

### Raw: DeleteParagraphBullets request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "objectId": "object_id" /* ID of the object to be removed */
}
```

Full Object

```json
{
    "objectId": "object_id", /* ID of the object to be removed */
    "textRange": [Optional] {
        "startIndex": [Optional] 1,
        "endIndex": [Optional] 1,
        "type": [Optional] "ALL"
    },
    "cellLocation": [Optional] {
        "rowIndex": 1,
        "columnIndex": 1
    },
}
```

#### Enums included

- [RangeType](/src/Services/Slides/Enums/RangeType.php)

</details>

___

### Raw: DeleteTableColumn request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "tableObjectId": "table_object_id" /* ID of the table where the column will be removed from */
}
```

Full Object

```json
{
    "tableObjectId": "table_object_id", /* ID of the table where the column will be removed from */
    "cellLocation": [Optional] { /* The reference table cell location from which a column will be deleted. */
        "rowIndex": 1,
        "columnIndex": 1
    },
}
```

</details>

___

### Raw: DeleteTableRow request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "tableObjectId": "table_object_id" /* ID of the table where the row will be removed from */
}
```

Full Object

```json
{
    "tableObjectId": "table_object_id", /* ID of the table where the row will be removed from */
    "cellLocation": [Optional] { /* The reference table cell location from which a row will be deleted. */
        "rowIndex": 1,
        "columnIndex": 1
    },
}
```

</details>

___

### Raw: DeleteText request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "objectId": "object_id" /* ID of the object where the text will be removed from */
}
```

Full Object

```json
{
    "objectId": "object_id", /* ID of the object where the text will be removed from */
    "textRange": [Optional] {
        "startIndex": [Optional] 1,
        "endIndex": [Optional] 1,
        "type": [Optional] "ALL"
    },
    "cellLocation": [Optional] {
        "rowIndex": 1,
        "columnIndex": 1
    },
}
```

#### Enums included

- [RangeType](/src/Services/Slides/Enums/RangeType.php)

</details>

___

### Raw: InsertTableColumns request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "tableObjectId": "table_object_id", /* ID of the table where the columns will be inserted into */
    "cellLocation": [Optional] {
        "rowIndex": 1,
        "columnIndex": 1
    }
}
```

Full Object

```json
{
    "tableObjectId": "table_object_id", /* ID of the table where the columns will be inserted into */
    "cellLocation": [Optional] {
        "rowIndex": 1,
        "columnIndex": 1
    },
    "number": [Optional] 1, /* Number of columns to insert */
    "insertRight": [Optional] true
}
```

</details>

___

### Raw: InsertTableRows request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "tableObjectId": "table_object_id", /* ID of the table where the columns will be inserted into */
    "cellLocation": [Optional] {
        "rowIndex": 1,
        "columnIndex": 1
    }
}
```

Full Object

```json
{
    "tableObjectId": "table_object_id", /* ID of the table where the columns will be inserted into */
    "cellLocation": [Optional] {
        "rowIndex": 1,
        "columnIndex": 1
    },
    "number": [Optional] 1, /* Number of columns to insert */
    "insertBelow": [Optional] true
}
```

</details>

___

### Raw: InsertText request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "objectId": "object_id", /* ID of the object where the text will be inserted into */
    "text": "text"
}
```

Full Object

```json
{
    "objectId": "object_id", /* ID of the object where the text will be inserted into */
    "text": "text",
    "insertionIndex": [Optional] 0,
    "cellLocation": [Optional] {
        "rowIndex": 1,
        "columnIndex": 1
    },
}
```

</details>

___

### Raw: UpdateImageProperties request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "objectId": "object_id", /* ID of the image the updates are applied to */
    "imageProperties": { /* At least one property must be submitted for modification */
        "transparency": 0.9
    }
}
```

Full Object

```json
{
    "objectId": "object_id", /* ID of the image the updates are applied to */
    "imageProperties": { /* At least one property must be submitted for modification */
        "cropProperties": [Optional] {
            "leftOffset": [Optional] 0.123456,
            "rightOffset": [Optional] 0.123456,
            "topOffset": [Optional] 0.123456,
            "bottomOffset": [Optional] 0.123456,
            "angle": [Optional] 0.0
        },
        "transparency": [Optional] 1.0,
        "brightness": [Optional] 1.0,
        "contrast": [Optional] 1.0,
        "recolor": [Optional] {
            "recolorStops": {
                "color": {
                    "rgbColor": {
                        "red": 0.5, /* 0.0 - 1.0 */
                        "green": 0.5, /* 0.0 - 1.0 */
                        "blue": 0.5 /* 0.0 - 1.0 */
                    },
                    "themeColor": [Optional] "DARK1"
                },
                "alpha": [Optional] 1.0,
                "position": [Optional] 1.0
            },
            "name": [Optional] "NONE"
        },
        "outline": [Optional] {
            "outlineFill": {
                "solidFill": {
                    "color": {
                        "rgbColor": {
                            "red": 0.5, /* 0.0 - 1.0 */
                            "green": 0.5, /* 0.0 - 1.0 */
                            "blue": 0.5 /* 0.0 - 1.0 */
                        },
                        "themeColor": [Optional] "DARK1"
                    },
                    "alpha": [Optional] 1.0,
                }
            },
            "weight": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            },
            "dashStyle": [Optional] "SOLID",
            "propertyState": [Optional] "RENDERED"
        },
        "shadow": [Optional] {
            "transform": [Optional] {
                "scaleX": 0.123456,
                "scaleY": 0.123456,
                "shearX": [Optional] 0.123456,
                "shearY": [Optional] 0.123456,
                "translateX": [Optional] 0.123456,
                "translateY": [Optional] 0.123456,
                "unit": [Optional] "EMU"
            },
            "blurRadius": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            },
            "color": {
                "rgbColor": {
                    "red": 0.5, /* 0.0 - 1.0 */
                    "green": 0.5, /* 0.0 - 1.0 */
                    "blue": 0.5 /* 0.0 - 1.0 */
                },
                "themeColor": [Optional] "DARK1"
            },
            "alignment": [Optional] "CENTER",
            "type": [Optional] "OUTER",
            "alpha": [Optional] 1.0,
            "rotateWithShape": [Optional] true,
            "propertyState": [Optional] "RENDERED"
        },
        "link": [Optional] {
            "url": [Optional] "external_url",
            "relativeLink": [Optional] "RELATIVE_SLIDE_LINK_UNSPECIFIED", /* Link to a slide in this presentation, addressed by its position */
            "pageObjectId": [Optional] "page_object_id", /* Link to the specific page in this presentation with this ID */
            "slideIndex": [Optional] 1 /* Link to the slide at this zero-based index in the presentation */
        }
    },
    "fields": [Optional] "*"
}
```

#### Enums included

- [Unit](/src/Services/Slides/Enums/Unit.php)
- [ThemeColorType](/src/Services/Slides/Enums/ThemeColorType.php)
- [Name](/src/Services/Slides/Enums/Name.php)
- [DashStyle](/src/Services/Slides/Enums/DashStyle.php)
- [PropertyState](/src/Services/Slides/Enums/PropertyState.php)
- [RectanglePosition](/src/Services/Slides/Enums/RectanglePosition.php)
- [ShadowType](/src/Services/Slides/Enums/ShadowType.php)
- [RelativeSlideLink](/src/Services/Slides/Enums/RelativeSlideLink.php)

</details>

___

### Raw: UpdateShapeProperties request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "objectId": "object_id", /* ID of the shape the updates are applied to */
    "shapeProperties": { /* At least one property must be submitted for modification */
        "contentAlignment": "TOP"
    }
}
```

Full Object

```json
{
    "objectId": "object_id", /* ID of the shape the updates are applied to */
    "shapeProperties": { /* At least one property must be submitted for modification */
        "shapeBackgroundFill": [Optional] {
            "solidFill": {
                "color": {
                    "rgbColor": {
                        "red": 0.5, /* 0.0 - 1.0 */
                        "green": 0.5, /* 0.0 - 1.0 */
                        "blue": 0.5 /* 0.0 - 1.0 */
                    },
                    "themeColor": [Optional] "DARK1"
                },
                "alpha": [Optional] 1.0,
            },
            "propertyState": [Optional] "RENDERED"
        },
        "outline": [Optional] {
            "outlineFill": {
                "solidFill": {
                    "color": {
                        "rgbColor": {
                            "red": 0.5, /* 0.0 - 1.0 */
                            "green": 0.5, /* 0.0 - 1.0 */
                            "blue": 0.5 /* 0.0 - 1.0 */
                        },
                        "themeColor": [Optional] "DARK1"
                    },
                    "alpha": [Optional] 1.0,
                }
            },
            "weight": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            },
            "dashStyle": [Optional] "SOLID",
            "propertyState": [Optional] "RENDERED"
        },
        "shadow": [Optional] {
            "transform": [Optional] {
                "scaleX": 0.123456,
                "scaleY": 0.123456,
                "shearX": [Optional] 0.123456,
                "shearY": [Optional] 0.123456,
                "translateX": [Optional] 0.123456,
                "translateY": [Optional] 0.123456,
                "unit": [Optional] "EMU"
            },
            "blurRadius": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            },
            "color": {
                "rgbColor": {
                    "red": 0.5, /* 0.0 - 1.0 */
                    "green": 0.5, /* 0.0 - 1.0 */
                    "blue": 0.5 /* 0.0 - 1.0 */
                },
                "themeColor": [Optional] "DARK1"
            },
            "alignment": [Optional] "CENTER",
            "type": [Optional] "OUTER",
            "alpha": [Optional] 1.0,
            "rotateWithShape": [Optional] true,
            "propertyState": [Optional] "RENDERED"
        },
        "link": [Optional] {
            "url": [Optional] "external_url",
            "relativeLink": [Optional] "RELATIVE_SLIDE_LINK_UNSPECIFIED", /* Link to a slide in this presentation, addressed by its position */
            "pageObjectId": [Optional] "page_object_id", /* Link to the specific page in this presentation with this ID */
            "slideIndex": [Optional] 1 /* Link to the slide at this zero-based index in the presentation */
        },
        "contentAlignment": [Optional] "CONTENT_ALIGNMENT_UNSPECIFIED",
        "autofit": {
            "autofitType": [Optional] "AUTOFIT_TYPE_UNSPECIFIED",
            "fontScale": [Optional] 1.0,
            "lineSpacingReduction": [Optional] 0.0
        }
    },
    "fields": [Optional] "*"
}
```

#### Enums included

- [Unit](/src/Services/Slides/Enums/Unit.php)
- [ThemeColorType](/src/Services/Slides/Enums/ThemeColorType.php)
- [DashStyle](/src/Services/Slides/Enums/DashStyle.php)
- [PropertyState](/src/Services/Slides/Enums/PropertyState.php)
- [RectanglePosition](/src/Services/Slides/Enums/RectanglePosition.php)
- [ShadowType](/src/Services/Slides/Enums/ShadowType.php)
- [RelativeSlideLink](/src/Services/Slides/Enums/RelativeSlideLink.php)
- [ContentAlignment](/src/Services/Slides/Enums/ContentAlignment.php)
- [AutofitType](/src/Services/Slides/Enums/AutofitType.php)

</details>

___

### Raw: UpdateTableCellProperties request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "objectId": "object_id", /* ID of the table the updates are applied to */
    "tableCellProperties": { /* At least one property must be submitted for modification */
        "contentAlignment": "TOP"
    }
}
```

Full Object

```json
{
    "objectId": "object_id", /* ID of the table the updates are applied to */
    "tableCellProperties": { /* At least one property must be submitted for modification */
        "tableCellBackgroundFill": [Optional] {
            "solidFill": {
                "color": {
                    "rgbColor": {
                        "red": 0.5, /* 0.0 - 1.0 */
                        "green": 0.5, /* 0.0 - 1.0 */
                        "blue": 0.5 /* 0.0 - 1.0 */
                    },
                    "themeColor": [Optional] "DARK1"
                },
                "alpha": [Optional] 1.0,
            },
            "propertyState": [Optional] "RENDERED"
        },
        "contentAlignment": [Optional] "CONTENT_ALIGNMENT_UNSPECIFIED",
    },
    "fields": [Optional] "*",
    "tableRange": [Optional] {
        "location": {
            "rowIndex": 1,
            "columnIndex": 1
        },
        "rowSpan": [Optional] 1,
        "columnSpan": [Optional] 1
    }
}
```

#### Enums included

- [ThemeColorType](/src/Services/Slides/Enums/ThemeColorType.php)
- [PropertyState](/src/Services/Slides/Enums/PropertyState.php)
- [ContentAlignment](/src/Services/Slides/Enums/ContentAlignment.php)

</details>

___

### Raw: UpdateTableColumnProperties request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "objectId": "object_id", /* ID of the table the updates are applied to */
    "tableColumnProperties": { /* At least one property must be submitted for modification */
        "columnWidth": {
            "magnitude": 0.123456
        }
    },
    "columnIndices": [0,1,3,5]
}
```

Full Object

```json
{
    "objectId": "object_id", /* ID of the table the updates are applied to */
    "tableColumnProperties": { /* At least one property must be submitted for modification */
        "columnWidth": {
            "magnitude": 0.123456,
            "unit": [Optional] "EMU"
        }
    },
    "columnIndices": [0,1,3,5],
    "fields": [Optional] "*",
}
```

#### Enums included

- [Unit](/src/Services/Slides/Enums/Unit.php)

</details>

___

### Raw: UpdateTableRowProperties request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "objectId": "object_id", /* ID of the table the updates are applied to */
    "tableRowProperties": { /* At least one property must be submitted for modification */
        "minRowHeight": {
            "magnitude": 0.123456
        }
    },
    "rowIndices": [0,1,3,5]
}
```

Full Object

```json
{
    "objectId": "object_id", /* ID of the table the updates are applied to */
    "tableRowProperties": { /* At least one property must be submitted for modification */
        "minRowHeight": {
            "magnitude": 0.123456,
            "unit": [Optional] "EMU"
        }
    },
    "rowIndices": [0,1,3,5],
    "fields": [Optional] "*",
}
```

#### Enums included

- [Unit](/src/Services/Slides/Enums/Unit.php)

</details>

___

### Raw: UpdateVideoProperties request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
    "objectId": "object_id", /* ID of the image the updates are applied to */
    "videoProperties": { /* At least one property must be submitted for modification */
        "autoPlay": true
    }
}
```

Full Object

```json
{
    "objectId": "object_id", /* ID of the image the updates are applied to */
    "videoProperties": { /* At least one property must be submitted for modification */
        "outline": [Optional] {
            "outlineFill": {
                "solidFill": {
                    "color": {
                        "rgbColor": {
                            "red": 0.5, /* 0.0 - 1.0 */
                            "green": 0.5, /* 0.0 - 1.0 */
                            "blue": 0.5 /* 0.0 - 1.0 */
                        },
                        "themeColor": [Optional] "DARK1"
                    },
                    "alpha": [Optional] 1.0,
                }
            },
            "weight": {
                "magnitude": 0.123456,
                "unit": [Optional] "EMU"
            },
            "dashStyle": [Optional] "SOLID",
            "propertyState": [Optional] "RENDERED"
        },
        "autoPlay": [Optional] false,
        "start": 1,
        "end": 50,
        "mute": false
    },
    "fields": [Optional] "*"
}
```

#### Enums included

- [Unit](/src/Services/Slides/Enums/Unit.php)
- [ThemeColorType](/src/Services/Slides/Enums/ThemeColorType.php)
- [DashStyle](/src/Services/Slides/Enums/DashStyle.php)
- [PropertyState](/src/Services/Slides/Enums/PropertyState.php)

</details>

_______________________________________________________________________________________________

## Request methods

The following are non-static methods that can be called from instances (client instances) of the [SlidesApi](/src/Services/Slides/SlidesApi.php) class.

_______________________________________________________________________________________________

### Presentation

- ### getPresentationData: *Array*

  `Gets the spreadsheet data.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `presentationId`: *String*  
      ID of the Presentation to get data from.
  </details><br>

_______________________________________________________________________________________________

### Slides

- ### getSlide(): *Array*

  `Gets the slide data.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      - `pageId`: *String*  
      ID of the slide/page to get the data from.

      #### Union field (only one of the following is allowed)
      *Note:* If none of the following is specified, the request will fail.

      - `presentationId`: *String*  
      ID of the presentation where the slide is located.

      - `presentationData`: *Array*  
      Presentation data in case that it was retrieved previously.

      #### End union field

  - Optional

      - `checkPresentation`: *Boolean*  
      If true, the presentation will be checked for validity before the slide is retrieved.
      If the presentation data is submitted, this parameter is ignored.
  </details><br>

- ### createSlide(): *Array*

  `Create a Slide in a presentation.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

      #### Union field (only one of the following is allowed)
      *Note:* If none of the following is specified, the request will fail.

      - `presentationId`: *String*  
      ID of the presentation where the slide is located.

      - `presentationData`: *Array*  
      Presentation data in case that it was retrieved previously.

      #### End union field

  - Optional

      - `objectId`: *String*  
      ID for the slide to be created. If not provided, a new ID will be generated.

      - `insertionIndex`: *Integer*  
      Zero-based index indicating where to insert the slides. If not provided, the slide will be created at the end.

      - `slideLayoutReference`: [*LayoutReference*](/src/Services/Slides/Classes/LayoutReference.php)  
      Layout reference of the slide to be inserted, based on the master slide. If not provided, the slide uses the predefined BLANK layout.

      - `placeholderIdMappings`: [*Placeholder*](/src/Services/Slides/Classes/Placeholder.php)  
      An optional list of object ID mappings from the [placeholder](https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.Placeholder) (s) on the layout to the placeholder (s) that will be created on the new slide from that specified layout. Can be used when copying objects from a slide that uses the same layout (when when *slideLayoutReference* is specified).

      - `checkPresentation`: *Boolean*  
      If true, the presentation will be checked for validity before the slide is created.
      If the presentation data is submitted, this parameter is ignored.
  </details><br>
