<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Sheets\SheetProperties;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#sheet
 */
class Sheet implements Jsonable
{
    public SheetProperties|array $properties;
    public array $data;
    public array $merges;
    public array $conditionalFormats;
    public array $filterViews;
    public array $protectedRanges;
    public BasicFilter|array $basicFilter;
    public array $charts;
    public array $bandedRanges;
    public array $developerMetadata;
    public array $rowGroups;
    public array $columnGroups;
    public array $slicers;
    
    public function __construct(
        SheetProperties|array $properties,
        array $data,
        array $merges,
        array $conditionalFormats,
        array $filterViews,
        array $protectedRanges,
        BasicFilter|array $basicFilter,
        array $charts,
        array $bandedRanges,
        array $developerMetadata,
        array $rowGroups,
        array $columnGroups,
        array $slicers
    ) {
        $this->properties = $this->arrayToObject(class: SheetProperties::class, var: $properties);
        $this->data = $data;
        $this->merges = $merges;
        $this->conditionalFormats = $conditionalFormats;
        $this->filterViews = $filterViews;
        $this->protectedRanges = $protectedRanges;
        $this->basicFilter = $this->arrayToObject(class: BasicFilter::class, var: $basicFilter);
        $this->charts = $charts;
        $this->bandedRanges = $bandedRanges;
        $this->developerMetadata = $developerMetadata;
        $this->rowGroups = $rowGroups;
        $this->columnGroups = $columnGroups;
        $this->slicers = $slicers;
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
