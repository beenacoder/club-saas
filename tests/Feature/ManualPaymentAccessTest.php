<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\Socio;
use App\Models\SocioCuota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ManualPaymentAccessTest extends TestCase
{
    use RefreshDatabase;

    private function createClubAndCuota(): array
    {
        $club = Club::create(['nombre' => 'Club de prueba']);
        $user = User::factory()->create(['club_id' => $club->id]);
        $socio = Socio::create(['club_id' => $club->id, 'nombre' => 'Socio de prueba', 'estado' => 'activo']);
        $cuotaId = DB::table('cuotas')->insertGetId([
            'club_id' => $club->id, 'nombre' => 'Cuota social', 'monto' => 100,
            'frecuencia' => 'mensual', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $cuota = SocioCuota::create([
            'club_id' => $club->id, 'socio_id' => $socio->id, 'cuota_id' => $cuotaId,
            'fecha' => now()->toDateString(), 'monto' => 100, 'estado' => 'pendiente',
        ]);

        return [$user, $cuota];
    }

    public function test_guest_cannot_register_a_manual_payment(): void
    {
        [, $cuota] = $this->createClubAndCuota();

        $this->post('/pagos', ['cuota_id' => $cuota->id, 'monto' => 30])->assertRedirect('/login');
        $this->assertDatabaseCount('pagos', 0);
    }

    public function test_user_cannot_pay_a_quota_from_another_club(): void
    {
        [$user] = $this->createClubAndCuota();
        [, $otherCuota] = $this->createClubAndCuota();

        $this->actingAs($user)->post('/pagos', ['cuota_id' => $otherCuota->id, 'monto' => 30])->assertNotFound();
        $this->assertDatabaseCount('pagos', 0);
        $this->assertDatabaseHas('socio_cuotas', ['id' => $otherCuota->id, 'monto_pagado' => 0]);
    }

    public function test_amount_above_remaining_balance_returns_a_validation_error(): void
    {
        [$user, $cuota] = $this->createClubAndCuota();

        $this->actingAs($user)->from('/socios')->post('/pagos', ['cuota_id' => $cuota->id, 'monto' => 101])
            ->assertRedirect('/socios')->assertSessionHasErrors('monto');
        $this->assertDatabaseCount('pagos', 0);
    }

    public function test_user_can_register_a_partial_payment_in_their_club(): void
    {
        [$user, $cuota] = $this->createClubAndCuota();

        $this->actingAs($user)->from('/socios')->post('/pagos', ['cuota_id' => $cuota->id, 'monto' => 30])
            ->assertRedirect('/socios')->assertSessionHasNoErrors();
        $this->assertDatabaseHas('socio_cuotas', ['id' => $cuota->id, 'monto_pagado' => 30, 'estado' => 'parcial']);
        $this->assertDatabaseHas('pagos', ['club_id' => $user->club_id, 'monto' => 30, 'metodo' => 'manual']);
    }
}
