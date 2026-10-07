<?php

namespace App\Services;

final class ExecutionResult
{
    /**
     * @param  array<string, array<int, string>>|null  $headers
     */
    private function __construct(
        public readonly string $targetUrl,
        public readonly ?int $status,
        public readonly ?array $headers,
        public readonly ?string $body,
        public readonly ?string $error,
    ) {
    }

    /**
     * @param  array<string, array<int, string>>  $headers
     */
    public static function success(string $targetUrl, int $status, array $headers, string $body): self
    {
        return new self($targetUrl, $status, $headers, $body, null);
    }

    public static function failure(string $targetUrl, string $error): self
    {
        return new self($targetUrl, null, null, null, $error);
    }

    public function failed(): bool
    {
        return $this->error !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        if ($this->failed()) {
            return [
                'targetUrl' => $this->targetUrl,
                'error' => $this->error,
            ];
        }

        return [
            'targetUrl' => $this->targetUrl,
            'status' => $this->status,
            'headers' => $this->headers,
            'body' => $this->body,
        ];
    }
}
