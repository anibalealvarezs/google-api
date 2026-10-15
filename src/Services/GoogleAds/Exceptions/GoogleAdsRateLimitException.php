<?php

declare(strict_types=1);

namespace Anibalealvarezs\GoogleApi\Services\GoogleAds\Exceptions;

use Anibalealvarezs\GoogleApi\Google\Exceptions\GoogleQuotaExceededException;
use Throwable;

class GoogleAdsRateLimitException extends GoogleQuotaExceededException
{
    protected ?string $customerId;
    protected ?string $developerToken;
    protected int $retryAfterSeconds;
    protected array $adsErrors;

    public function __construct(
        string $message = "Google Ads rate limit or quota exceeded.",
        int $code = 429,
        ?Throwable $previous = null,
        ?string $customerId = null,
        ?string $developerToken = null,
        int $retryAfterSeconds = 60,
        array $adsErrors = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->customerId = $customerId;
        $this->developerToken = $developerToken;
        $this->retryAfterSeconds = $retryAfterSeconds;
        $this->adsErrors = $adsErrors;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function getDeveloperToken(): ?string
    {
        return $this->developerToken;
    }

    public function getRetryAfterSeconds(): int
    {
        return $this->retryAfterSeconds;
    }

    public function getAdsErrors(): array
    {
        return $this->adsErrors;
    }
}
