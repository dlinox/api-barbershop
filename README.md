# api-barbershop

Backend API REST de la **Plataforma Web Integral de Grupo Samanez**. Centraliza la lógica de negocio, autenticación, seguridad, persistencia y generación de reportes para la aplicación de gestión (`app-barbershop`).

## Stack

- **PHP** ^8.2
- **Laravel** ^12.0
- **Autenticación:** JWT (`php-open-source-saver/jwt-auth`) + Sanctum
- **Base de datos:** MySQL 8 / MariaDB 10.6+
- **PDFs:** `mpdf/mpdf` ^8.3
- **Tests:** Pest ^4.3

## Arquitectura

Monolito modular orientado al dominio (DDD). El código se organiza por **contexto de negocio** dentro de `app/Modules`, no por capa técnica.

```
app/
├── Common/          # Helpers, Exceptions, Middleware, Providers, Traits
├── Console/         # Comandos artisan
├── Models/          # Modelos Eloquent agrupados por dominio
│   ├── Academy/  Auth/  Barbershop/  Behavior/  Core/
│   └── Inventory/  Profile/  Reports/  Treasury/
└── Modules/         # Lógica por panel
    ├── Auth/  Profile/  Shared/
    ├── Administrator/   AcademyPanel/   BarbershopPanel/
    └── BarberPanel/     TeacherPanel/   StudentPanel/
```

Cada submódulo respeta la estructura:

```
<Modulo>/<Submódulo>/
├── Http/
│   ├── Controllers/   Requests/   Resources/
│   └── *.api.php           # Rutas auto-cargadas por RouteServiceProvider
├── Repositories/
└── Services/
```

Cualquier archivo `*.api.php` dentro de una carpeta `Http/` se carga automáticamente bajo el prefijo `/api`.

## Módulos principales

| Módulo               | Propósito                                                                                              |
| :------------------- | :----------------------------------------------------------------------------------------------------- |
| `Auth`               | Login con correo, Google OAuth, selección de perfil/sede, refresh, logout.                             |
| `Administrator`      | Panel del Super Admin (Academia, Barbería, Inventario, Tesorería, Seguridad, Configuración, Reportes). |
| `AcademyPanel`       | Operación de cada sede académica.                                                                      |
| `BarbershopPanel`    | Operación de cada barbería.                                                                            |
| `BarberPanel`        | Asistencia, tickets, POS, caja del barbero.                                                            |
| `TeacherPanel`       | Grupos asignados, asistencia del docente.                                                              |
| `StudentPanel`       | Matrículas, asistencia y pagos del estudiante.                                                         |
| `Profile` / `Shared` | Endpoints transversales (perfil del usuario, selectores, búsquedas).                                   |

## Base de datos

73 tablas agrupadas por prefijo de dominio: `core_*`, `auth_*`, `behavior_*`, `profile_*`, `academy_*`, `barbershop_*`, `barber_*`, `inventory_*`, `treasury_*`, `worker_*`, `reports`.

## Instalación (desarrollo local)

```bash
# 1. Instalar dependencias
composer install

# 2. Configurar entorno
cp .env.example .env
php artisan key:generate

# 3. Crear la base de datos y configurarla en .env
#    (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 4. Migrar y sembrar
php artisan migrate
php artisan db:seed --class=CoreSeeder
php artisan db:seed --class=PermissionSeeder

# 5. Levantar servidor
php artisan serve
```

La API quedará disponible en `http://localhost:8000/api`.

## Variables de entorno relevantes

```dotenv
APP_NAME="Grupo Samanez"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_DATABASE=db_api_barbershop

JWT_SECRET=********
JWT_TTL=4320            # Access token (minutos)
JWT_REFRESH_TTL=20160   # Refresh token (minutos)
JWT_BLACKLIST_ENABLED=true
```

## Middleware personalizado

| Alias                 | Función                                               |
| :-------------------- | :---------------------------------------------------- |
| `auth:api`            | Valida el JWT del header `Authorization: Bearer ...`. |
| `super_admin`         | Restringe la ruta a usuarios con rol Super Admin.     |
| `permission:<nombre>` | Verifica un permiso jerárquico específico.            |

Los permisos se definen en `config/permissions/*.php` con la estructura `feature → module → view → action`.

## Tarea programada (cron)

Una única tarea programada actualiza el estado de los grupos académicos:

```bash
0 0 * * *  /usr/local/bin/php /home/USUARIO/api/artisan academy:update-group-status >> /dev/null 2>&1
```

Detalle completo en [`CRON.md`](./CRON.md).

## Despliegue

El despliegue oficial se realiza por **subida de `.zip` en cPanel** de Hostinger. Procedimiento paso a paso, requisitos del servidor y matriz de variables en la documentación general de la plataforma (`DOCUMENTACION.md`, sección 1.2).

## Comandos útiles

```bash
php artisan optimize:clear          # Limpiar todas las cachés
php artisan config:cache            # Cachear configuración (producción)
php artisan route:cache             # Cachear rutas (producción)
php artisan schedule:list           # Ver tareas programadas
php artisan test                    # Ejecutar Pest
```

## Licencia

Software propietario. Ver [`LICENCIA.md`](./LICENCIA.md).
