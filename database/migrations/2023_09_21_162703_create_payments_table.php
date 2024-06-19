<?php

use App\Models\Currency;
use App\Models\Merchant;
use App\Models\PaymentStatus;
use App\Models\PaySystem;
use App\Models\User;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('external_id')->nullable();
            $table->foreignIdFor(PaySystem::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(Currency::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(User::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(PaymentStatus::class)->constrained()->restrictOnDelete();

            $table->unsignedDecimal('amount',15,2)->nullable();
            $table->unsignedDecimal('old_amount',15,2)->nullable();
            $table->string('callback_url')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('form_url')->nullable();
            $table->string('email')->nullable();
            $table->string('customer_card_number')->nullable();
            $table->string('dest_card_number')->nullable();

            $table->string('merchant_user_id')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();

            $table->index(['user_id']);
            $table->index(['merchant_user_id']);
            $table->index(['external_id']);
            $table->index(['pay_system_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
