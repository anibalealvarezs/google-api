<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Enums\Other\RelativeDate;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#conditionvalue
 */
class ConditionValue implements Jsonable
{
    public RelativeDate|string|null $relativeDate;
    public ?string $userEnteredValue;
    
    public function __construct(
        RelativeDate|string|null $relativeDate = null,
        ?string $userEnteredValue = null,
    ) {
        $this->relativeDate = $this->stringToEnum(enum: RelativeDate::class, var: $relativeDate);
        $this->userEnteredValue = $userEnteredValue;

        $this->keepOneOfKind([
            'userEnteredValue',
            'relativeDate',
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

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
