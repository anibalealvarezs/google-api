<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Charts;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/charts#sheetschart
 */
class SheetsChart implements Jsonable
{
    public string $spreadsheetId;
    public int $chartId;
    public string $contentUrl;
    public SheetsChartProperties|array|null $sheetsChartProperties;

    public function __construct(
        string $spreadsheetId,
        int $chartId,
        string $contentUrl,
        SheetsChartProperties|array|null $sheetsChartProperties,
    ) {
        $this->spreadsheetId = $spreadsheetId;
        $this->chartId = $chartId;
        $this->contentUrl = $contentUrl;
        $this->sheetsChartProperties = $this->arrayToObject(class: SheetsChartProperties::class, var: $sheetsChartProperties);
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
