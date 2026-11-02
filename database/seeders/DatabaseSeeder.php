<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $pacientes = User::factory()->count(3)->create();
        $medicos = User::factory()->count(2)->create();

        Cita::factory()
            ->count(5)
            ->recycle($pacientes)
            ->state(fn () => ['doctor_id' => $medicos->random()->id])
            ->create();

        Cita::factory()
            ->confirmada()
            ->recycle($pacientes)
            ->state(fn () => ['doctor_id' => $medicos->random()->id])
            ->create();
    }
}
