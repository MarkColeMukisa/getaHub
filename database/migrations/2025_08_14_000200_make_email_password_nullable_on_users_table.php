<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        // Intentionally left blank after rollback of earlier change.
    }

    public function down(): void
    {
        // No-op
    }
};
