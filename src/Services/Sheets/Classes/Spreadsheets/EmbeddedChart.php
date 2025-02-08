<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Spreadsheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\EmbeddedObjectBorder;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\EmbeddedObjectPosition;
use Chmw\GoogleApi\Services\Sheets\Classes\Spreadsheets\ChartSpec;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/create#embeddedchart
 */
class EmbeddedChart implements Jsonable
{
    public int $chartId;
    public ChartSpec|array $spec;
    public EmbeddedObjectPosition|array $position;
    public EmbeddedObjectBorder|array|null $border;
    
    public function __construct(
        int $chartId,
        ChartSpec|array $spec,
        EmbeddedObjectPosition|array $position,
        EmbeddedObjectBorder|array|null $border = null,
    ) {
        $this->chartId = $chartId;
        $this->spec = $this->arrayToObject(class: ChartSpec::class, var: $spec);
        $this->position = $this->arrayToObject(class: EmbeddedObjectPosition::class, var: $position);
        $this->border = $this->arrayToObject(class: EmbeddedObjectBorder::class, var: $border);
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
