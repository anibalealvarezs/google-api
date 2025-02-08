<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Waterfall;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#waterfallchartcustomsubtotal
 */
class WaterfallChartCustomSubtotal implements Jsonable
{
    public int $subtotalIndex;
    public string $label;
    public bool $dataIsSubtotal;
    
    public function __construct(
        int $subtotalIndex,
        string $label,
        bool $dataIsSubtotal = false,
    ) {
        $this->subtotalIndex = $subtotalIndex;
        $this->label = $label;
        $this->dataIsSubtotal = $dataIsSubtotal;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
