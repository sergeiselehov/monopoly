<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pay_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('base_url',255)->nullable();
            $table->string('app_key',255)->nullable();
            $table->string('secret_key',255)->nullable();
            $table->decimal('percent');
            $table->decimal('input_percent');
            $table->decimal('output_percent');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pay_systems');
    }
};
