<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Spreadsheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Basic\BasicChartSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Bubble\BubbleChartSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Candlestick\CandlestickChartSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Candlestick\CandlestickDomain;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\ChartData;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\DataLabel;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\DataSourceChartProperties;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Histogram\HistogramChartSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\KeyValueFormat;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\LineStyle;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Org\OrgChartSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Pie\PieChartSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Scorecard\ScorecardChartSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Treemap\TreemapChartColorScale;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Treemap\TreemapChartSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Waterfall\WaterfallChartDomain;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Waterfall\WaterfallChartSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\TextFormat;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\TextPosition;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic\BasicChartCompareMode;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic\BasicChartLegendPosition;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic\BasicChartStackedType;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic\BasicChartType;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\ChartHiddenDimensionStrategy;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\ChartTypes;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#ChartSpec
 * @param string $title
 * @param string $subtitle
 * @param TextFormat|array $subtitleTextFormat
 * @param string $fontName
 * @param ColorStyle|array|null $backgroundColorStyle
 * @param TextFormat|array|null $titleTextFormat
 * @param DataSourceChartProperties|array|null $dataSourceChartProperties
 * @param ?array $filterSpecs
 * @param ?array $sortSpecs
 * @param ChartHiddenDimensionStrategy|string|null $hiddenDimensionStrategy
 * @param BasicChartSpec|array|null $basicChart
 * @param PieChartSpec|array|null $pieChart
 * @param BubbleChartSpec|array|null $bubbleChart
 * @param CandlestickChartSpec|array|null $candlestickChart
 * @param OrgChartSpec|array|null $orgChart
 * @param HistogramChartSpec|array|null $histogramChart
 * @param WaterfallChartSpec|array|null $waterfallChart
 * @param TreemapChartSpec|array|null $treemapChart
 * @param ScorecardChartSpec|array|null $scorecardChart
 * @param ?string $altText
 * @param TextPosition|array|null $titleTextPosition
 * @param TextPosition|array|null $subtitleTextPosition
 * @param bool $maximized
 * @return ChartSpec
 */
class ChartSpec implements Jsonable
{
    public string $title;
    public string $subtitle;
    public TextFormat|array $subtitleTextFormat;
    public string $fontName;
    public ColorStyle|array|null $backgroundColorStyle;
    public TextFormat|array|null $titleTextFormat;
    public DataSourceChartProperties|array|null $dataSourceChartProperties;
    public ?array $filterSpecs;
    public ?array $sortSpecs;
    public ChartHiddenDimensionStrategy|string|null $hiddenDimensionStrategy;
    public BasicChartSpec|array|null $basicChart;
    public PieChartSpec|array|null $pieChart;
    public BubbleChartSpec|array|null $bubbleChart;
    public CandlestickChartSpec|array|null $candlestickChart;
    public OrgChartSpec|array|null $orgChart;
    public HistogramChartSpec|array|null $histogramChart;
    public WaterfallChartSpec|array|null $waterfallChart;
    public TreemapChartSpec|array|null $treemapChart;
    public ScorecardChartSpec|array|null $scorecardChart;
    public ?string $altText;
    public TextPosition|array|null $titleTextPosition;
    public TextPosition|array|null $subtitleTextPosition;
    public bool $maximized;
    
    public function __construct(
        string $title,
        string $subtitle,
        TextFormat|array $subtitleTextFormat,
        string $fontName,
        ColorStyle|array|null $backgroundColorStyle = null,
        TextFormat|array|null $titleTextFormat = null,
        DataSourceChartProperties|array|null $dataSourceChartProperties = null,
        ?array $filterSpecs = null,
        ?array $sortSpecs = null,
        ChartHiddenDimensionStrategy|string|null $hiddenDimensionStrategy = ChartHiddenDimensionStrategy::SKIP_HIDDEN_ROWS_AND_COLUMNS,
        BasicChartSpec|array|null $basicChart = null,
        PieChartSpec|array|null $pieChart = null,
        BubbleChartSpec|array|null $bubbleChart = null,
        CandlestickChartSpec|array|null $candlestickChart = null,
        OrgChartSpec|array|null $orgChart = null,
        HistogramChartSpec|array|null $histogramChart = null,
        WaterfallChartSpec|array|null $waterfallChart = null,
        TreemapChartSpec|array|null $treemapChart = null,
        ScorecardChartSpec|array|null $scorecardChart = null,
        ?string $altText = null,
        TextPosition|array|null $titleTextPosition = null,
        TextPosition|array|null $subtitleTextPosition = null,
        bool $maximized = false,
    ) {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->subtitleTextFormat = $this->arrayToObject(class: TextFormat::class, var: $subtitleTextFormat);
        $this->fontName = $fontName;
        $this->backgroundColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $backgroundColorStyle);
        $this->titleTextFormat = $this->arrayToObject(class: TextFormat::class, var: $titleTextFormat);
        $this->dataSourceChartProperties = $this->arrayToObject(class: DataSourceChartProperties::class, var: $dataSourceChartProperties);
        $this->filterSpecs = $filterSpecs;
        $this->sortSpecs = $sortSpecs;
        $this->hiddenDimensionStrategy = $this->stringToEnum(enum: ChartHiddenDimensionStrategy::class, var: $hiddenDimensionStrategy);
        $this->basicChart = $this->arrayToObject(class: BasicChartSpec::class, var: $basicChart);
        $this->pieChart = $this->arrayToObject(class: PieChartSpec::class, var: $pieChart);
        $this->bubbleChart = $this->arrayToObject(class: BubbleChartSpec::class, var: $bubbleChart);
        $this->candlestickChart = $this->arrayToObject(class: CandlestickChartSpec::class, var: $candlestickChart);
        $this->orgChart = $this->arrayToObject(class: OrgChartSpec::class, var: $orgChart);
        $this->histogramChart = $this->arrayToObject(class: HistogramChartSpec::class, var: $histogramChart);
        $this->waterfallChart = $this->arrayToObject(class: WaterfallChartSpec::class, var: $waterfallChart);
        $this->treemapChart = $this->arrayToObject(class: TreemapChartSpec::class, var: $treemapChart);
        $this->scorecardChart = $this->arrayToObject(class: ScorecardChartSpec::class, var: $scorecardChart);
        $this->altText = $altText;
        $this->titleTextPosition = $this->arrayToObject(class: TextPosition::class, var: $titleTextPosition);
        $this->subtitleTextPosition = $this->arrayToObject(class: TextPosition::class, var: $subtitleTextPosition);
        $this->maximized = $maximized;

        $this->keepOneOfKind([
            'basicChart',
            'pieChart',
            'bubbleChart',
            'candlestickChart',
            'orgChart',
            'histogramChart',
            'waterfallChart',
            'treemapChart',
            'scorecardChart'
        ]);
    }

    public function keepOneOfKind(array $properties): void
    {
        if ($key = Helpers::getFirstNotNullPropertyFrom($this, $properties)) {
            Helpers::nullifyOtherProperties($this, $key);
        }
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }

    public function setChartData(ChartTypes $chartType, array $data): void
    {
        $formattedChartType = $this->stringToEnum(enum: ChartTypes::class, var: $chartType);
        switch($formattedChartType) {
            case ChartTypes::BASIC:
                $this->basicChart = $this->setBasicChart(...$data);
                break;
            case ChartTypes::PIE:
                $this->pieChart = $this->setPieChart(...$data);
                break;
            case ChartTypes::BUBBLE:
                $this->bubbleChart = $this->setBubbleChart(...$data);
                break;
            case ChartTypes::CANDLESTICK:
                $this->candlestickChart = $this->setCandlestickChart(...$data);
                break;
            case ChartTypes::ORG:
                $this->orgChart = $this->setOrgChart(...$data);
                break;
            case ChartTypes::HISTOGRAM:
                $this->histogramChart = $this->setHistogramChart(...$data);
                break;
            case ChartTypes::WATERFALL:
                $this->waterfallChart = $this->setWaterfallChart(...$data);
                break;
            case ChartTypes::TREEMAP:
                $this->treemapChart = $this->setTreemapChart(...$data);
                break;
            case ChartTypes::SCORECARD:
                $this->scorecardChart = $this->setScorecardChart(...$data);
                break;
        }
        $this->keepOneOfKind([
            'basicChart',
            'pieChart',
            'bubbleChart',
            'candlestickChart',
            'orgChart',
            'histogramChart',
            'waterfallChart',
            'treemapChart',
            'scorecardChart'
        ]);
    }

    protected function setBasicChart(
        array $axis,
        array $domains,
        array $series,
        DataLabel|array|null $totalDataLabel = null,
        BasicChartLegendPosition|string|null $legendPosition = BasicChartLegendPosition::BOTTOM_LEGEND,
        BasicChartStackedType|string|null $stackedType = BasicChartStackedType::NOT_STACKED,
        BasicChartCompareMode|string|null $compareMode = BasicChartCompareMode::DATUM,
        BasicChartType|string|null $chartType = BasicChartType::LINE,
    ): BasicChartSpec {
        return new BasicChartSpec(
            axis: $axis,
            domains: $domains,
            series: $series,
            chartType: $this->stringToEnum(enum: BasicChartStackedType::class, var: $chartType),
            totalDataLabel: $this->arrayToObject(class: DataLabel::class, var: $totalDataLabel),
            legendPosition: $this->stringToEnum(enum: BasicChartCompareMode::class, var: $legendPosition),
            stackedType: $this->stringToEnum(enum: BasicChartLegendPosition::class, var: $stackedType),
            compareMode: $this->stringToEnum(enum: BasicChartType::class, var: $compareMode),
        );
    }

    protected function setPieChart(
        ChartData|array $domain,
        ChartData|array $series,
        float $pieHole
    ): PieChartSpec {
        return new PieChartSpec(
            domain: $this->arrayToObject(class: ChartData::class, var: $domain),
            series: $this->arrayToObject(class: ChartData::class, var: $series),
            pieHole: $pieHole,
        );
    }

    protected function setBubbleChart(
        ChartData|array $bubbleLabels,
        ChartData|array $domain,
        ChartData|array $series,
        ChartData|array $groupIds,
        ChartData|array $bubbleSizes,
        ColorStyle|array $bubbleBorderColorStyle,
        TextFormat|array $bubbleTextStyle,
    ): BubbleChartSpec {
        return new BubbleChartSpec(
            bubbleLabels: $this->arrayToObject(class: ChartData::class, var: $bubbleLabels),
            domain: $this->arrayToObject(class: ChartData::class, var: $domain),
            series: $this->arrayToObject(class: ChartData::class, var: $series),
            groupIds: $this->arrayToObject(class: ChartData::class, var: $groupIds),
            bubbleSizes: $this->arrayToObject(class: ChartData::class, var: $bubbleSizes),
            bubbleBorderColorStyle: $this->arrayToObject(class: ColorStyle::class, var: $bubbleBorderColorStyle),
            bubbleTextStyle: $this->arrayToObject(class: TextFormat::class, var: $bubbleTextStyle),
        );
    }

    protected function setCandlestickChart(
        CandlestickDomain|array $domain,
        array $data
    ): CandlestickChartSpec {
        return new CandlestickChartSpec(
            domain: $this->arrayToObject(class: CandlestickDomain::class, var: $domain),
            data: $data,
        );
    }

    protected function setOrgChart(
        ColorStyle|array $nodeColorStyle,
        ColorStyle|array $selectedNodeColorStyle,
        ChartData|array $labels,
    ): OrgChartSpec {
        return new OrgChartSpec(
            nodeColorStyle: $this->arrayToObject(class: ColorStyle::class, var: $nodeColorStyle),
            selectedNodeColorStyle: $this->arrayToObject(class: ColorStyle::class, var: $selectedNodeColorStyle),
            labels: $this->arrayToObject(class: ChartData::class, var: $labels),
        );
    }

    protected function setHistogramChart(
        array $series,
        float $bucketSize,
        float $outlierPercentile,
    ): HistogramChartSpec {
        return new HistogramChartSpec(
            series: $series,
            bucketSize: $bucketSize,
            outlierPercentile: $outlierPercentile,
        );
    }

    protected function setWaterfallChart(
        WaterfallChartDomain|array $domain,
        array $series,
        LineStyle|array $connectorLineStyle,
        DataLabel|array $totalDataLabel,
    ): WaterfallChartSpec {
        return new WaterfallChartSpec(
            domain: $this->arrayToObject(class: WaterfallChartDomain::class, var: $domain),
            series: $series,
            connectorLineStyle: $this->arrayToObject(class: LineStyle::class, var: $connectorLineStyle),
            totalDataLabel: $this->arrayToObject(class: DataLabel::class, var: $totalDataLabel),
        );
    }

    protected function setTreemapChart(
        ChartData|array $labels,
        ChartData|array $parentLabels,
        ChartData|array $sizeData,
        ChartData|array $colorData,
        TextFormat|array $textFormat,
        ColorStyle|array $headerColorStyle,
        TreemapChartColorScale|array $colorScale,
    ): TreemapChartSpec {
        return new TreemapChartSpec(
            labels: $this->arrayToObject(class: ChartData::class, var: $labels),
            parentLabels: $this->arrayToObject(class: ChartData::class, var: $parentLabels),
            sizeData: $this->arrayToObject(class: ChartData::class, var: $sizeData),
            colorData: $this->arrayToObject(class: ChartData::class, var: $colorData),
            textFormat: $this->arrayToObject(class: TextFormat::class, var: $textFormat),
            headerColorStyle: $this->arrayToObject(class: ColorStyle::class, var: $headerColorStyle),
            colorScale: $this->arrayToObject(class: TreemapChartColorScale::class, var: $colorScale),
        );
    }

    protected function setScorecardChart(
        ChartData|array $keyValueData,
        KeyValueFormat|array $keyValueFormat,
    ): ScorecardChartSpec {
        return new ScorecardChartSpec(
            keyValueData: $this->arrayToObject(class: ChartData::class, var: $keyValueData),
            keyValueFormat: $this->arrayToObject(class: KeyValueFormat::class, var: $keyValueFormat),
        );
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
