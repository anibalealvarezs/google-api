<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\BooleanCondition;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#datavalidationrule
 */
class DataValidationRule implements Jsonable
{
    public BooleanCondition|array $condition;
    public string $inputMessage;
    public bool $strict;
    public bool $showCustomUi;
    
    public function __construct(
        BooleanCondition|array $condition,
        string $inputMessage,
        bool $strict = true,
        bool $showCustomUi = true
    ) {
        $this->condition = $this->arrayToObject(class: BooleanCondition::class, var: $condition);
        $this->inputMessage = $inputMessage;
        $this->strict = $strict;
        $this->showCustomUi = $showCustomUi;
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
