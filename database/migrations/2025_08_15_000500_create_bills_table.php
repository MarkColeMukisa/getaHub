<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public $withinTransaction = false;
        
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('previous_reading');
            $table->unsignedBigInteger('current_reading');
            $table->unsignedBigInteger('units_used');
            $table->unsignedInteger('unit_price'); // store in smallest currency unit if needed
            $table->unsignedBigInteger('total_amount');
            $table->string('month'); // e.g. January
            $table->unsignedSmallInteger('year');
            $table->timestamps();
            $table->index(['tenant_id','year','month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
