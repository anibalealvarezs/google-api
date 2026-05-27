<?php

namespace Anibalealvarezs\GoogleApi\Services\BusinessInformation;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BusinessInformationApi extends GoogleApi
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
            baseUrl: "https://mybusinessbusinessinformation.googleapis.com/v1/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: Helpers::parseScopes($scopes, ["https://www.googleapis.com/auth/business.manage"]),
            token: $token,
            guzzleClient: $guzzleClient,
            tokenPath: $tokenPath,
            tokenIdentifier: $tokenIdentifier,
            logger: $logger,
            tokenRefresherCallback: $tokenRefresherCallback,
        );
    }

    /**
     * Lists business accounts accessible by the authenticated user.
     *
     * @return array
     * @throws GuzzleException
     */
    public function getAccounts(): array
    {
        $baseUrl = $this->getBaseUrl();
        $this->setBaseUrl('https://mybusinessaccountmanagement.googleapis.com/v1/');

        try {
            $response = $this->performRequest(
                method: "GET",
                endpoint: "accounts",
            );
            $result = json_decode($response->getBody()->getContents(), true);
        } finally {
            $this->setBaseUrl($baseUrl);
        }

        return $result ?: [];
    }

    /**
     * Lists locations for a specific account.
     *
     * @param string $accountName Format: accounts/{accountId}
     * @param string $readMask Fields to return in locations (comma separated list)
     * @return array
     * @throws GuzzleException
     */
    public function getLocations(string $accountName, string $readMask = 'name,title,storeCode,latlng,websiteUrl,phoneNumbers'): array
    {
        $response = $this->performRequest(
            method: "GET",
            endpoint: $accountName . "/locations",
            query: [
                'readMask' => $readMask,
            ]
        );

        return json_decode($response->getBody()->getContents(), true) ?: [];
    }
}
