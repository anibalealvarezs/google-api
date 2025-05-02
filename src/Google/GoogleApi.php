<?php

namespace Anibalealvarezs\GoogleApi\Google;

use Anibalealvarezs\ApiSkeleton\Clients\OAuthV2Client;
use Exception;

class GoogleApi extends OAuthV2Client
{
    /**
     * @param string $baseUrl
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
     * @param array $scopes
     * @param string $token
     * @throws Exception
     */
    public function __construct(
        string $baseUrl,
        string $redirectUrl,
        string $clientId,
        string $clientSecret,
        string $refreshToken,
        string $userId,
        array $scopes = [],
        string $token = ""
    ) {
        return parent::__construct(
            baseUrl: $baseUrl,
            authUrl: "https://accounts.google.com/o/oauth2/auth",
            tokenUrl: "https://oauth2.googleapis.com/token",
            refreshAuthUrl: "https://accounts.google.com/o/oauth2/auth?access_type=offline",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            authSettings: [
                'location' => 'header',
                'headerPrefix' => 'Bearer ',
            ],
            defaultHeaders: [
                "Content-Type" => "application/json",
            ],
            userId: $userId,
            scopes: $scopes,
            token: $token,
        );
    }
}
