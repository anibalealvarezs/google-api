<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Shapes;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Shapes\AutofitType;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/shapes#Page.Autofit
 */
class Autofit implements Jsonable
{
    public AutofitType|string $autofitType;
    public readonly ?float $fontScale;
    public readonly ?float $lineSpacingReduction;

    public function __construct(
        AutofitType|string $autofitType = AutofitType::AUTOFIT_TYPE_UNSPECIFIED,
        ?float $fontScale = null,
        ?float $lineSpacingReduction = null,
    ) {
        $this->autofitType = $this->stringToEnum(enum: AutofitType::class, var: $autofitType);
        $this->fontScale = $fontScale;
        $this->lineSpacingReduction = $lineSpacingReduction;
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
