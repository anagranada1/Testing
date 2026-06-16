<?php

namespace App;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ServiceHelper
{
    public static function serviceConfig(string $slug): array
    {
        $config = config('services.'.$slug);
        abort_unless(is_array($config), HttpResponse::HTTP_NOT_FOUND);

        return $config;
    }

    public static function basePathByVersion(array $config): array
    {
        $serviceLevel = (string) ($config['base_path'] ?? '');
        $result = [];
        foreach ($config['versions'] ?? [] as $versionKey => $versionData) {
            $result[(string) $versionKey] = (string) (
                $versionData['base_path'] ?? $serviceLevel
            );
        }

        return $result;
    }

    public static function stepsByVersion(array $versions): array
    {
        $result = [];
        foreach ($versions as $versionKey => $versionData) {
            $steps = [];
            foreach ($versionData['steps'] ?? [] as $stepId => $step) {
                $steps[] = [
                    'id' => $stepId,
                    'label' => $step['label'] ?? $stepId,
                    'method' => $step['method'] ?? 'POST',
                    'path' => $step['path'] ?? '',
                    'presets' => $step['presets'] ?? [],
                    'browser_redirect' => $step['browser_redirect'] ?? null,
                ];
            }
            $result[$versionKey] = $steps;
        }

        return $result;
    }
}
