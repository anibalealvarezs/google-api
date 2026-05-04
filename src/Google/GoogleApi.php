<?php

namespace Anibalealvarezs\GoogleApi\Google;

use Anibalealvarezs\ApiSkeleton\Clients\OAuthV2Client;
use Anibalealvarezs\GoogleApi\Google\Exceptions\GoogleQuotaExceededException;
use Anibalealvarezs\GoogleApi\Google\Support\GoogleErrorClassifier;
use Exception;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class GoogleApi extends OAuthV2Client
{
    protected string $tokenPath = "";
    protected string $tokenIdentifier = "";

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
     * @param string $tokenIdentifier
     * @param LoggerInterface|null $logger
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
        string $tokenPath = "",
        string $tokenIdentifier = "",
        ?\Psr\Log\LoggerInterface $logger = null
    ) {
        $this->tokenPath = $tokenPath;
        $this->tokenIdentifier = $tokenIdentifier ?: ($refreshToken ? 'RefreshToken_' . substr(md5($refreshToken), 0, 16) : "");

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
            logger: $logger,
        );

        $this->setResponseErrorDetector('error');
        $this->setErrorMessageParser(fn ($data) => $data['error']['message'] ?? json_encode($data));
        $this->setRateLimitDetector([
            'Quota',
            'quota',
            'Rate limit',
            'rate limit',
            'Too Many Requests',
            'too many requests',
            'RESOURCE_EXHAUSTED',
            'resource_exhausted',
            'Backend Error',
            'backend error',
            'temporarily unavailable',
            'UNAVAILABLE',
            'INTERNAL',
        ]);
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
        return $this->tokenIdentifier ?: str_replace('Anibalealvarezs\\GoogleApi\\', '', get_class($this));
    }

    /**
     * Keep retry detection semantic and resilient even if the base client detector
     * implementation differs across installed api-client-skeleton versions.
     */
    protected function isRateLimit(mixed $input): bool
    {
        if (GoogleErrorClassifier::isRetryable($input)) {
            return true;
        }

        return parent::isRateLimit($input);
    }

    /**
     * @param Exception $exception
     * @param mixed $onFailure
     * @return mixed
     * @throws Exception
     */
    protected function handleException(Exception $exception, mixed $onFailure = null): mixed
    {
        if (GoogleErrorClassifier::isQuotaExceeded($exception)) {
            throw new GoogleQuotaExceededException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return parent::handleException($exception, $onFailure);
    }
}
