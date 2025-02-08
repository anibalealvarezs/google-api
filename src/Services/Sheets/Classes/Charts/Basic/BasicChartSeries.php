<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Basic;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\ChartData;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\DataLabel;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\LineStyle;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\PointStyle;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\Basic\BasicChartAxisPosition;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\Basic\BasicChartType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#basicchartseries
 */
class BasicChartSeries implements Jsonable
{
    public ChartData|array $series;
    public ColorStyle|array|null $colorStyle;
    public ?array $styleOverrides;
    public DataLabel|array|null $dataLabel;
    public PointStyle|array|null $pointStyle;
    public LineStyle|array|null $lineStyle;
    public BasicChartAxisPosition|string|null $targetAxis;
    public BasicChartType|string $type;
    
    public function __construct(
        ChartData|array $series,
        ColorStyle|array|null $colorStyle = null,
        ?array $styleOverrides = null,
        DataLabel|array|null $dataLabel = null,
        PointStyle|array|null $pointStyle = null,
        LineStyle|array|null $lineStyle = null,
        BasicChartAxisPosition|string|null $targetAxis = BasicChartAxisPosition::BOTTOM_AXIS,
        BasicChartType|string $type = BasicChartType::LINE,
    ) {
        $this->series = $this->arrayToObject(class: ChartData::class, var: $series);
        $this->dataLabel = $this->arrayToObject(class: ColorStyle::class, var: $dataLabel);
        $this->colorStyle = $colorStyle;
        $this->styleOverrides = $this->arrayToObject(class: DataLabel::class, var: $styleOverrides);
        $this->pointStyle = $this->arrayToObject(class: PointStyle::class, var: $pointStyle);
        $this->lineStyle = $this->arrayToObject(class: LineStyle::class, var: $lineStyle);
        $this->targetAxis = $this->stringToEnum(enum: BasicChartAxisPosition::class, var: $targetAxis);
        $this->type = $this->stringToEnum(enum: BasicChartType::class, var: $type);
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
