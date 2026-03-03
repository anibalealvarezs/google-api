<?php

namespace Anibalealvarezs\GoogleApi\Google;

use Anibalealvarezs\ApiSkeleton\Clients\OAuthV2Client;
use Exception;
use GuzzleHttp\Client;

class GoogleApi extends OAuthV2Client
{
    protected string $tokenPath = "";

    /**
     * @param string $baseUrl
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
     * @param array $scopes
     * @param string $token
     * @param Client|null $guzzleClient
     * @param string $tokenPath
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
        string $token = "",
        ?Client $guzzleClient = null,
        string $tokenPath = ""
    ) {
        $this->tokenPath = $tokenPath;

        // Load token from storage if not provided
        if (!$token && $this->tokenPath && file_exists($this->tokenPath)) {
            $data = json_decode(json: (string) file_get_contents($this->tokenPath), associative: true);
            $serviceKey = $this->getServiceKey();
            if (isset($data[$userId][$serviceKey])) {
                $token = $data[$userId][$serviceKey];
            }
        }

        parent::__construct(
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
            guzzleClient: $guzzleClient,
        );
    }

    /**
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        parent::setToken($token);

        if ($this->tokenPath && $token) {
            $this->persistToken($token);
        }
    }

    /**
     * @param string $token
     * @return void
     */
    protected function persistToken(string $token): void
    {
        $data = [];
        if (file_exists($this->tokenPath)) {
            $data = json_decode(json: (string) file_get_contents($this->tokenPath), associative: true) ?: [];
        }

        $userId = $this->getUserId();
        if (!isset($data[$userId]) || !is_array($data[$userId])) {
            $data[$userId] = [];
        }

        $data[$userId][$this->getServiceKey()] = $token;

        // Ensure directory exists
        $dir = dirname($this->tokenPath);
        if (!is_dir($dir)) {
            mkdir(directory: $dir, permissions: 0755, recursive: true);
        }

        file_put_contents(filename: $this->tokenPath, data: json_encode(value: $data, flags: JSON_PRETTY_PRINT));
    }

    /**
     * @return string
     */
    protected function getServiceKey(): string
    {
        return str_replace('Anibalealvarezs\\GoogleApi\\', '', get_class($this));
    }
}
