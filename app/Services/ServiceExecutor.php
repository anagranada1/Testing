<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpClient;
use Throwable;

class ServiceExecutor
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    /**
     * @param  array<string, string>  $headers
     */
    public function execute(string $method, string $targetUrl, array $headers, ?string $body): ExecutionResult
    {
        $client = $this->http->withHeaders($headers);

        if (filled($body)) {
            $client = $client->withBody(
                $body,
                $headers['Content-Type'] ?? 'application/json',
            );
        }

        try {
            $response = $client->send($method, $targetUrl);

            return ExecutionResult::success(
                $targetUrl,
                $response->status(),
                $response->headers(),
                $response->body(),
            );
        } catch (Throwable $e) {
            return ExecutionResult::failure($targetUrl, $e->getMessage());
        }
    }
}
