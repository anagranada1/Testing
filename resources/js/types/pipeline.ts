export interface PresetData {
    headers: Record<string, string>;
    body: string;
}

export interface BrowserRedirect {
    url: string;
    params: Record<string, string>;
}

export interface StepConfig {
    id: string;
    label: string;
    method: string;
    path: string;
    note: string;
    presets: Record<string, PresetData>;
    browser_redirect: BrowserRedirect | null;
}

export interface ExecutionResult {
    targetUrl?: string;
    status?: number;
    body?: string;
    headers?: Record<string, string[]>;
    error?: string;
    parsed?: unknown;
}

export const RETURN_MESSAGE_SOURCE = 'copper-api:redirect-return';

export interface ReturnMessage {
    source: typeof RETURN_MESSAGE_SOURCE;
    slug: string;
    stepId: string;
    params: Record<string, string>;
}
