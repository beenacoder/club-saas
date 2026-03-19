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
        Schema::create('socio_cuotas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('club_id')->constrained()->cascadeOnDelete();

            $table->foreignId('socio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cuota_id')->constrained()->cascadeOnDelete();

            $table->date('fecha'); // mes de la cuota
            $table->decimal('monto', 10, 2);

            $table->string('estado')->default('pendiente');
            // pendiente | pagado | vencido

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socio_cuotas');
    }
};
