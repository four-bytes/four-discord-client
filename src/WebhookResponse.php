<?php

declare(strict_types=1);

namespace Four\Discord;

use Psr\Http\Message\ResponseInterface;

class WebhookResponse
{
    private bool $success;
    private int $statusCode;
    private ?string $errorMessage;
    private ?ResponseInterface $response;

    public function __construct(bool $success, int $statusCode, ?string $errorMessage = null, ?ResponseInterface $response = null)
    {
        $this->success = $success;
        $this->statusCode = $statusCode;
        $this->errorMessage = $errorMessage;
        $this->response = $response;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function getResponseBody(): ?string
    {
        return $this->response ? (string) $this->response->getBody() : null;
    }

    public function getRateLimitReset(): ?int
    {
        if (!$this->response) {
            return null;
        }

        $resetHeader = $this->response->getHeader('X-RateLimit-Reset');
        return $resetHeader ? (int) $resetHeader[0] : null;
    }

    public function getRateLimitRemaining(): ?int
    {
        if (!$this->response) {
            return null;
        }

        $remainingHeader = $this->response->getHeader('X-RateLimit-Remaining');
        return $remainingHeader ? (int) $remainingHeader[0] : null;
    }
}