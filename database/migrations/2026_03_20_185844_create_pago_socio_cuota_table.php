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
        Schema::create('pago_socio_cuota', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pago_id')->constrained()->cascadeOnDelete();
            $table->foreignId('socio_cuota_id')->constrained()->cascadeOnDelete();

            $table->decimal('monto', 10, 2); // 🔥 pago parcial posible

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_socio_cuota');
    }
};
