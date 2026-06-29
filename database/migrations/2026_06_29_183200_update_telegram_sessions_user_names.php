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
        Schema::table('telegram_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('telegram_sessions', 'telegram_first_name')) {
                $table->string('telegram_first_name')->nullable()->after('telegram_username');
            }

            if (! Schema::hasColumn('telegram_sessions', 'telegram_last_name')) {
                $table->string('telegram_last_name')->nullable()->after('telegram_first_name');
            }

            if (Schema::hasColumn('telegram_sessions', 'access_token')) {
                $table->dropColumn('access_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('telegram_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('telegram_sessions', 'access_token')) {
                $table->string('access_token')->nullable()->after('telegram_username');
            }

            if (Schema::hasColumn('telegram_sessions', 'telegram_last_name')) {
                $table->dropColumn('telegram_last_name');
            }

            if (Schema::hasColumn('telegram_sessions', 'telegram_first_name')) {
                $table->dropColumn('telegram_first_name');
            }
        });
    }
};
