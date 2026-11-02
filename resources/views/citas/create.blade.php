<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Agendar cita | MediCitas</title>
        <style>
            :root {
                color-scheme: light;
                font-family: Arial, sans-serif;
                color: #1f2937;
                background: #f3f6f5;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
            }

            main {
                width: min(100% - 2rem, 32rem);
                margin: 0 auto;
                padding: 2.5rem 0;
            }

            h1 {
                margin: 0 0 1.5rem;
                font-size: 1.75rem;
            }

            form {
                background: #fff;
                border: 1px solid #d7dee0;
                border-radius: 0.5rem;
                padding: 1.75rem;
            }

            .field {
                margin-bottom: 1.1rem;
            }

            .field label {
                display: block;
                margin-bottom: 0.35rem;
                font-size: 0.8rem;
                font-weight: 700;
                color: #374151;
            }

            .field input,
            .field select,
            .field textarea {
                width: 100%;
                padding: 0.6rem 0.7rem;
                border: 1px solid #d7dee0;
                border-radius: 0.35rem;
                font: inherit;
            }

            .field .error {
                margin-top: 0.3rem;
                color: #b91c1c;
                font-size: 0.8rem;
            }

            .form-actions {
                display: flex;
                gap: 0.75rem;
                margin-top: 1.25rem;
            }

            button.btn,
            a.btn-secondary {
                display: inline-block;
                padding: 0.6rem 1.25rem;
                border-radius: 0.35rem;
                border: none;
                font-weight: 700;
                font-size: 0.9rem;
                cursor: pointer;
                text-decoration: none;
            }

            button.btn {
                background: #2563eb;
                color: #fff;
            }

            a.btn-secondary {
                background: transparent;
                color: #374151;
                border: 1px solid #d7dee0;
            }
        </style>
    </head>
    <body>
        <main>
            <h1>Agendar cita</h1>

            <form action="{{ route('citas.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="patient_id">Paciente</label>
                    <select id="patient_id" name="patient_id" required>
                        <option value="">Selecciona un paciente</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" @selected(old('patient_id') == $usuario->id)>{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="doctor_id">Médico</label>
                    <select id="doctor_id" name="doctor_id" required>
                        <option value="">Selecciona un médico</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" @selected(old('doctor_id') == $usuario->id)>{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="especialidad">Especialidad</label>
                    <input type="text" id="especialidad" name="especialidad" value="{{ old('especialidad') }}" required>
                    @error('especialidad') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="fecha_hora">Fecha y hora</label>
                    <input type="datetime-local" id="fecha_hora" name="fecha_hora" value="{{ old('fecha_hora') }}" required>
                    @error('fecha_hora') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="motivo">Motivo</label>
                    <textarea id="motivo" name="motivo" rows="3" required>{{ old('motivo') }}</textarea>
                    @error('motivo') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Agendar</button>
                    <a href="{{ route('citas.index') }}" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </main>
    </body>
</html>
