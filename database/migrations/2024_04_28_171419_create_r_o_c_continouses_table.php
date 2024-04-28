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
        Schema::create('r_o_c_continouses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('or_number')->unique();
            $table->string('payor_name');
            $table->string('student_number');
            $table->string('college');
            $table->string('transaction_code');
            $table->float('amount')
                ->default(0);
            $table->float('total_amount')
                ->default(0);
            $table->string('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_o_c_continouses');
    }
};
