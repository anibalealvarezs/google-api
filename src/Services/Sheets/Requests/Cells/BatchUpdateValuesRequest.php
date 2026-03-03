<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Cells;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells\ValueRange;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\DateTimeRenderOption;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\ValueInputOption;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\ValueRenderOption;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.values/batchUpdate
 */
class BatchUpdateValuesRequest implements Jsonable
{
    public string $valueInputOption;
    public array $data;
    public bool $includeValuesInResponse;
    public string $responseValueRenderOption;
    public string $responseDateTimeRenderOption;

    public function __construct(
        array $data,
        ValueInputOption|string $valueInputOption = ValueInputOption::USER_ENTERED,
        bool $includeValuesInResponse = false,
        ValueRenderOption|string $responseValueRenderOption = ValueRenderOption::FORMATTED_VALUE,
        DateTimeRenderOption|string $responseDateTimeRenderOption = DateTimeRenderOption::SERIAL_NUMBER,
    ) {
        $this->valueInputOption = ($valueInputOption instanceof ValueInputOption) ? $valueInputOption->name : $valueInputOption;
        $this->includeValuesInResponse = $includeValuesInResponse;
        $this->responseValueRenderOption = ($responseValueRenderOption instanceof ValueRenderOption) ? $responseValueRenderOption->name : $responseValueRenderOption;
        $this->responseDateTimeRenderOption = ($responseDateTimeRenderOption instanceof DateTimeRenderOption) ? $responseDateTimeRenderOption->name : $responseDateTimeRenderOption;

        $formattedData = [];
        foreach ($data as $valueRange) {
            if (!($valueRange instanceof ValueRange)) {
                $formattedData[] = new ValueRange(...$valueRange);
            } else {
                $formattedData[] = $valueRange;
            }
        }
        $this->data = $formattedData;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
