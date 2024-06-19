<?php

use App\Models\Currency;
use App\Models\PaySystem;
use App\Models\User;
use App\Models\WithdrawalStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('external_id');
            $table->foreignIdFor(User::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(WithdrawalStatus::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(PaySystem::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(Currency::class)->constrained()->restrictOnDelete();
            $table->decimal('amount');
            $table->decimal('commission')->nullable();
            $table->string('destination_card_number');
            $table->string('callback_url');
            $table->tinyInteger('is_success_callback')->nullable();
            $table->tinyInteger('callback_tries')->nullable();
            $table->dateTime('last_callback_at')->nullable();
            $table->unsignedInteger('bin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdrawal_payments');
    }
};
