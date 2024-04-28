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
        Schema::create('cheques_issueds', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('cheque_number')->unique();
            $table->string('payee');
            $table->string('nature');
            $table->float('amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques_issueds');
    }
};
