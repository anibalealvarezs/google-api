<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other\ErrorType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#extendedvalue
 */
class ExtendedValue implements Jsonable
{
    public ?string $stringValue;
    public ?string $formulaValue;
    public int|float|null $numberValue;
    public readonly ErrorValue|array|null $errorValue;
    public ?bool $boolValue;
    
    public function __construct(
        ?string $stringValue = null,
        ?string $formulaValue = null,
        int|float|null $numberValue = null,
        ErrorValue|array|null $errorValue = null,
        ?bool $boolValue = null,
    ) {
        $this->stringValue = $stringValue;
        $this->formulaValue = $formulaValue;
        $this->numberValue = $numberValue;
        $this->errorValue = $this->arrayToObject(class: ErrorValue::class, var: $errorValue);
        $this->boolValue = $boolValue;

        $this->keepOneOfKind([
            'stringValue',
            'formulaValue',
            'numberValue',
            'errorValue',
            'boolValue'
        ]);
    }

    public function keepOneOfKind(array $properties): void
    {
        if ($key = Helpers::getFirstNotNullPropertyFrom($this, $properties)) {
            Helpers::nullifyOtherProperties($this, $key);
        }
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
