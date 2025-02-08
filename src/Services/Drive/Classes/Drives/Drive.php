<?php

namespace Anibalealvarezs\GoogleApi\Services\Drive\Classes\Drives;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use DateTime;

/**
 * @see https://developers.google.com/drive/api/v3/reference/drives#resource
 */
class Drive implements Jsonable
{
    public string $kind;
    public string $id;
    public string $name;
    public string $themeId;
    public string $colorRgb;
    public BackgroundImageFile|array|null $backgroundImageFile;
    public string $backgroundImageLink;
    public Capabilities|array|null $capabilities;
    public DateTime $createdTime;
    public string $orgUnitId;
    public bool $hidden;
    public Restrictions|array|null $restrictions;
    
    public function __construct(
        string $kind = 'drive#drive',
        string $id = '',
        string $name = '',
        string $themeId = '',
        string $colorRgb = '',
        BackgroundImageFile|array|null $backgroundImageFile = null,
        string $backgroundImageLink = '',
        Capabilities|array|null $capabilities = null,
        ?DateTime $createdTime = null,
        string $orgUnitId = '',
        bool $hidden = false,
        Restrictions|array|null $restrictions = null
    ) {
        $this->kind = $kind;
        $this->id = $id;
        $this->name = $name;
        $this->themeId = $themeId;
        $this->colorRgb = $colorRgb;
        $this->backgroundImageFile = $this->arrayToObject(class: BackgroundImageFile::class, var: $backgroundImageFile);
        $this->backgroundImageLink = $backgroundImageLink;
        $this->capabilities = $this->arrayToObject(class: Capabilities::class, var: $capabilities);
        $this->createdTime = $createdTime;
        $this->orgUnitId = $orgUnitId;
        $this->hidden = $hidden;
        $this->restrictions = $this->arrayToObject(class: Restrictions::class, var: $restrictions);
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
