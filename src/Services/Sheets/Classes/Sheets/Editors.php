<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#editors
 */
class Editors implements Jsonable
{
    public array $users;
    public array $groups;
    public bool $domainUsersCanEdit;
    
    public function __construct(
        array $users,
        array $groups,
        bool $domainUsersCanEdit = true,
    ) {
        $this->users = $users;
        $this->groups = $groups;
        $this->domainUsersCanEdit = $domainUsersCanEdit;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
