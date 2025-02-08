<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#filtercriteria
 */
class FilterCriteria implements Jsonable
{
    public BooleanCondition|array $condition;
    public ColorStyle|array $visibleBackgroundColorStyle;
    public ColorStyle|array $visibleForegroundColorStyle;
    public array $hiddenValues;
    
    public function __construct(
        BooleanCondition|array $condition,
        ColorStyle|array $visibleBackgroundColorStyle,
        ColorStyle|array $visibleForegroundColorStyle,
        array $hiddenValues = []
    ) {
        $this->condition = $this->arrayToObject(class: BooleanCondition::class, var: $condition);
        $this->visibleBackgroundColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $visibleBackgroundColorStyle);
        $this->visibleForegroundColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $visibleForegroundColorStyle);
        $this->hiddenValues = $hiddenValues;
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
