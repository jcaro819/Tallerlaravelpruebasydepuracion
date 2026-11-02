<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CitaAgendadaNotification;
use App\Models\Cita;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CitaController extends Controller
{
    // Endpoint de consulta: solo devuelve las citas confirmadas
    public function index(): JsonResponse
    {
        $citas = Cita::where('estado', 'confirmada')
            ->with(['paciente', 'medico'])
            ->get();

        $data = $citas->map(fn (Cita $cita) => [
            'id' => $cita->id,
            'especialidad' => $cita->especialidad,
            'fecha_hora' => $cita->fecha_hora,
            'estado' => $cita->estado,
            'paciente' => $cita->paciente->name,
            'medico' => $cita->medico->name,
        ]);

        return response()->json($data);
    }

    // Agendar una cita (paciente autenticado) y notificar al médico
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:users,id'],
            'especialidad' => ['required', 'string', 'max:255'],
            'fecha_hora' => ['required', 'date'],
            'motivo' => ['required', 'string'],
        ]);

        $cita = Cita::create([
            ...$validated,
            'patient_id' => $request->user()->id,
        ]);

        Mail::to($cita->medico->email)->send(new CitaAgendadaNotification($cita));

        return response()->json($cita, 201);
    }
}
