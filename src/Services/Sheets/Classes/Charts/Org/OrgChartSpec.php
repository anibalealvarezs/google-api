<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Org;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\ChartData;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\Org\OrgChartNodeSize;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#orgchartspec
 */
class OrgChartSpec implements Jsonable
{
    public ColorStyle|array $nodeColorStyle;
    public ColorStyle|array $selectedNodeColorStyle;
    public ChartData|array $labels;
    public ChartData|array|null $parentLabels;
    public ChartData|array|null $tooltips;
    public OrgChartNodeSize|string $nodeSize;
    
    public function __construct(
        ColorStyle|array $nodeColorStyle,
        ColorStyle|array $selectedNodeColorStyle,
        ChartData|array $labels,
        ChartData|array|null $parentLabels = null,
        ChartData|array|null $tooltips = null,
        OrgChartNodeSize|string $nodeSize = OrgChartNodeSize::MEDIUM,
    ) {
        $this->nodeColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $nodeColorStyle);
        $this->selectedNodeColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $selectedNodeColorStyle);
        $this->labels = $this->arrayToObject(class: ChartData::class, var: $labels);
        $this->parentLabels = $this->arrayToObject(class: ChartData::class, var: $parentLabels);
        $this->tooltips = $this->arrayToObject(class: ChartData::class, var: $tooltips);
        $this->nodeSize = $this->stringToEnum(enum: OrgChartNodeSize::class, var: $nodeSize);
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
