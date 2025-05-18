<?php

namespace Anibalealvarezs\GoogleApi\Services\BigQuery\Classes\Datasets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://cloud.google.com/bigquery/docs/reference/rest/v2/datasets#datasetreference
 * @param string $datasetId
 * @param string|null $projectId
 * @return DatasetReference
 */
class DatasetReference implements Jsonable
{
    public string $datasetId;
    public string $projectId;

    public function __construct(
        string $datasetId,
        ?string $projectId = null,
    ) {
        $this->datasetId = $datasetId;
        $this->projectId = $projectId;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
