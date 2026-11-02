<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CitaController extends Controller
{
    // Listado de todas las citas agendadas
    public function index(): View
    {
        $citas = Cita::with(['paciente', 'medico'])->latest('fecha_hora')->get();

        return view('citas.index', compact('citas'));
    }

    // Formulario de agendamiento
    public function create(): View
    {
        $usuarios = User::orderBy('name')->get();

        return view('citas.create', compact('usuarios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:users,id', 'different:doctor_id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'especialidad' => ['required', 'string', 'max:255'],
            'fecha_hora' => ['required', 'date'],
            'motivo' => ['required', 'string'],
        ]);

        Cita::create($validated);

        return redirect()->route('citas.index')->with('status', 'Cita agendada correctamente.');
    }
}
