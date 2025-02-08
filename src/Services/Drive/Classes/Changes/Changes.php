<?php

namespace Anibalealvarezs\GoogleApi\Services\Drive\Classes\Changes;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Drive\Classes\Drives\Drive;
use Anibalealvarezs\GoogleApi\Services\Drive\Enums\ChangeType;
use DateTime;

/**
 * @see https://developers.google.com/drive/api/v3/reference/changes#resource
 */
class Changes implements Jsonable
{
    public string $kind;
    public ?string $fileId;
    public ?bool $removed;
    public ?DateTime $time;
    public ?object $file;
    public ChangeType|string $changeType;
    public ?string $driveId;
    public Drive|array|null $drive;
    
    public function __construct(
        string $kind = 'drive#change',
        ?string $fileId = null,
        ?bool $removed = null,
        ?DateTime $time = null,
        ?object $file = null,
        ChangeType|string $changeType = ChangeType::file,
        ?string $driveId = null,
        Drive|array|null $drive = null,
    ) {
        $this->kind = $kind;
        $this->fileId = $fileId;
        $this->removed = $removed;
        $this->time = $time;
        $this->file = $file;
        $this->changeType = $this->stringToEnum(enum: ChangeType::class, var: $changeType);
        $this->driveId = $driveId;
        $this->drive = $this->arrayToObject(class: Drive::class, var: $drive);
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
