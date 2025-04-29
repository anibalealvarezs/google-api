<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Text;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Text\Type;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#textelement
 */
class TextElement implements Jsonable
{
    public ?int $startIndex;
    public ?int $endIndex;
    public ParagraphMarker|array|null $paragraphMarker;
    public TextRun|array|null $textRun;
    public AutoText|array|null $autoText;
    
    public function __construct(
        ?int $startIndex = null,
        ?int $endIndex = null,
        ParagraphMarker|array|null $paragraphMarker = null,
        TextRun|array|null $textRun = null,
        AutoText|array|null $autoText = null,
    ) {
        $this->startIndex = $startIndex;
        $this->endIndex = $endIndex;
        $this->paragraphMarker = $this->arrayToObject(class: ParagraphMarker::class, var: $paragraphMarker);
        $this->textRun = $this->arrayToObject(class: TextRun::class, var: $textRun);
        $this->autoText = $this->arrayToObject(class: AutoText::class, var: $autoText);

        $this->keepOneOfKind([
            'paragraphMarker',
            'textRun',
            'autoText',
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
