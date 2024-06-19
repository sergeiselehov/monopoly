<?php

use App\Models\Bank;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bank_bins', function (Blueprint $table) {
            $table->id();
            $table->string('bin');
            $table->string('bank_name');
            $table->foreignIdFor(Bank::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_bins');
    }
};
