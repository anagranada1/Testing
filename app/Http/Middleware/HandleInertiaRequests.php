<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'nav' => $this->buildNav(),
        ];
    }

    /**
     * Build the sidebar navigation as a 2-level tree:
     *
     *   Pipelines
     *     └── <servicio> → /forms/<slug>
     *
     * Cada nodo lleva un campo `type` ('group' | 'item') que el front usa
     * para decidir si renderizar otra sección colapsable o un link plano.
     *
     * @return array<int, array<string, mixed>>
     */
    private function buildNav(): array
    {
        $services = (array) config('services', []);

        $pipelines = [];

        foreach ($services as $slug => $service) {
            if (! is_array($service)) {
                continue;
            }

            $slug = (string) $slug;
            $label = (string) ($service['label'] ?? $slug);
            $kind = (string) ($service['kind'] ?? 'endpoint');

            if ($kind === 'pipeline') {
                $pipelines[] = [
                    'type' => 'item',
                    'label' => $label,
                    'href' => '/forms/' . $slug,
                ];
            }
        }

        return [
            [
                'type' => 'group',
                'title' => 'Pipelines',
                'items' => $pipelines,
            ],
        ];
    }
}
