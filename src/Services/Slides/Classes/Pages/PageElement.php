<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Charts\SheetsChart;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Images\Image;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Lines\Line;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\AffineTransform;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Shapes\Shape;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables\Table;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Videos\Video;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Size;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#pageelement
 */
class PageElement implements Jsonable
{
    public string $objectId;
    public Size|array|null $size;
    public AffineTransform|array|null $transform;
    public ?string $title;
    public ?string $description;
    public Group|array|null $elementGroup;
    public Shape|array|null $shape;
    public Image|array|null $image;
    public Video|array|null $video;
    public Line|array|null $line;
    public Table|array|null $table;
    public WordArt|array|null $wordArt;
    public SheetsChart|array|null $sheetsChart;
    
    public function __construct(
        string $objectId,
        Size|array|null $size = null,
        AffineTransform|array|null $transform = null,
        ?string $title = null,
        ?string $description = null,
        Group|array|null $elementGroup = null,
        Shape|array|null $shape = null,
        Image|array|null $image = null,
        Video|array|null $video = null,
        Line|array|null $line = null,
        Table|array|null $table = null,
        WordArt|array|null $wordArt = null,
        SheetsChart|array|null $sheetsChart = null
    ) {
        $this->objectId = $objectId;
        $this->size = $this->arrayToObject(class: Size::class, var: $size);
        $this->transform = $this->arrayToObject(class: AffineTransform::class, var: $transform);
        $this->title = $title;
        $this->description = $description;
        $this->elementGroup = $this->arrayToObject(class: Group::class, var: $elementGroup);
        $this->shape = $this->arrayToObject(class: Shape::class, var: $shape);
        $this->image = $this->arrayToObject(class: Image::class, var: $image);
        $this->video = $this->arrayToObject(class: Video::class, var: $video);
        $this->line = $this->arrayToObject(class: Line::class, var: $line);
        $this->table = $this->arrayToObject(class: Table::class, var: $table);
        $this->wordArt = $this->arrayToObject(class: WordArt::class, var: $wordArt);
        $this->sheetsChart = $this->arrayToObject(class: SheetsChart::class, var: $sheetsChart);
        
        $this->keepOneOfKind([
            'elementGroup',
            'shape',
            'image',
            'video',
            'line',
            'table',
            'wordArt',
            'sheetsChart',
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
