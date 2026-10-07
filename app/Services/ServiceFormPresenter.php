<?php

namespace App\Services;

class ServiceFormPresenter
{
    /**
     * @return array<string, mixed>
     */
    public function present(ServiceDefinition $definition, string $defaultBaseUrl): array
    {
        $multiStep = $definition->isMultiStep();

        return [
            'slug' => $definition->slug,
            'label' => $definition->label(),
            'description' => $definition->description(),
            'defaultBaseUrl' => $definition->baseUrl() ?? $defaultBaseUrl,
            'basePath' => $definition->basePath(),
            'basePathByVersion' => $definition->basePathByVersion(),
            'multiStep' => $multiStep,
            'versions' => $definition->versionKeys(),
            'defaults' => $multiStep ? null : $definition->presetsByVersion(),
            'steps' => $multiStep ? $definition->stepsByVersion() : null,
            'initialMethod' => null,
            'initialPath' => null,
        ];
    }
}
