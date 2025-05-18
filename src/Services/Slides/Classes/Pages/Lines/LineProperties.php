<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Lines;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Dimension;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\Link;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Lines\ArrowStyle;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Other\DashStyle;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/lines#line
 */
class LineProperties implements Jsonable
{
    public LineFill|array $lineFill;
    public Dimension|array $weight;
    public DashStyle|string $dashStyle;
    public ArrowStyle|string $startArrow;
    public ArrowStyle|string $endArrow;
    public Link|array|null $link;
    public LineConnection|array|null $startConnection;
    public LineConnection|array|null $endConnection;
    
    public function __construct(
        LineFill|array $lineFill,
        Dimension|array $weight,
        DashStyle|string $dashStyle,
        ArrowStyle|string $startArrow,
        ArrowStyle|string $endArrow,
        Link|array|null $link = null,
        LineConnection|array|null $startConnection = null,
        LineConnection|array|null $endConnection = null,
    ) {
        $this->lineFill = $this->arrayToObject(class: LineFill::class, var: $lineFill);
        $this->weight = $this->arrayToObject(class: Dimension::class, var: $weight);
        $this->dashStyle = $this->stringToEnum(enum: DashStyle::class, var: $dashStyle);
        $this->startArrow = $this->stringToEnum(enum: ArrowStyle::class, var: $startArrow);
        $this->endArrow = $this->stringToEnum(enum: ArrowStyle::class, var: $endArrow);
        $this->link = $this->arrayToObject(class: Link::class, var: $link);
        $this->startConnection = $this->arrayToObject(class: LineConnection::class, var: $startConnection);
        $this->endConnection = $this->arrayToObject(class: LineConnection::class, var: $endConnection);
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
