<?php

namespace Database\Seeders;

use App\Models\Socio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SocioTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Socio::whereNull('token')->get()->each(function ($socio) {
        $socio->token = Str::uuid();
        $socio->save();
    });
    }
}
