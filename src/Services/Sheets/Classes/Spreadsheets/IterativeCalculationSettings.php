<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Spreadsheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets#iterativecalculationsettings
 */
class IterativeCalculationSettings implements Jsonable
{
    public int $maxIterations;
    public float $convergenceThreshold;
    
    public function __construct(
        int $maxIterations,
        float $convergenceThreshold,
    ) {
        $this->maxIterations = $maxIterations;
        $this->convergenceThreshold = $convergenceThreshold;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
