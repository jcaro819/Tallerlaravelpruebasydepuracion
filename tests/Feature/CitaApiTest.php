<?php

namespace Tests\Feature;

use App\Mail\CitaAgendadaNotification;
use App\Models\Cita;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CitaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_muestra_citas_confirmadas(): void
    {
        Cita::factory()->confirmada()->create(['especialidad' => 'Cardiología']);
        Cita::factory()->confirmada()->create(['especialidad' => 'Pediatría']);
        Cita::factory()->create(['estado' => 'pendiente']);
        Cita::factory()->cancelada()->create();

        $response = $this->getJson('/api/citas');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['especialidad' => 'Cardiología'])
            ->assertJsonFragment(['especialidad' => 'Pediatría']);
    }

    public function test_agendar_cita_y_envio_correo(): void
    {
        Mail::fake();

        $paciente = User::factory()->create();
        $medico = User::factory()->create();

        $response = $this->actingAs($paciente)
            ->postJson('/api/citas', [
                'doctor_id' => $medico->id,
                'especialidad' => 'Dermatología',
                'fecha_hora' => now()->addDay()->format('Y-m-d H:i:s'),
                'motivo' => 'Revisión de rutina.',
            ]);

        // Imprime el cuerpo de la respuesta y detiene la prueba
        dump('/api/citas Response Content:');
        dump($response->getContent());

        $response->assertStatus(201);

        $this->assertDatabaseHas('citas', [
            'patient_id' => $paciente->id,
            'doctor_id' => $medico->id,
            'especialidad' => 'Dermatología',
        ]);

        Mail::assertSent(CitaAgendadaNotification::class, function ($mail) use ($medico) {
            return $mail->hasTo($medico->email);
        });
    }

    public function test_detecta_problema_n_mas_1(): void
    {
        $medico = User::factory()->create();
        Cita::factory()
            ->count(10)
            ->confirmada()
            ->create(['doctor_id' => $medico->id]);

        // Escuchar y contar cuántas consultas SQL se ejecutan
        $queryCount = 0;
        DB::listen(function ($query) use (&$queryCount) {
            $queryCount++;
        });

        // Hacer la petición a la API
        $this->getJson('/api/citas');

        // Imprimir la evidencia directamente en la terminal de pruebas
        dump('Total de consultas SQL ejecutadas: '.$queryCount);

        // 1 consulta para las citas + 1 para pacientes + 1 para médicos (eager loading)
        $this->assertLessThanOrEqual(3, $queryCount, "Se detectó un problema de N+1. Consultas ejecutadas: {$queryCount}");
    }
}
