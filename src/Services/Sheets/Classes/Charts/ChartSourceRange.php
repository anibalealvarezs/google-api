<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\GridRange;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#chartsourcerange
 * @param GridRange[] $sources
 * @return ChartSourceRange
 */
class ChartSourceRange implements Jsonable
{
    public array $sources;
    
    public function __construct(
        array $sources
    ) {
        // Format Filter Specs
        $formattedSources = [];
        if ($sources) {
            foreach ($sources as $source) {
                if (!($source instanceof GridRange)) {
                    $formattedSources[] = new GridRange(...$source);
                } else {
                    $formattedSources[] = $source;
                }
            }
        }
        $this->sources = $formattedSources;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
