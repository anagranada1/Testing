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
     *   Servicios
     *     └── <servicio> (collapsible)
     *           ├── <step 1> → /forms/<slug>/steps/<id>
     *           ├── <step 2>
     *           └── …
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
        $services = (array) config('services.forms.services', []);

        $unitaryServices = [];
        $pipelines = [];

        foreach ($services as $slug => $service) {
            if (! is_array($service)) {
                continue;
            }

            $slug = (string) $slug;
            $label = (string) ($service['label'] ?? $slug);
            $kind = (string) ($service['kind'] ?? 'endpoint');

            $unitaryServices[] = [
                'type' => 'group',
                'title' => $label,
                'items' => $this->stepLeavesFor($slug, $service),
            ];

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
                'title' => 'Servicios',
                'items' => $unitaryServices,
            ],
            [
                'type' => 'group',
                'title' => 'Pipelines',
                'items' => $pipelines,
            ],
        ];
    }

    /**
     * Devuelve los pasos de un servicio (de la primera versión declarada)
     * como hojas listas para el sidebar.
     *
     * @param  array<string, mixed>  $service
     * @return array<int, array{type: string, label: string, href: string}>
     */
    private function stepLeavesFor(string $slug, array $service): array
    {
        $versions = is_array($service['versions'] ?? null) ? $service['versions'] : [];
        $firstVersion = reset($versions);
        $firstVersion = is_array($firstVersion) ? $firstVersion : [];
        $steps = is_array($firstVersion['steps'] ?? null) ? $firstVersion['steps'] : [];

        $leaves = [];
        foreach ($steps as $stepId => $step) {
            if (! is_array($step)) {
                continue;
            }
            $leaves[] = [
                'type' => 'item',
                'label' => (string) ($step['label'] ?? $stepId),
                'href' => '/forms/' . $slug . '/steps/' . (string) $stepId,
            ];
        }

        // Si el servicio no es multi-step, lo exponemos como un único enlace
        // al recurso completo.
        if ($leaves === [] && ! ($service['multi_step'] ?? false)) {
            $leaves[] = [
                'type' => 'item',
                'label' => (string) ($service['label'] ?? $slug),
                'href' => '/forms/' . $slug,
            ];
        }

        return $leaves;
    }
}
