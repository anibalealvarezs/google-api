<?php

namespace Anibalealvarezs\GoogleApi\Services\AnalyticsAdmin;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class AnalyticsAdminApi extends GoogleApi
{
    /**
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
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
        string|array $scopes = [],
        string $token = "",
        ?Client $guzzleClient = null,
        string $tokenPath = "",
        string $tokenIdentifier = "",
        ?\Psr\Log\LoggerInterface $logger = null,
        mixed $tokenRefresherCallback = null
    ) {
        parent::__construct(
            baseUrl: "https://analyticsadmin.googleapis.com/v1beta/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: Helpers::parseScopes($scopes, ["https://www.googleapis.com/auth/analytics.readonly"]),
            token: $token,
            guzzleClient: $guzzleClient,
            tokenPath: $tokenPath,
            tokenIdentifier: $tokenIdentifier,
            logger: $logger,
            tokenRefresherCallback: $tokenRefresherCallback,
        );
    }

    /**
     * Lists all AccountSummaries which includes the accounts and their properties.
     *
     * @return array
     * @throws GuzzleException
     * @link https://developers.google.com/analytics/devguides/config/admin/v1/rest/v1beta/accountSummaries/list
     */
    public function listAccountSummaries(): array
    {
        $response = $this->performRequest(
            method: "GET",
            endpoint: "accountSummaries"
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Helper to get a flat list of properties accessible to the user.
     *
     * @return array
     * @throws GuzzleException
     */
    public function getProperties(): array
    {
        $summaries = $this->listAccountSummaries();
        $properties = [];
        
        if (!empty($summaries['accountSummaries'])) {
            foreach ($summaries['accountSummaries'] as $accountSummary) {
                if (!empty($accountSummary['propertySummaries'])) {
                    foreach ($accountSummary['propertySummaries'] as $propertySummary) {
                        $propertySummary['account'] = $accountSummary['account'];
                        $propertySummary['accountDisplayName'] = $accountSummary['displayName'];
                        $properties[] = $propertySummary;
                    }
                }
            }
        }
        
        return $properties;
    }
}
