<?php

namespace Anibalealvarezs\GoogleApi\Services\Drive\Classes\Drives;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/drive/api/v3/reference/drives#resource
 */
class Capabilities implements Jsonable
{
    public bool $canAddChildren = false;
    public bool $canChangeCopyRequiresWriterPermissionRestriction = false;
    public bool $canChangeDomainUsersOnlyRestriction = false;
    public bool $canChangeDriveBackground = false;
    public bool $canChangeDriveMembersOnlyRestriction = false;
    public bool $canComment = false;
    public bool $canCopy = false;
    public bool $canDeleteChildren = false;
    public bool $canDeleteDrive = false;
    public bool $canDownload = false;
    public bool $canEdit = false;
    public bool $canListChildren = false;
    public bool $canManageMembers = false;
    public bool $canReadRevisions = false;
    public bool $canRename = false;
    public bool $canRenameDrive = false;
    public bool $canResetDriveRestrictions = false;
    public bool $canShare = false;
    public bool $canTrashChildren = false;
    
    public function __construct(
        bool $canAddChildren = false,
        bool $canChangeCopyRequiresWriterPermissionRestriction = false,
        bool $canChangeDomainUsersOnlyRestriction = false,
        bool $canChangeDriveBackground = false,
        bool $canChangeDriveMembersOnlyRestriction = false,
        bool $canComment = false,
        bool $canCopy = false,
        bool $canDeleteChildren = false,
        bool $canDeleteDrive = false,
        bool $canDownload = false,
        bool $canEdit = false,
        bool $canListChildren = false,
        bool $canManageMembers = false,
        bool $canReadRevisions = false,
        bool $canRename = false,
        bool $canRenameDrive = false,
        bool $canResetDriveRestrictions = false,
        bool $canShare = false,
        bool $canTrashChildren = false
    ) {
        $this->canAddChildren = $canAddChildren;
        $this->canChangeCopyRequiresWriterPermissionRestriction = $canChangeCopyRequiresWriterPermissionRestriction;
        $this->canChangeDomainUsersOnlyRestriction = $canChangeDomainUsersOnlyRestriction;
        $this->canChangeDriveBackground = $canChangeDriveBackground;
        $this->canChangeDriveMembersOnlyRestriction = $canChangeDriveMembersOnlyRestriction;
        $this->canComment = $canComment;
        $this->canCopy = $canCopy;
        $this->canDeleteChildren = $canDeleteChildren;
        $this->canDeleteDrive = $canDeleteDrive;
        $this->canDownload = $canDownload;
        $this->canEdit = $canEdit;
        $this->canListChildren = $canListChildren;
        $this->canManageMembers = $canManageMembers;
        $this->canReadRevisions = $canReadRevisions;
        $this->canRename = $canRename;
        $this->canRenameDrive = $canRenameDrive;
        $this->canResetDriveRestrictions = $canResetDriveRestrictions;
        $this->canShare = $canShare;
        $this->canTrashChildren = $canTrashChildren;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
