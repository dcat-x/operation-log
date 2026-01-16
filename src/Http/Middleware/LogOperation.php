<?php

declare(strict_types=1);

namespace Dcat\Admin\OperationLog\Http\Middleware;

use Closure;
use Dcat\Admin\Admin;
use Dcat\Admin\OperationLog\Models\OperationLog as OperationLogModel;
use Dcat\Admin\OperationLog\OperationLogServiceProvider;
use Dcat\Admin\Support\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogOperation
{
    protected array $secretFields = [
        'password',
        'password_confirmation',
    ];

    protected array $except = [
        'dcat-admin.operation-log.*',
    ];

    protected array $defaultAllowedMethods = [
        'GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldLogOperation($request)) {
            $user = Admin::user();

            $log = [
                'user_id' => $user ? $user->id : 0,
                'path' => substr($request->path(), 0, 255),
                'method' => $request->method(),
                'ip' => $request->getClientIp(),
                'input' => $this->formatInput($request->input()),
            ];

            try {
                OperationLogModel::create($log);
            } catch (\Exception) {
                // pass
            }
        }

        return $next($request);
    }

    protected function formatInput(array $input): string
    {
        foreach ($this->getSecretFields() as $field) {
            if ($field && ! empty($input[$field])) {
                $input[$field] = Str::limit($input[$field], 3, '******');
            }
        }

        return json_encode($input, JSON_UNESCAPED_UNICODE);
    }

    protected function setting(string $key, mixed $default = null): mixed
    {
        return OperationLogServiceProvider::setting($key, $default);
    }

    protected function shouldLogOperation(Request $request): bool
    {
        return ! $this->inExceptArray($request)
            && $this->inAllowedMethods($request->method());
    }

    protected function inAllowedMethods(string $method): bool
    {
        $allowedMethods = collect($this->getAllowedMethods())->filter();

        if ($allowedMethods->isEmpty()) {
            return true;
        }

        return $allowedMethods->map(fn ($method) => strtoupper($method))->contains($method);
    }

    protected function inExceptArray(Request $request): bool
    {
        if ($request->routeIs(admin_api_route_name('value'))) {
            return true;
        }

        foreach ($this->except() as $except) {
            if ($request->routeIs($except)) {
                return true;
            }

            $except = admin_base_path($except);

            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if (Helper::matchRequestPath($except)) {
                return true;
            }
        }

        return false;
    }

    protected function except(): array
    {
        return array_merge((array) $this->setting('except'), $this->except);
    }

    protected function getSecretFields(): array
    {
        return array_merge((array) $this->setting('secret_fields'), $this->secretFields);
    }

    protected function getAllowedMethods(): array
    {
        return (array) ($this->setting('allowed_methods') ?: $this->defaultAllowedMethods);
    }
}
