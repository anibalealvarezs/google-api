<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#chartcustomnumberformatoptions
 */
class ChartCustomNumberFormatOptions implements Jsonable
{
    public ?string $prefix;
    public ?string $suffix;
    
    public function __construct(
        ?string $prefix = null,
        ?string $suffix = null,
    ) {
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
