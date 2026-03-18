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
        Schema::create('socios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('club_id')->constrained()->cascadeOnDelete();

        $table->string('nombre');
        $table->string('email')->nullable();
        $table->string('telefono')->nullable();
        $table->string('estado')->default('activo');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
