<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

