<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\PivotTables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#manualrule
 * @param ManualRuleGroup[] $groups
 * @return ManualRule
 */
class ManualRule implements Jsonable
{
    public array $groups;
    
    public function __construct(
        array $groups,
    ) {
        // Format Groups
        $formattedGroups = [];
        if ($groups) {
            foreach ($groups as $group) {
                if (!($group instanceof ManualRuleGroup)) {
                    $formattedGroups[] = new ManualRuleGroup(...$group);
                } else {
                    $formattedGroups[] = $group;
                }
            }
        }
        $this->groups = $formattedGroups;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
