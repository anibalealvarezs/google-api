<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Waterfall;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\ChartData;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\DataLabel;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#waterfallchartseries
 */
class WaterfallChartSeries implements Jsonable
{
    public ChartData|array $data;
    public array $customSubtotals;
    public DataLabel|array $dataLabel;
    public WaterfallChartColumnStyle|array $positiveColumnsStyle;
    public WaterfallChartColumnStyle|array $negativeColumnsStyle;
    public WaterfallChartColumnStyle|array $subtotalColumnsStyle;
    public bool $hideTrailingSubtotal;
    
    public function __construct(
        ChartData|array $data,
        array $customSubtotals,
        DataLabel|array $dataLabel,
        WaterfallChartColumnStyle|array $positiveColumnsStyle,
        WaterfallChartColumnStyle|array $negativeColumnsStyle,
        WaterfallChartColumnStyle|array $subtotalColumnsStyle,
        bool $hideTrailingSubtotal = false
    ) {
        $this->data = $this->arrayToObject(class: ChartData::class, var: $data);
        $this->customSubtotals = $customSubtotals;
        $this->dataLabel = $this->arrayToObject(class: DataLabel::class, var: $dataLabel);
        $this->positiveColumnsStyle = $this->arrayToObject(class: WaterfallChartColumnStyle::class, var: $positiveColumnsStyle);
        $this->negativeColumnsStyle = $this->arrayToObject(class: WaterfallChartColumnStyle::class, var: $negativeColumnsStyle);
        $this->subtotalColumnsStyle = $this->arrayToObject(class: WaterfallChartColumnStyle::class, var: $subtotalColumnsStyle);
        $this->hideTrailingSubtotal = $hideTrailingSubtotal;
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
