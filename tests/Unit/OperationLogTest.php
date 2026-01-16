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
    });
});

describe('LogOperation Middleware', function (): void {
    test('class exists', function (): void {
        expect(class_exists(LogOperation::class))->toBeTrue();
    });

    test('has handle method', function (): void {
        $reflection = new ReflectionClass(LogOperation::class);
        expect($reflection->hasMethod('handle'))->toBeTrue();
    });

    test('has protected secret fields', function (): void {
        $reflection = new ReflectionClass(LogOperation::class);
        $property = $reflection->getProperty('secretFields');

        expect($property->isProtected())->toBeTrue();
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
    });
});

describe('LogController', function (): void {
    test('class exists', function (): void {
        expect(class_exists(LogController::class))->toBeTrue();
    });

    test('has index method', function (): void {
        $reflection = new ReflectionClass(LogController::class);
        expect($reflection->hasMethod('index'))->toBeTrue();
    });

    test('has destroy method', function (): void {
        $reflection = new ReflectionClass(LogController::class);
        expect($reflection->hasMethod('destroy'))->toBeTrue();
    });
});
