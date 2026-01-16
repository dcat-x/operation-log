<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function getConnection(): ?string
    {
        return config('database.connection') ?: config('database.default');
    }

    public function up(): void
    {
        if (! Schema::hasTable('admin_operation_log')) {
            Schema::create('admin_operation_log', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('path');
                $table->string('method', 10);
                $table->string('ip', 45);
                $table->text('input');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_operation_log');
    }
};
