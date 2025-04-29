<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#conditionalformatrule
 */
class ConditionalFormatRule implements Jsonable
{
    public array $ranges;
    public BooleanRule|array|null $booleanRule;
    public GradientRule|array|null $gradientRule;
    
    public function __construct(
        array $ranges,
        BooleanRule|array|null $booleanRule = null,
        GradientRule|array|null $gradientRule = null,
    ) {
        $this->ranges = $ranges;
        $this->booleanRule = $this->arrayToObject(class: BooleanRule::class, var: $booleanRule);
        $this->gradientRule = $this->arrayToObject(class: GradientRule::class, var: $gradientRule);

        $this->keepOneOfKind([
            'booleanRule',
            'gradientRule'
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
