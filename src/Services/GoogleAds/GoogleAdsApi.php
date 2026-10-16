<?php

namespace Anibalealvarezs\GoogleApi\Services\GoogleAds;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GoogleAdsApi extends GoogleApi
{
    protected string $developerToken;
    protected ?string $loginCustomerId;

    /**
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
     * @param string $developerToken
     * @param string|null $loginCustomerId
     * @param string|array $scopes
     * @param string $token
     * @param Client|null $guzzleClient
     * @param string $tokenPath
     * @throws Exception
     */
    public function __construct(
        string $redirectUrl,
        string $clientId,
        string $clientSecret,
        string $refreshToken,
        string $userId,
        string $developerToken,
        ?string $loginCustomerId = null,
        string|array $scopes = [],
        string $token = "",
        ?Client $guzzleClient = null,
        string $tokenPath = "",
        string $tokenIdentifier = "",
        ?\Psr\Log\LoggerInterface $logger = null,
        mixed $tokenRefresherCallback = null
    ) {
        if (trim($developerToken) === '') {
            throw new \InvalidArgumentException(
                "A Google Ads developer token is required. Obtain a developer token from the Google Ads Manager Account (MCC) API Center or provide it via configuration."
            );
        }

        $this->developerToken = $developerToken;
        $this->loginCustomerId = $loginCustomerId;

        parent::__construct(
            baseUrl: "https://googleads.googleapis.com/v26/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: Helpers::parseScopes($scopes, ["https://www.googleapis.com/auth/adwords"]),
            token: $token,
            guzzleClient: $guzzleClient,
            tokenPath: $tokenPath,
            tokenIdentifier: $tokenIdentifier,
            logger: $logger,
            tokenRefresherCallback: $tokenRefresherCallback,
        );

        $headers = $this->getHeaders();
        $headers["developer-token"] = $this->developerToken;
        if ($this->loginCustomerId) {
            $headers["login-customer-id"] = $this->loginCustomerId;
        }
        $this->setHeaders($headers);

        $this->setErrorMessageParser(function ($data) {
            if (isset($data['error']['details'][0]['errors'][0]['message'])) {
                return $data['error']['details'][0]['errors'][0]['message'];
            }
            if (isset($data['error']['message'])) {
                return $data['error']['message'];
            }
            return json_encode($data);
        });
    }

    public function setLoginCustomerId(?string $loginCustomerId): void
    {
        $this->loginCustomerId = $loginCustomerId;
        $headers = $this->getHeaders();
        if ($loginCustomerId) {
            $headers["login-customer-id"] = $loginCustomerId;
        } else {
            unset($headers["login-customer-id"]);
        }
        $this->setHeaders($headers);
    }

    public function getLoginCustomerId(): ?string
    {
        return $this->loginCustomerId;
    }

    /**
     * @param string $customerId
     * @param string $query
     * @param bool $validateOnly
     * @return array
     * @throws GuzzleException
     * @link https://developers.google.com/google-ads/api/reference/rest/v17/customers/googleAds:search
     */
    public function search(string $customerId, string $query, bool $validateOnly = false): array
    {
        $payload = [
            "query" => $query,
            "validateOnly" => $validateOnly,
        ];

        $response = $this->performRequest(
            method: "POST",
            endpoint: "customers/" . $customerId . "/googleAds:search",
            body: json_encode($payload),
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $customerId
     * @param string $query
     * @return array
     * @throws GuzzleException
     * @link https://developers.google.com/google-ads/api/reference/rest/v17/customers/googleAds:searchStream
     */
    public function searchStream(string $customerId, string $query): array
    {
        $payload = [
            "query" => $query,
        ];

        $response = $this->performRequest(
            method: "POST",
            endpoint: "customers/" . $customerId . "/googleAds:searchStream",
            body: json_encode($payload),
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Updates the query payload with the current pagination pageToken and guards against infinite loops.
     *
     * @param array $payload The request payload array to update.
     * @param string|null $nextPageToken The incoming nextPageToken.
     * @param string|null $prevPageToken The previous pageToken.
     * @return bool False if an infinite loop is detected (current token same as previous), true otherwise.
     */
    protected function updatePaginationQuery(array &$payload, ?string $nextPageToken, ?string &$prevPageToken): bool
    {
        if (!$nextPageToken) {
            unset($payload['pageToken']);
            return true;
        }

        if ($nextPageToken === $prevPageToken) {
            return false;
        }

        $prevPageToken = $nextPageToken;
        $payload['pageToken'] = $nextPageToken;

        return true;
    }

    /**
     * Fetches all results from a Google Ads search by automatically handling pagination.
     *
     * @param string $customerId
     * @param string $query
     * @param int $pageSize
     * @param callable|null $shouldContinue
     * @return array
     * @throws GuzzleException
     */
    public function searchAll(string $customerId, string $query, int $pageSize = 1000, ?callable $shouldContinue = null): array
    {
        $results = [];
        $nextPageToken = null;
        $prevPageToken = null;

        do {
            if ($shouldContinue && !$shouldContinue()) {
                break;
            }

            $payload = [
                "query" => $query,
                "pageSize" => $pageSize,
            ];

            if (!$this->updatePaginationQuery($payload, $nextPageToken, $prevPageToken)) {
                break;
            }

            $response = $this->performRequest(
                method: "POST",
                endpoint: "customers/" . $customerId . "/googleAds:search",
                body: json_encode($payload),
            );

            $data = json_decode($response->getBody()->getContents(), true);
            if (!empty($data['results'])) {
                $results = array_merge($results, $data['results']);
            }
            $nextPageToken = $data['nextPageToken'] ?? null;
        } while ($nextPageToken);

        $data['results'] = $results;
        unset($data['nextPageToken']);
        return $data;
    }

    /**
     * Fetches all results from a Google Ads search and processes them batch-by-batch with a callback.
     *
     * @param string $customerId
     * @param string $query
     * @param callable $callback
     * @param int $pageSize
     * @param callable|null $shouldContinue
     * @return void
     * @throws GuzzleException
     */
    public function searchAllAndProcess(
        string $customerId,
        string $query,
        callable $callback,
        int $pageSize = 1000,
        ?callable $shouldContinue = null
    ): void {
        $nextPageToken = null;
        $prevPageToken = null;

        do {
            if ($shouldContinue && !$shouldContinue()) {
                break;
            }

            $payload = [
                "query" => $query,
                "pageSize" => $pageSize,
            ];

            if (!$this->updatePaginationQuery($payload, $nextPageToken, $prevPageToken)) {
                break;
            }

            $response = $this->performRequest(
                method: "POST",
                endpoint: "customers/" . $customerId . "/googleAds:search",
                body: json_encode($payload),
            );

            $data = json_decode($response->getBody()->getContents(), true);
            if (!empty($data['results'])) {
                $callback($data['results']);
            }
            $nextPageToken = $data['nextPageToken'] ?? null;
        } while ($nextPageToken);
    }

    /**
     * Executes a searchStream request against Google Ads and passes each stream batch array to the callback.
     *
     * @param string $customerId
     * @param string $query
     * @param callable $callback
     * @param callable|null $shouldContinue
     * @return void
     * @throws GuzzleException
     */
    public function searchStreamAndProcess(
        string $customerId,
        string $query,
        callable $callback,
        ?callable $shouldContinue = null
    ): void {
        if ($shouldContinue && !$shouldContinue()) {
            return;
        }

        $payload = [
            "query" => $query,
        ];

        $response = $this->performRequest(
            method: "POST",
            endpoint: "customers/" . $customerId . "/googleAds:searchStream",
            body: json_encode($payload),
        );

        $data = json_decode($response->getBody()->getContents(), true);
        if (is_array($data)) {
            // searchStream returns a JSON array of batch objects: [{"results": [...]}, ...]
            foreach ($data as $batch) {
                if ($shouldContinue && !$shouldContinue()) {
                    break;
                }
                if (!empty($batch['results']) && is_array($batch['results'])) {
                    $callback($batch['results']);
                }
            }
        }
    }

    /**
     * Overrides exception handling to catch Google Ads rate limit & quota failures.
     *
     * @param Exception $exception
     * @param mixed $onFailure
     * @return mixed
     * @throws Exception
     */
    protected function handleException(Exception $exception, mixed $onFailure = null): mixed
    {
        $normalized = \Anibalealvarezs\GoogleApi\Google\Support\GoogleErrorClassifier::normalize($exception);
        if (\Anibalealvarezs\GoogleApi\Google\Support\GoogleErrorClassifier::isQuotaExceeded($exception)) {
            $retryAfter = 60;
            $prev = $exception->getPrevious();
            if ($prev instanceof \GuzzleHttp\Exception\RequestException && $prev->hasResponse()) {
                $resp = $prev->getResponse();
                if ($resp->hasHeader('retry-after')) {
                    $retryAfter = (int)$resp->getHeaderLine('retry-after');
                }
            }

            throw new \Anibalealvarezs\GoogleApi\Services\GoogleAds\Exceptions\GoogleAdsRateLimitException(
                message: $exception->getMessage(),
                code: $exception->getCode() ?: 429,
                previous: $exception,
                developerToken: $this->developerToken,
                retryAfterSeconds: $retryAfter,
                adsErrors: $normalized['ads_errors'] ?? []
            );
        }

        return parent::handleException($exception, $onFailure);
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getAccessibleCustomers(): array
    {
        $headers = $this->getHeaders();
        unset($headers['login-customer-id']);

        $response = $this->performRequest(
            method: "GET",
            endpoint: "customers:listAccessibleCustomers",
            headers: $headers
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $customerId
     * @return array
     * @throws GuzzleException
     */
    public function getCustomerClients(string $customerId): array
    {
        $query = "SELECT customer_client.client_customer, customer_client.hidden, customer_client.level, customer_client.time_zone, customer_client.test_account, customer_client.descriptive_name, customer_client.currency_code, customer_client.id FROM customer_client WHERE customer_client.level <= 1";
        return $this->searchAll($customerId, $query);
    }

    /**
     * @param string $customerId
     * @param string $fields
     * @param string $where
     * @return array
     * @throws GuzzleException
     */
    public function getCampaigns(string $customerId, string $fields = "campaign.id, campaign.name, campaign.status", string $where = ""): array
    {
        $query = "SELECT {$fields} FROM campaign";
        if ($where) {
            $query .= " WHERE {$where}";
        }
        return $this->searchAll($customerId, $query);
    }

    /**
     * @param string $customerId
     * @param string $fields
     * @param string $where
     * @return array
     * @throws GuzzleException
     */
    public function getAdGroups(string $customerId, string $fields = "ad_group.id, ad_group.name, ad_group.status, campaign.id", string $where = ""): array
    {
        $query = "SELECT {$fields} FROM ad_group";
        if ($where) {
            $query .= " WHERE {$where}";
        }
        return $this->searchAll($customerId, $query);
    }

    /**
     * @param string $customerId
     * @param string $fields
     * @param string $where
     * @return array
     * @throws GuzzleException
     */
    public function getAds(string $customerId, string $fields = "ad_group_ad.ad.id, ad_group_ad.ad.name, ad_group_ad.status, ad_group.id, campaign.id", string $where = ""): array
    {
        $query = "SELECT {$fields} FROM ad_group_ad";
        if ($where) {
            $query .= " WHERE {$where}";
        }
        return $this->searchAll($customerId, $query);
    }

    /**
     * @param string $customerId
     * @param string $resource
     * @param string $startDate
     * @param string $endDate
     * @param string $fields
     * @param string $where
     * @return array
     * @throws GuzzleException
     */
    public function getMetrics(string $customerId, string $resource, string $startDate, string $endDate, string $fields, string $where = ""): array
    {
        $query = "SELECT {$fields} FROM {$resource} WHERE segments.date >= '{$startDate}' AND segments.date <= '{$endDate}'";
        if ($where) {
            $query .= " AND {$where}";
        }
        return $this->searchAll($customerId, $query);
    }

    /**
     * Helper to fetch metrics for a specific hierarchy level with segments.
     *
     * @param string $customerId
     * @param string $level 'customer' | 'campaign' | 'ad_group' | 'ad'
     * @param string $startDate
     * @param string $endDate
     * @param array $metrics
     * @param array $segments
     * @param string $where
     * @return array
     * @throws GuzzleException
     */
    public function getMetricsForLevel(
        string $customerId,
        string $level,
        string $startDate,
        string $endDate,
        array $metrics,
        array $segments = ['segments.date', 'segments.device'],
        string $where = ""
    ): array {
        $resource = match ($level) {
            'campaign' => 'campaign',
            'ad_group', 'adset' => 'ad_group',
            'ad', 'creative' => 'ad_group_ad',
            default => 'customer',
        };

        $baseFields = match ($level) {
            'campaign' => ['campaign.id', 'campaign.name'],
            'ad_group', 'adset' => ['campaign.id', 'ad_group.id', 'ad_group.name'],
            'ad', 'creative' => ['campaign.id', 'ad_group.id', 'ad_group_ad.ad.id', 'ad_group_ad.ad.name'],
            default => ['customer.id', 'customer.descriptive_name'],
        };

        $allFields = array_unique(array_merge($baseFields, $segments, $metrics));
        $fieldsStr = implode(', ', $allFields);

        return $this->getMetrics(
            customerId: $customerId,
            resource: $resource,
            startDate: $startDate,
            endDate: $endDate,
            fields: $fieldsStr,
            where: $where
        );
    }
}
