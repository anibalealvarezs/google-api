<?php

namespace Anibalealvarezs\GoogleApi\Services\BigQuery\Classes\Query;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\BigQuery\Classes\ConnectionProperty;
use Anibalealvarezs\GoogleApi\Services\BigQuery\Classes\DataFormatOptions;
use Anibalealvarezs\GoogleApi\Services\BigQuery\Classes\Datasets\DatasetReference;
use Anibalealvarezs\GoogleApi\Services\BigQuery\Classes\QueryParameter;
use Anibalealvarezs\GoogleApi\Services\BigQuery\Enums\ParameterMode;

/**
 * @see https://cloud.google.com/bigquery/docs/reference/rest/v2/jobs/query#queryrequest
 * @param string $kind
 * @param string $query
 * @param QueryParameter[] $queryParameters
 * @param int|null $maxResults
 * @param DatasetReference|null $defaultDataset
 * @param int|null $timeoutMs
 * @param bool $dryRun
 * @param bool|null $useQueryCache
 * @param bool $useLegacySql
 * @param ParameterMode $parameterMode
 * @param string $location
 * @param DataFormatOptions|array|null $formatOptions
 * @param ConnectionProperty[]|array|null $connectionProperties
 * @param array|null $labels
 * @param string|null $maximumBytesBilled
 * @param string|null $requestId
 * @param bool $createSession
 * @return QueryRequest
 */
class QueryRequest implements Jsonable
{
    public string $kind;
    public string $query;
    public array $queryParameters;
    public ?int $maxResults;
    public ?DatasetReference $defaultDataset;
    public ?int $timeoutMs;
    public bool $dryRun;
    public bool $useQueryCache;
    public bool $useLegacySql;
    public ParameterMode $parameterMode;
    public string $location;
    public DataFormatOptions|array|null $formatOptions;
    public ?array $connectionProperties;
    public ?array $labels;
    public ?string $maximumBytesBilled; // Int64Value: https://developers.google.com/discovery/v1/type-format
    public ?string $requestId; // A unique case-sensitive user provided identifier limited to up to 36 ASCII characters. UUID recommended
    public ?bool $createSession;

    public function __construct(
        string $kind,
        string $query,
        array $queryParameters = [],
        ?int $maxResults = null,
        ?DatasetReference $defaultDataset = null,
        ?int $timeoutMs = null,
        bool $dryRun = false,
        bool $useQueryCache = false, // "true" for actually running the query and "false" for getting statistics
        bool $useLegacySql = false, // "true" for using legacy SQL and "false" for using GoogleSQL and bypass "flattenResults"
        ParameterMode $parameterMode = ParameterMode::POSITIONAL,
        string $location = 'us-central1',
        ?DataFormatOptions $formatOptions = null,
        ?array $connectionProperties = null,
        ?array $labels = null,
        ?string $maximumBytesBilled = null,
        ?string $requestId = null,
        ?bool $createSession = null, // "true" for creating a new session and "false" for using an existing one provided in $connectionProperties
        // If ignored, it will run in non-session mode
    ) {
        $this->kind = $kind;
        $this->query = $query;
        // Format QueryParameters
        $formattedQueryParameters = [];
        if ($queryParameters) {
            foreach ($queryParameters as $queryParameter) {
                if (!($queryParameter instanceof QueryParameter)) {
                    $formattedQueryParameters[] = new QueryParameter(...$queryParameter);
                } else {
                    $formattedQueryParameters[] = $queryParameter;
                }
            }
        }
        $this->queryParameters = $formattedQueryParameters;
        $this->maxResults = $maxResults;
        $this->defaultDataset = $this->arrayToObject(class: DatasetReference::class, var: $defaultDataset);
        $this->timeoutMs = $timeoutMs;
        $this->dryRun = $dryRun;
        $this->useQueryCache = $useQueryCache;
        $this->useLegacySql = $useLegacySql;
        $this->parameterMode = $parameterMode;
        $this->location = $location;
        $this->formatOptions = $this->arrayToObject(class: DataFormatOptions::class, var: $formatOptions);
        // Format ConnectionProperties
        $formattedConnectionProperties = [];
        if ($connectionProperties) {
            foreach ($connectionProperties as $connectionProperty) {
                if (!($connectionProperty instanceof ConnectionProperty)) {
                    $formattedConnectionProperties[] = new ConnectionProperty(...$connectionProperty);
                } else {
                    $formattedConnectionProperties[] = $connectionProperty;
                }
            }
        }
        $this->connectionProperties = $formattedConnectionProperties;
        $this->labels = $labels;
        $this->maximumBytesBilled = $maximumBytesBilled;
        $this->requestId = $requestId;
        $this->createSession = $createSession;
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
