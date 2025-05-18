<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Text;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Dimension;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Text\Alignment;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Text\SpacingMode;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Text\TextDirection;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#paragraphstyle
 */
class ParagraphStyle implements Jsonable
{
    public ?int $lineSpacing;
    public Alignment|string|null $alignment;
    public Dimension|array|null $indentStart;
    public Dimension|array|null $indentEnd;
    public Dimension|array|null $spaceAbove;
    public Dimension|array|null $spaceBelow;
    public Dimension|array|null $indentFirstLine;
    public TextDirection|string|null $direction;
    public SpacingMode|string|null $spacingMode;
    
    public function __construct(
        ?int $lineSpacing = null,
        Alignment|string|null $alignment = Alignment::START,
        Dimension|array|null $indentStart = null,
        Dimension|array|null $indentEnd = null,
        Dimension|array|null $spaceAbove = null,
        Dimension|array|null $spaceBelow = null,
        Dimension|array|null $indentFirstLine = null,
        TextDirection|string|null $direction = TextDirection::LEFT_TO_RIGHT,
        SpacingMode|string|null $spacingMode = SpacingMode::SPACING_MODE_UNSPECIFIED,
    ) {
        $this->lineSpacing = $lineSpacing;
        $this->alignment = $this->stringToEnum(enum: Alignment::class, var: $alignment);
        $this->indentStart = $this->arrayToObject(class: Dimension::class, var: $indentStart);
        $this->indentEnd = $this->arrayToObject(class: Dimension::class, var: $indentEnd);
        $this->spaceAbove = $this->arrayToObject(class: Dimension::class, var: $spaceAbove);
        $this->spaceBelow = $this->arrayToObject(class: Dimension::class, var: $spaceBelow);
        $this->indentFirstLine = $this->arrayToObject(class: Dimension::class, var: $indentFirstLine);
        $this->direction = $this->stringToEnum(enum: TextDirection::class, var: $direction);
        $this->spacingMode = $this->stringToEnum(enum: SpacingMode::class, var: $spacingMode);
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
