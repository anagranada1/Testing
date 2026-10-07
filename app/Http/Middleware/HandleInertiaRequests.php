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
     * @return array<int, array<string, mixed>>
     */
    private function buildNav(): array
    {
        $groups = [];

        foreach ((array) config('services', []) as $slug => $service) {
            if (! is_array($service) || ! isset($service['kind'])) {
                continue;
            }

            $kind = (string) $service['kind'];
            $title = (string) ($service['group'] ?? ($kind === 'pipeline' ? 'Pipelines' : 'Endpoints'));

            $groups[$title][] = [
                'type' => 'item',
                'label' => (string) ($service['label'] ?? $slug),
                'href' => '/forms/'.$slug,
            ];
        }

        return array_map(
            fn (string $title, array $items): array => [
                'type' => 'group',
                'title' => $title,
                'items' => $items,
            ],
            array_keys($groups),
            $groups,
        );
    }
}
