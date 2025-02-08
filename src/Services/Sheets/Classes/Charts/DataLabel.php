<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\TextFormat;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\DataLabelPlacement;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\DataLabelType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#datalabel
 */
class DataLabel implements Jsonable
{
    public TextFormat|array $textFormat;
    public ChartData|array $customLabelData;
    public DataLabelType|string $type;
    public DataLabelPlacement|string $placement;
    
    public function __construct(
        TextFormat|array $textFormat,
        ChartData|array $customLabelData,
        DataLabelType|string $type = DataLabelType::NONE,
        DataLabelPlacement|string $placement = DataLabelPlacement::OUTSIDE_END
    ) {
        $this->textFormat = $this->arrayToObject(class: TextFormat::class, var: $textFormat);
        $this->customLabelData = $this->arrayToObject(class: ChartData::class, var: $customLabelData);
        $this->type = $this->stringToEnum(enum: DataLabelType::class, var: $type);
        $this->placement = $this->stringToEnum(enum: DataLabelPlacement::class, var: $placement);
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
