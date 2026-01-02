<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public $withinTransaction = false;
     
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'contact')) {
                $table->string('contact')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'room_number')) {
                $table->string('room_number')->nullable()->after('contact');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'contact')) {
                $table->dropColumn('contact');
            }
            if (Schema::hasColumn('users', 'room_number')) {
                $table->dropColumn('room_number');
            }
        });
    }
};
