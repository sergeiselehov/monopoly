<?php

use App\Models\SupportRequestCategory;
use App\Models\SupportRequestStatus;
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
        Schema::create('support_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('theme');
            $table->text('description');
            $table->string('email');
            $table->string('telegram');
            $table->foreignIdFor(SupportRequestStatus::class)->default(1);
            $table->foreignIdFor(SupportRequestCategory::class);
            $table->softDeletes();
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
        Schema::dropIfExists('support_requests');
    }
};
