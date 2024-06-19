<?php

use App\Models\Payment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_user_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Payment::class)->constrained()->restrictOnDelete();
            $table->decimal('score');
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('ip_address_form')->nullable();
            $table->boolean('give_access')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_user_validations');
    }
};
