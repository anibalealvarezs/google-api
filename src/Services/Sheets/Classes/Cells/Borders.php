<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Cells;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#borders
 */
class Borders implements Jsonable
{
    public Border|array|null $top;
    public Border|array|null $bottom;
    public Border|array|null $left;
    public Border|array|null $right;
    
    public function __construct(
        Border|array|null $top = null,
        Border|array|null $bottom = null,
        Border|array|null $left = null,
        Border|array|null $right = null,
    ) {
        $this->top = $this->arrayToObject(class: Border::class, var: $top);
        $this->bottom = $this->arrayToObject(class: Border::class, var: $bottom);
        $this->left = $this->arrayToObject(class: Border::class, var: $left);
        $this->right = $this->arrayToObject(class: Border::class, var: $right);
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
