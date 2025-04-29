<?php

namespace Anibalealvarezs\GoogleApi\Services\SearchConsole;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use GuzzleHttp\Exception\GuzzleException;

class Data extends GoogleApi
{
    /**
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
     * @param array $scopes
     * @throws GuzzleException
     */
    public function __construct(
        string $redirectUrl,
        string $clientId,
        string $clientSecret,
        string $refreshToken,
        string $userId,
        array $scopes = []
    ) {
        parent::__construct(
            baseUrl: "https://analyticsdata.googleapis.com/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: ($scopes ?: ["https://www.googleapis.com/auth/analytics.readonly"]),
        );
    }
}
