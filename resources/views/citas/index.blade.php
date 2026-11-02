<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Citas | MediCitas</title>
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
                width: min(100% - 2rem, 60rem);
                margin: 0 auto;
                padding: 2.5rem 0;
            }

            h1 {
                margin: 0 0 0.25rem;
                font-size: 2rem;
            }

            .intro {
                margin: 0 0 1.5rem;
                color: #4b5563;
            }

            .status {
                padding: 0.9rem 1.1rem;
                margin-bottom: 1.25rem;
                border-radius: 0.4rem;
                background: #e7f6ec;
                border: 1px solid #a9d9b8;
                color: #1f5132;
            }

            .actions-bar {
                display: flex;
                justify-content: flex-end;
                margin-bottom: 1.25rem;
            }

            a.btn {
                display: inline-block;
                padding: 0.6rem 1.25rem;
                border-radius: 0.35rem;
                background: #2563eb;
                color: #fff;
                text-decoration: none;
                font-weight: 700;
                font-size: 0.9rem;
            }

            .table-wrap {
                overflow: hidden;
                border: 1px solid #d7dee0;
                border-radius: 0.5rem;
                background: #fff;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
            }

            th, td {
                padding: 0.9rem 1rem;
                border-bottom: 1px solid #e5eaea;
                font-size: 0.95rem;
            }

            th {
                background: #eef2f5;
                color: #374151;
                font-size: 0.75rem;
                letter-spacing: 0.06em;
                text-transform: uppercase;
            }

            tbody tr:last-child td {
                border-bottom: 0;
            }

            .badge {
                display: inline-block;
                padding: 0.2rem 0.6rem;
                border-radius: 999px;
                font: 700 0.7rem/1.2 Arial, sans-serif;
                text-transform: uppercase;
            }

            .badge-pendiente { background: #fef3c7; color: #92400e; }
            .badge-confirmada { background: #d1fae5; color: #065f46; }
            .badge-cancelada { background: #fee2e2; color: #991b1b; }

            .empty {
                padding: 2rem;
                text-align: center;
                color: #6b7280;
            }
        </style>
    </head>
    <body>
        <main>
            <h1>Citas médicas</h1>
            <p class="intro">Listado de citas agendadas en MediCitas.</p>

            @if (session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            <div class="actions-bar">
                <a href="{{ route('citas.create') }}" class="btn">Agendar cita</a>
            </div>

            <div class="table-wrap">
                @if ($citas->isEmpty())
                    <p class="empty">No hay citas agendadas todavía.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">Paciente</th>
                                <th scope="col">Médico</th>
                                <th scope="col">Especialidad</th>
                                <th scope="col">Fecha y hora</th>
                                <th scope="col">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($citas as $cita)
                                <tr>
                                    <td>{{ $cita->paciente->name }}</td>
                                    <td>{{ $cita->medico->name }}</td>
                                    <td>{{ $cita->especialidad }}</td>
                                    <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                                    <td><span class="badge badge-{{ $cita->estado }}">{{ $cita->estado }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </main>
    </body>
</html>
