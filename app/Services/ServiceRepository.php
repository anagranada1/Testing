<?php

namespace App\Services;

use Illuminate\Contracts\Config\Repository as Config;
use Symfony\Component\HttpFoundation\Response;

class ServiceRepository
{
    public function __construct(private readonly Config $config)
    {
    }

    public function find(string $slug): ServiceDefinition
    {
        $config = $this->config->get('services.'.$slug);

        abort_unless(is_array($config), Response::HTTP_NOT_FOUND);

        return new ServiceDefinition($slug, $config);
    }

    public function defaultBaseUrl(): string
    {
        return rtrim((string) $this->config->get('services.base_url'), '/');
    }
}
