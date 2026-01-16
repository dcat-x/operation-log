<?php

declare(strict_types=1);

use Dcat\Admin\OperationLog\Http\Controllers\LogController;
use Dcat\Admin\OperationLog\Http\Middleware\LogOperation;
use Dcat\Admin\OperationLog\Models\OperationLog;
use Dcat\Admin\OperationLog\OperationLogServiceProvider;
use Dcat\Admin\OperationLog\Setting;

describe('OperationLog Model', function (): void {
    test('class exists', function (): void {
        expect(class_exists(OperationLog::class))->toBeTrue();
    });

    test('extends Eloquent Model', function (): void {
        $reflection = new ReflectionClass(OperationLog::class);
        expect($reflection->getParentClass()->getName())->toBe('Illuminate\Database\Eloquent\Model');
    });

    test('has expected static properties', function (): void {
        expect(OperationLog::$methodColors)->toBeArray();
        expect(OperationLog::$methods)->toBeArray();
    });

    test('method colors contains expected methods', function (): void {
        expect(OperationLog::$methodColors)->toHaveKeys(['GET', 'POST', 'PUT', 'DELETE']);
    });

    test('methods contains common HTTP methods', function (): void {
        expect(OperationLog::$methods)->toContain('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH');
    });

    test('method colors have valid values', function (): void {
        expect(OperationLog::$methodColors['GET'])->toBe('primary');
        expect(OperationLog::$methodColors['POST'])->toBe('success');
        expect(OperationLog::$methodColors['PUT'])->toBe('blue');
        expect(OperationLog::$methodColors['DELETE'])->toBe('danger');
    });

    test('has user relationship method', function (): void {
        $reflection = new ReflectionClass(OperationLog::class);
        expect($reflection->hasMethod('user'))->toBeTrue();

        $method = $reflection->getMethod('user');
        expect($method->getReturnType()->getName())->toBe('Illuminate\Database\Eloquent\Relations\BelongsTo');
    });

    test('has fillable attributes', function (): void {
        $reflection = new ReflectionClass(OperationLog::class);
        $property = $reflection->getProperty('fillable');

        $model = $reflection->newInstanceWithoutConstructor();
        $property->setAccessible(true);
        $fillable = $property->getValue($model);

        expect($fillable)->toContain('user_id', 'path', 'method', 'ip', 'input');
    });
});

describe('OperationLogServiceProvider', function (): void {
    test('class exists', function (): void {
        expect(class_exists(OperationLogServiceProvider::class))->toBeTrue();
    });

    test('extends ServiceProvider', function (): void {
        $reflection = new ReflectionClass(OperationLogServiceProvider::class);
        expect($reflection->getParentClass()->getName())->toBe('Dcat\Admin\Extend\ServiceProvider');
    });

    test('has settingForm method', function (): void {
        $reflection = new ReflectionClass(OperationLogServiceProvider::class);
        expect($reflection->hasMethod('settingForm'))->toBeTrue();

        $method = $reflection->getMethod('settingForm');
        expect($method->getReturnType()->getName())->toBe('Dcat\Admin\OperationLog\Setting');
    });

    test('registers middleware', function (): void {
        $reflection = new ReflectionClass(OperationLogServiceProvider::class);
        $property = $reflection->getProperty('middleware');
        $property->setAccessible(true);

        $provider = $reflection->newInstanceWithoutConstructor();
        $middleware = $property->getValue($provider);

        expect($middleware)->toHaveKey('middle');
        expect($middleware['middle'])->toContain(LogOperation::class);
    });

    test('registers menu', function (): void {
        $reflection = new ReflectionClass(OperationLogServiceProvider::class);
        $property = $reflection->getProperty('menu');
        $property->setAccessible(true);

        $provider = $reflection->newInstanceWithoutConstructor();
        $menu = $property->getValue($provider);

        expect($menu)->toBeArray();
        expect($menu[0])->toHaveKeys(['title', 'uri']);
        expect($menu[0]['uri'])->toBe('auth/operation-logs');
    });
});

describe('LogOperation Middleware', function (): void {
    test('class exists', function (): void {
        expect(class_exists(LogOperation::class))->toBeTrue();
    });

    test('has handle method with correct signature', function (): void {
        $reflection = new ReflectionClass(LogOperation::class);
        expect($reflection->hasMethod('handle'))->toBeTrue();

        $method = $reflection->getMethod('handle');
        $parameters = $method->getParameters();

        expect($parameters)->toHaveCount(2);
        expect($parameters[0]->getType()->getName())->toBe('Illuminate\Http\Request');
        expect($parameters[1]->getType()->getName())->toBe('Closure');
    });

    test('has protected secret fields with default values', function (): void {
        $reflection = new ReflectionClass(LogOperation::class);
        $property = $reflection->getProperty('secretFields');
        $property->setAccessible(true);

        $middleware = $reflection->newInstanceWithoutConstructor();
        $secretFields = $property->getValue($middleware);

        expect($secretFields)->toBeArray();
        expect($secretFields)->toContain('password', 'password_confirmation');
    });

    test('has protected except routes', function (): void {
        $reflection = new ReflectionClass(LogOperation::class);
        $property = $reflection->getProperty('except');
        $property->setAccessible(true);

        $middleware = $reflection->newInstanceWithoutConstructor();
        $except = $property->getValue($middleware);

        expect($except)->toBeArray();
        expect($except)->toContain('dcat-admin.operation-log.*');
    });

    test('has default allowed methods', function (): void {
        $reflection = new ReflectionClass(LogOperation::class);
        $property = $reflection->getProperty('defaultAllowedMethods');
        $property->setAccessible(true);

        $middleware = $reflection->newInstanceWithoutConstructor();
        $methods = $property->getValue($middleware);

        expect($methods)->toBeArray();
        expect($methods)->toContain('GET', 'POST', 'PUT', 'DELETE', 'PATCH');
    });

    test('formatInput method exists and is protected', function (): void {
        $reflection = new ReflectionClass(LogOperation::class);
        $method = $reflection->getMethod('formatInput');

        expect($method->isProtected())->toBeTrue();
        expect($method->getReturnType()->getName())->toBe('string');
    });

    test('getSecretFields method exists', function (): void {
        $reflection = new ReflectionClass(LogOperation::class);
        expect($reflection->hasMethod('getSecretFields'))->toBeTrue();

        $method = $reflection->getMethod('getSecretFields');
        expect($method->isProtected())->toBeTrue();
        expect($method->getReturnType()->getName())->toBe('array');
    });

    test('getAllowedMethods method exists', function (): void {
        $reflection = new ReflectionClass(LogOperation::class);
        expect($reflection->hasMethod('getAllowedMethods'))->toBeTrue();

        $method = $reflection->getMethod('getAllowedMethods');
        expect($method->isProtected())->toBeTrue();
        expect($method->getReturnType()->getName())->toBe('array');
    });

    test('shouldLogOperation method exists', function (): void {
        $reflection = new ReflectionClass(LogOperation::class);
        expect($reflection->hasMethod('shouldLogOperation'))->toBeTrue();

        $method = $reflection->getMethod('shouldLogOperation');
        expect($method->isProtected())->toBeTrue();
        expect($method->getReturnType()->getName())->toBe('bool');
    });

    test('inAllowedMethods method exists', function (): void {
        $reflection = new ReflectionClass(LogOperation::class);
        expect($reflection->hasMethod('inAllowedMethods'))->toBeTrue();

        $method = $reflection->getMethod('inAllowedMethods');
        expect($method->isProtected())->toBeTrue();
        expect($method->getReturnType()->getName())->toBe('bool');
    });
});

describe('Setting', function (): void {
    test('class exists', function (): void {
        expect(class_exists(Setting::class))->toBeTrue();
    });

    test('extends Form', function (): void {
        $reflection = new ReflectionClass(Setting::class);
        expect($reflection->getParentClass()->getName())->toBe('Dcat\Admin\Extend\Setting');
    });

    test('has required methods', function (): void {
        $reflection = new ReflectionClass(Setting::class);
        expect($reflection->hasMethod('title'))->toBeTrue();
        expect($reflection->hasMethod('form'))->toBeTrue();
        expect($reflection->hasMethod('formatInput'))->toBeTrue();
    });

    test('formatInput handles missing keys', function (): void {
        $reflection = new ReflectionClass(Setting::class);
        $method = $reflection->getMethod('formatInput');
        $method->setAccessible(true);

        $setting = $reflection->newInstanceWithoutConstructor();

        $input = [];
        $result = $method->invoke($setting, $input);

        expect($result)->toHaveKey('except');
        expect($result)->toHaveKey('allowed_methods');
        expect($result['except'])->toBeArray();
        expect($result['allowed_methods'])->toBeArray();
    });
});

describe('LogController', function (): void {
    test('class exists', function (): void {
        expect(class_exists(LogController::class))->toBeTrue();
    });

    test('has index method with correct return type', function (): void {
        $reflection = new ReflectionClass(LogController::class);
        expect($reflection->hasMethod('index'))->toBeTrue();

        $method = $reflection->getMethod('index');
        expect($method->getReturnType()->getName())->toBe('Dcat\Admin\Layout\Content');
    });

    test('has destroy method with correct return type', function (): void {
        $reflection = new ReflectionClass(LogController::class);
        expect($reflection->hasMethod('destroy'))->toBeTrue();

        $method = $reflection->getMethod('destroy');
        expect($method->getReturnType()->getName())->toBe('Dcat\Admin\Http\JsonResponse');
    });

    test('has protected grid method', function (): void {
        $reflection = new ReflectionClass(LogController::class);
        expect($reflection->hasMethod('grid'))->toBeTrue();

        $method = $reflection->getMethod('grid');
        expect($method->isProtected())->toBeTrue();
        expect($method->getReturnType()->getName())->toBe('Dcat\Admin\Grid');
    });
});
