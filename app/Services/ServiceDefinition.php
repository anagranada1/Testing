<?php

namespace App\Services;

final class ServiceDefinition
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        public readonly string $slug,
        private readonly array $config,
    ) {
    }

    public function label(): string
    {
        return (string) ($this->config['label'] ?? $this->slug);
    }

    public function description(): string
    {
        return (string) ($this->config['description'] ?? '');
    }

    public function basePath(): string
    {
        return (string) ($this->config['base_path'] ?? '');
    }

    public function baseUrl(): ?string
    {
        $value = trim((string) ($this->config['base_url'] ?? ''));

        return $value !== '' ? rtrim($value, '/') : null;
    }

    public function isMultiStep(): bool
    {
        return (bool) ($this->config['multi_step'] ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function versions(): array
    {
        return is_array($this->config['versions'] ?? null)
            ? $this->config['versions']
            : [];
    }

    /**
     * @return array<int, string>
     */
    public function versionKeys(): array
    {
        return array_map('strval', array_keys($this->versions()));
    }

    public function basePathFor(string $version): string
    {
        $versionData = $this->versions()[$version] ?? [];

        return (string) ($versionData['base_path'] ?? $this->basePath());
    }

    /**
     * @return array<string, string>
     */
    public function basePathByVersion(): array
    {
        $result = [];

        foreach ($this->versionKeys() as $version) {
            $result[$version] = $this->basePathFor($version);
        }

        return $result;
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function stepsByVersion(): array
    {
        $result = [];

        foreach ($this->versions() as $version => $versionData) {
            $steps = is_array($versionData['steps'] ?? null) ? $versionData['steps'] : [];

            $normalized = [];
            foreach ($steps as $id => $step) {
                if (! is_array($step)) {
                    continue;
                }

                $normalized[] = [
                    'id' => $id,
                    'label' => $step['label'] ?? $id,
                    'method' => $step['method'] ?? 'POST',
                    'path' => $step['path'] ?? '',
                    'presets' => $step['presets'] ?? [],
                    'browser_redirect' => $step['browser_redirect'] ?? null,
                ];
            }

            $result[(string) $version] = $normalized;
        }

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function presetsByVersion(): ?array
    {
        return $this->versions() ?: null;
    }

    public function targetUrl(string $baseUrl, string $version, string $pathSuffix): string
    {
        return rtrim($baseUrl, '/').$this->basePathFor($version).'/'.$version.$pathSuffix;
    }
}
