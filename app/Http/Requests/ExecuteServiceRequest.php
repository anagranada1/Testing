<?php

namespace App\Http\Requests;

use App\Services\ServiceDefinition;
use App\Services\ServiceRepository;
use Illuminate\Foundation\Http\FormRequest;

class ExecuteServiceRequest extends FormRequest
{
    private ?ServiceDefinition $definition = null;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'method' => ['required', 'string', 'in:GET,POST,PUT,PATCH,DELETE'],
            'version' => ['required', 'string', 'in:'.implode(',', $this->definition()->versionKeys())],
            'pathSuffix' => ['nullable', 'string', 'max:1000'],
            'baseUrl' => ['nullable', 'string', 'max:500'],
            'headers' => ['array'],
            'headers.*.key' => ['nullable', 'string'],
            'headers.*.value' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ];
    }

    public function definition(): ServiceDefinition
    {
        return $this->definition ??= app(ServiceRepository::class)
            ->find((string) $this->route('slug'));
    }

    public function targetMethod(): string
    {
        return (string) $this->validated('method');
    }

    public function version(): string
    {
        return (string) $this->validated('version');
    }

    public function pathSuffix(): string
    {
        return (string) ($this->validated('pathSuffix') ?? '');
    }

    public function body(): ?string
    {
        return $this->validated('body');
    }

    public function baseUrlOverride(): ?string
    {
        $value = $this->validated('baseUrl');

        return filled($value) ? $value : null;
    }

    /**
     * @return array<string, string>
     */
    public function headerMap(): array
    {
        return collect($this->validated('headers') ?? [])
            ->filter(fn ($row) => filled($row['key'] ?? null))
            ->mapWithKeys(fn ($row) => [$row['key'] => $row['value'] ?? ''])
            ->all();
    }
}
