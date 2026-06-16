<?php

namespace App\Http\Controllers;

use App\ServiceHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ServiceFormController
{
    public function show(string $slug): Response
    {
        $config = ServiceHelper::serviceConfig($slug);

        $defaultBaseUrl = rtrim(config('services.base_url'), '/');
        $multiStep = (bool) ($config['multi_step'] ?? false);

        return Inertia::render('Forms/Service', [
            'slug' => $slug,
            'label' => ($config['label'] ?? $slug),
            'description' => ($config['description'] ?? ''),
            'defaultBaseUrl' => $defaultBaseUrl,
            'basePath' => ($config['base_path'] ?? ''),
            'basePathByVersion' => ServiceHelper::basePathByVersion($config),
            'multiStep' => $multiStep,
            'versions' => array_keys($config['versions'] ?? []),
            'defaults' => $multiStep ? null : ($config['versions'] ?? null),
            'steps' => $multiStep ? ServiceHelper::stepsByVersion($config['versions'] ?? []) : null,
            'initialMethod' => null,
            'initialPath' => null,
        ]);
    }

    public function execute(Request $request, string $slug): JsonResponse
    {
        $config = ServiceHelper::serviceConfig($slug);

        $payload = $request->validate([
            'method' => ['required', 'string', 'in:GET,POST,PUT,PATCH,DELETE'],
            'version' => ['required', 'string', 'in:'.implode(',', array_keys($config['versions'] ?? []))],
            'pathSuffix' => ['nullable', 'string', 'max:1000'],
            'baseUrl' => ['nullable', 'string', 'max:500'],
            'headers' => ['array'],
            'headers.*.key' => ['nullable', 'string'],
            'headers.*.value' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ]);

        $headers = collect($payload['headers'] ?? [])
            ->filter(fn ($row) => filled($row['key'] ?? null))
            ->mapWithKeys(fn ($row) => [$row['key'] => $row['value'] ?? ''])
            ->all();

        $baseUrl = rtrim(
            filled($payload['baseUrl'] ?? null)
                ? $payload['baseUrl']
                : config('services.base_url'),
            '/'
        );
        $version = $payload['version'];
        $basePath = (
            $config['versions'][$version]['base_path']
                ?? $config['base_path']
                ?? ''
        );
        $pathSuffix = ($payload['pathSuffix'] ?? '');

        $targetUrl = $baseUrl.$basePath.'/'.$version.$pathSuffix;

        $client = Http::withHeaders($headers);

        if (filled($payload['body'] ?? null)) {
            $client = $client->withBody(
                $payload['body'],
                $headers['Content-Type'] ?? 'application/json'
            );
        }

        try {
            $response = $client->send($payload['method'], $targetUrl);

            return response()->json([
                'targetUrl' => $targetUrl,
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'targetUrl' => $targetUrl,
                'error' => $e->getMessage(),
            ], HttpResponse::HTTP_BAD_GATEWAY);
        }
    }
}
