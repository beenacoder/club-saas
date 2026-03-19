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
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();

            // puede ser null para cuota general (ej: cuota social)
            $table->foreignId('actividad_id')->nullable()->constrained('actividades');

            $table->string('nombre');
            $table->decimal('monto', 10, 2);
            $table->string('frecuencia'); // mensual, semanal, etc

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
