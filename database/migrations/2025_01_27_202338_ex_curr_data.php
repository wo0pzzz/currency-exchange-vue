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
        Schema::create('ex_curr_data', function (Blueprint $table) {
            $table->id();
            $table->string('from_curr');
            $table->string('to_curr');
            $table->float('from_amount');
            $table->float('to_amount');
            $table->float('rate');
            $table->integer('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ex_curr_data');
    }
};
