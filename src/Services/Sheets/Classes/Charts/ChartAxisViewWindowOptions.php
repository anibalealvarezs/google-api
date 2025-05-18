<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\LineDashType;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\ViewWindowMode;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#chartaxisviewwindowoptions
 */
class ChartAxisViewWindowOptions implements Jsonable
{
    public float $viewWindowMin;
    public float $viewWindowMax;
    public ViewWindowMode|string $viewWindowMode;
    
    public function __construct(
        float $viewWindowMin,
        float $viewWindowMax,
        ViewWindowMode|string $viewWindowMode = ViewWindowMode::PRETTY,
    ) {
        $this->viewWindowMin = $viewWindowMin;
        $this->viewWindowMax = $viewWindowMax;
        $this->viewWindowMode = $this->stringToEnum(enum: ViewWindowMode::class, var: $viewWindowMode);
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
