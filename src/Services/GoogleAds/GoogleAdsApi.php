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
        $this->developerToken = $developerToken;
        $this->loginCustomerId = $loginCustomerId;

        parent::__construct(
            baseUrl: "https://googleads.googleapis.com/v17/",
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
     * Fetches all results from a Google Ads search by automatically handling pagination.
     *
     * @param string $customerId
     * @param string $query
     * @param int $pageSize
     * @return array
     * @throws GuzzleException
     */
    public function searchAll(string $customerId, string $query, int $pageSize = 1000): array
    {
        $results = [];
        $pageToken = null;

        do {
            $payload = [
                "query" => $query,
                "pageSize" => $pageSize,
            ];
            if ($pageToken) {
                $payload["pageToken"] = $pageToken;
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
            $pageToken = $data['nextPageToken'] ?? null;
        } while ($pageToken);

        $data['results'] = $results;
        unset($data['nextPageToken']);
        return $data;
    }

    /**
     * Fetches all results from a Google Ads search and processes them with a callback.
     *
     * @param string $customerId
     * @param string $query
     * @param callable $callback
     * @param int $pageSize
     * @return void
     * @throws GuzzleException
     */
    public function searchAllAndProcess(string $customerId, string $query, callable $callback, int $pageSize = 1000): void
    {
        $pageToken = null;

        do {
            $payload = [
                "query" => $query,
                "pageSize" => $pageSize,
            ];
            if ($pageToken) {
                $payload["pageToken"] = $pageToken;
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
            $pageToken = $data['nextPageToken'] ?? null;
        } while ($pageToken);
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getAccessibleCustomers(): array
    {
        $response = $this->performRequest(
            method: "GET",
            endpoint: "customers:listAccessibleCustomers"
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
}
