<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->timestamp('notified_at')->nullable()->after('year');
            $table->text('notification_error')->nullable()->after('notified_at');
            $table->unsignedInteger('notification_attempts')->default(0)->after('notification_error');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn(['notified_at', 'notification_error', 'notification_attempts']);
        });
    }
};
