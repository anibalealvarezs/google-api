<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\SolidFill;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Other\PropertyState;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/tables#Page.TableCellBackgroundFill
 */
class TableCellBackgroundFill implements Jsonable
{
    public SolidFill|array $solidFill;
    public PropertyState|string $propertyState;
    
    public function __construct(
        SolidFill|array $solidFill,
        PropertyState|string $propertyState = PropertyState::RENDERED
    ) {
        $this->solidFill = $this->arrayToObject(class: DataExecutionStatus::class, var: $solidFill);
        $this->propertyState = $this->stringToEnum(enum: DataSourceTableColumnSelectionType::class, var: $propertyState);
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
