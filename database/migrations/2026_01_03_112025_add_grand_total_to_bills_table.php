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
            $table->integer('vat_amount')->default(0);
            $table->integer('paye_amount')->default(0);
            $table->integer('rubbish_amount')->default(0);
            $table->unsignedBigInteger('grand_total')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            //
        });
    }
};
