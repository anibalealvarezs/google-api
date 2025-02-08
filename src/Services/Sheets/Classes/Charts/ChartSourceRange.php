<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\GridRange;

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
