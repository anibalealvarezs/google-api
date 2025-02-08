<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Basic;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\ChartAxisViewWindowOptions;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\TextFormat;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\TextPosition;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\Basic\BasicChartAxisPosition;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#basicchartaxis
 */
class BasicChartAxis implements Jsonable
{
    public string $title;
    public TextFormat|array|null $format;
    public TextPosition|array|null $titleTextPosition;
    public ChartAxisViewWindowOptions|array|null $viewWindowOptions;
    public BasicChartAxisPosition|string $position;
    
    public function __construct(
        string $title,
        TextFormat|array|null $format = null,
        TextPosition|array|null $titleTextPosition = null,
        ChartAxisViewWindowOptions|array|null $viewWindowOptions = null,
        BasicChartAxisPosition|string $position = BasicChartAxisPosition::BOTTOM_AXIS,
    ) {
        $this->title = $title;
        $this->format = $this->arrayToObject(class: TextFormat::class, var: $format);
        $this->titleTextPosition = $this->arrayToObject(class: TextPosition::class, var: $titleTextPosition);
        $this->viewWindowOptions = $this->arrayToObject(class: ChartAxisViewWindowOptions::class, var: $viewWindowOptions);
        $this->position = $this->stringToEnum(enum: BasicChartAxisPosition::class, var: $position);
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
