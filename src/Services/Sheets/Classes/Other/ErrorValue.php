<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Enums\Other\ErrorType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#errorvalue
 */
class ErrorValue implements Jsonable
{
    public string $message;
    public ErrorType|string $type;
    
    public function __construct(
        string $message,
        ErrorType|string $type = ErrorType::ERROR,
    ) {
        $this->message = $message;
        $this->type = $this->stringToEnum(enum: ErrorType::class, var: $type);
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
