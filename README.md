# MediCitas — Taller Prueba y Depuración

Aplicación Laravel para la gestión de citas médicas de la clínica **MediCitas**, con un módulo web
sencillo (Blade), endpoints API equivalentes y una suite de pruebas automatizadas de integración.

## Requisitos

- PHP 8.3+
- Composer

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

## Migraciones y datos de prueba (seeders)

```bash
php artisan migrate --seed
```

Esto crea pacientes, médicos y citas de ejemplo (pendientes, confirmadas y canceladas).

## Levantar la aplicación

```bash
php artisan serve
```

- Listado de citas: `http://localhost:8000/citas`
- Agendar una cita: `http://localhost:8000/citas/crear`

## Endpoints API

- `GET /api/citas`: devuelve únicamente las citas en estado `confirmada`.
- `POST /api/citas` (requiere autenticación con `auth:sanctum`): agenda una cita para el usuario
  autenticado y envía una notificación por correo al médico asignado.

## Ejecutar las pruebas automatizadas

Las pruebas corren contra una base de datos SQLite en memoria (configurada en `phpunit.xml`).

```bash
php artisan test
```

O solo la suite de citas:

```bash
php artisan test --filter=CitaApiTest
```

### Casos de prueba incluidos (`tests/Feature/CitaApiTest.php`)

1. **`test_muestra_citas_confirmadas`**: verifica que `GET /api/citas` devuelva únicamente las citas
   con estado `confirmada`.
2. **`test_agendar_cita_y_envio_correo`**: un paciente autenticado agenda una cita, se persiste en
   la base de datos y se dispara una notificación por correo al médico (`Mail::fake()`).
3. **`test_detecta_problema_n_mas_1`**: cuenta las consultas SQL ejecutadas al listar las citas,
   verificando que las relaciones `paciente` y `medico` se cargan de forma eficiente (eager loading)
   en vez de generar una consulta individual por cada cita.
