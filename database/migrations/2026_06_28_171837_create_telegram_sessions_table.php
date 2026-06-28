<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('telegram_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->bigInteger('telegram_id')->unique();

            $table->string('telegram_username')->nullable();

            $table->string('access_token');

            $table->timestamp('last_login_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_sessions');
    }
};
