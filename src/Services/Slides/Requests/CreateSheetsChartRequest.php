<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Presentations\Request\PageElementProperties;
use Chmw\GoogleApi\Services\Slides\Enums\Presentations\Request\LinkingMode;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#createsheetschartrequest
 */
class CreateSheetsChartRequest implements Jsonable
{
    public PageElementProperties|array $elementProperties;
    public string $spreadsheetId;
    public int $chartId;
    public LinkingMode|string $linkingMode;
    public ?string $objectId;
    
    public function __construct(
        PageElementProperties|array $elementProperties,
        string $spreadsheetId,
        int $chartId,
        LinkingMode|string $linkingMode = LinkingMode::LINKED,
        ?string $objectId = null,
    ) {
        $this->elementProperties = $this->arrayToObject(class: PageElementProperties::class, var: $elementProperties);
        $this->spreadsheetId = $spreadsheetId;
        $this->chartId = $chartId;
        $this->linkingMode = $this->stringToEnum(enum: LinkingMode::class, var: $linkingMode);
        $this->objectId = $objectId;
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
