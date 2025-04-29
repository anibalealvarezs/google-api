<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other\ConditionType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#booleancondition
 * @param ConditionValue[] $values
 * @param ConditionType|string $type
 * @return ConditionValue[]
 */
class BooleanCondition implements Jsonable
{
    public array $values;
    public ConditionType|string $type;
    
    public function __construct(
        array $values = [],
        ConditionType|string $type = ConditionType::BOOLEAN
    ) {
        // Format Filter Specs
        $formattedValues = [];
        if ($values) {
            foreach ($values as $value) {
                if (!($value instanceof ConditionValue)) {
                    $formattedValues[] = new ConditionValue(...$value);
                } else {
                    $formattedValues[] = $value;
                }
            }
        }
        $this->values = $formattedValues;
        $this->type = $this->stringToEnum(enum: ConditionType::class, var: $type);
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
