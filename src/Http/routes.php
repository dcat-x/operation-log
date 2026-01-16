<?php

declare(strict_types=1);

use Dcat\Admin\OperationLog\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;

Route::get('auth/operation-logs', [LogController::class, 'index'])->name('dcat-admin.operation-log.index');
Route::delete('auth/operation-logs/{id}', [LogController::class, 'destroy'])->name('dcat-admin.operation-log.destroy');
