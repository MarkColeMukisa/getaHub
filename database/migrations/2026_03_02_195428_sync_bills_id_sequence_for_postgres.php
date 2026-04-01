<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement("
            SELECT setval(
                pg_get_serial_sequence('bills', 'id'),
                COALESCE((SELECT MAX(id) FROM bills), 1),
                (SELECT MAX(id) IS NOT NULL FROM bills)
            )
        ");
    }

    public function down(): void
    {
        //
    }
};
