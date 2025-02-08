<?php

namespace Anibalealvarezs\GoogleApi\Services\Drive\Classes\Drives;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/drive/api/v3/reference/drives#resource
 */
class Restrictions implements Jsonable
{
    public bool $adminManagedRestrictions;
    public bool $copyRequiresWriterPermission;
    public bool $domainUsersOnly;
    public bool $driveMembersOnly;
    
    public function __construct(
        bool $adminManagedRestrictions = false,
        bool $copyRequiresWriterPermission = false,
        bool $domainUsersOnly = false,
        bool $driveMembersOnly = false
    ) {
        $this->adminManagedRestrictions = $adminManagedRestrictions;
        $this->copyRequiresWriterPermission = $copyRequiresWriterPermission;
        $this->domainUsersOnly = $domainUsersOnly;
        $this->driveMembersOnly = $driveMembersOnly;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
