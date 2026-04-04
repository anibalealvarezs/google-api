<?php

namespace Anibalealvarezs\GoogleApi\Services\Recaptcha;

use Anibalealvarezs\ApiSkeleton\Clients\ApiKeyClient;
use GuzzleHttp\Exception\GuzzleException;

class RecaptchaApi extends ApiKeyClient
{
    protected string $projectId;

    /**
     * @param string $projectId
     * @param string $apiKey
     */
    public function __construct(
        string $projectId,
        string $apiKey
    ) {
        $this->projectId = $projectId;

        parent::__construct(
            baseUrl: "https://recaptchaenterprise.googleapis.com/v1/",
            apiKey: $apiKey,
            authSettings: [
                'location' => 'query',
                'name' => 'key',
            ],
            defaultHeaders: [
                "Content-Type" => "application/json",
            ]
        );
    }

    /**
     * Create an assessment for a reCAPTCHA token.
     *
     * @param string $token
     * @param string $siteKey
     * @param string $expectedAction
     * @return array
     * @throws GuzzleException
     */
    public function createAssessment(string $token, string $siteKey, string $expectedAction = ""): array
    {
        $endpoint = "projects/{$this->projectId}/assessments";
        
        $body = [
            'event' => [
                'token' => $token,
                'siteKey' => $siteKey,
                'expectedAction' => $expectedAction,
            ],
        ];

        $response = $this->performRequest(
            method: "POST",
            endpoint: $endpoint,
            body: json_encode($body)
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Verify a reCAPTCHA token and return if it is valid (score >= threshold).
     *
     * @param string $token
     * @param string $siteKey
     * @param string $expectedAction
     * @param float $threshold
     * @return bool
     * @throws GuzzleException
     */
    public function verifyToken(string $token, string $siteKey, string $expectedAction = "", float $threshold = 0.3): bool
    {
        $assessment = $this->createAssessment($token, $siteKey, $expectedAction);

        if (!isset($assessment['tokenProperties']['valid']) || !$assessment['tokenProperties']['valid']) {
            return false;
        }

        if ($expectedAction && isset($assessment['tokenProperties']['action']) && $assessment['tokenProperties']['action'] !== $expectedAction) {
            return false;
        }

        $score = $assessment['riskAnalysis']['score'] ?? 0.0;

        return $score >= $threshold;
    }
}
