<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExecuteServiceRequest;
use App\Services\ServiceExecutor;
use App\Services\ServiceFormPresenter;
use App\Services\ServiceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ServiceFormController
{
    public function __construct(private readonly ServiceRepository $services) {}

    public function show(string $slug, ServiceFormPresenter $presenter): Response
    {
        $definition = $this->services->find($slug);

        return Inertia::render(
            'Forms/Service',
            $presenter->present($definition, $this->services->defaultBaseUrl()),
        );
    }

    public function execute(ExecuteServiceRequest $request, string $slug, ServiceExecutor $executor): JsonResponse
    {
        $definition = $request->definition();

        $baseUrl = $request->baseUrlOverride()
            ?? $definition->baseUrl()
            ?? $this->services->defaultBaseUrl();
        $targetUrl = $definition->targetUrl($baseUrl, $request->version(), $request->pathSuffix());

        $result = $executor->execute(
            $request->targetMethod(),
            $targetUrl,
            $request->headerMap(),
            $request->body(),
        );

        return response()->json(
            $result->toArray(),
            $result->failed() ? HttpResponse::HTTP_BAD_GATEWAY : HttpResponse::HTTP_OK,
        );
    }

    public function redirectReturn(Request $request, string $slug): Response
    {
        $definition = $this->services->find($slug);

        return Inertia::render('Forms/Return', [
            'slug' => $definition->slug,
            'label' => $definition->label(),
            'method' => $request->method(),
            'params' => array_map(
                static fn ($value): string => is_scalar($value) ? (string) $value : json_encode($value),
                $request->except('_token'),
            ),
        ]);
    }

    public function finalUrl(Request $request)
    {
        logger()->debug('FinalUrl request received', [
            'request' => $request->all(),
        ]);

        return Inertia::render('Welcome');
    }

    public function url(Request $request)
    {
        logger()->debug('Url request received', [
            'request' => $request->all(),
        ]);

        return Inertia::render('Welcome');
    }
}
