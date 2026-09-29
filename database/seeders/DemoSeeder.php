<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Club;
use App\Models\Socio;
use App\Models\Actividad;
use App\Models\Cuota;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 CLUB
        $club = Club::create([
            'nombre' => 'Club Demo',
            'email' => 'clubdemo@gmail.com',
        ]);

        // crear usuario
        $user = User::create([
            'name' => 'Admin',
            'email' => 'clubdemo@gmail.com',
            'password' => Hash::make('12345678'),
            'club_id' => $club->id,
            'role' => 'admin',
        ]);

        // 🔥 ACTIVIDADES
        $futbol = Actividad::create([
            'club_id' => $club->id,
            'nombre' => 'Futbol',
            'slug' => 'futbol'
        ]);

        $gym = Actividad::create([
            'club_id' => $club->id,
            'nombre' => 'Gimnasio',
            'slug' => 'gimnasio'
        ]);

        // 🔥 SOCIOS
        $socio1 = Socio::create([
            'club_id' => $club->id,
            'nombre' => 'Juan Perez',
            'email' => 'juan@mail.com',
            'telefono' => '123',
            'estado' => 'activo'
        ]);

        $socio2 = Socio::create([
            'club_id' => $club->id,
            'nombre' => 'Maria Lopez',
            'email' => 'maria@mail.com',
            'telefono' => '456',
            'estado' => 'activo'
        ]);

        // 🔥 RELACIONES
        $socio1->actividades()->attach([$futbol->id, $gym->id]);
        $socio2->actividades()->attach([$gym->id]);

        // 🔥 CUOTAS
        Cuota::create([
            'club_id' => $club->id,
            'actividad_id' => $futbol->id,
            'nombre' => 'Futbol Mensual',
            'monto' => 10000,
            'frecuencia' => 'mensual'
        ]);

        Cuota::create([
            'club_id' => $club->id,
            'actividad_id' => $gym->id,
            'nombre' => 'Gimnasio Mensual',
            'monto' => 8000,
            'frecuencia' => 'mensual'
        ]);

        // 🔥 CUOTA GENERAL
        Cuota::create([
            'club_id' => $club->id,
            'actividad_id' => null,
            'nombre' => 'Cuota Social',
            'monto' => 5000,
            'frecuencia' => 'mensual'
        ]);
    }
}
