# Laravel API - Guía de Desarrollo para LLMs

## Stack

- **Laravel 12** | **PHP 8.2+** | **JWT Auth** (php-open-source-saver/jwt-auth)

---

## Arquitectura: DDD-Lite Modular

```
app/
├── Modules/
│   ├── Core/           # Datos maestros
│   ├── Auth/           # Autenticación (JWT)
│   ├── Behavior/       # Autorización (roles/permisos)
│   └── Profile/        # Perfiles específicos (Admin, Student, etc.)
│
└── Shared/
    ├── Providers/      # AppServiceProvider
    └── Http/
        └── Responses/  # ApiResponse helper
```

---

## Estructura de Módulo

```
[Module]/
├── Models/           # Eloquent models
├── Repositories/     # Data access (Eloquent queries)
├── Services/         # Business logic (NO Eloquent directo)
└── Http/
    ├── Controllers/
    ├── Requests/     # Form validation
    ├── Resources/    # Response transformation (camelCase)
    └── [module].api.php
```

**Capas:**

- **Repository** → Acceso a datos (Eloquent)
- **Service** → Lógica de negocio (usa Repository)
- **Resource** → Transformación de respuesta (snake_case → camelCase)
- **Controller** → HTTP request/response

---

## Respuestas API

### ApiResponse (`App\Shared\Http\Responses\ApiResponse`)

```php
// Éxito
ApiResponse::success($data, 'Message');
ApiResponse::created($data, 'Created');

// Errores
ApiResponse::error('Message', $errors, 400);
ApiResponse::unauthorized('Message');      // 401
ApiResponse::forbidden('Message');         // 403
ApiResponse::notFound('Message');          // 404
ApiResponse::validationError($errors);     // 422
ApiResponse::serverError('Message');       // 500
```

### Estructura JSON

```json
// Éxito
{ "success": true, "message": "...", "data": {...} }

// Error
{ "success": false, "message": "...", "errors": {...} }
```

### Resources (camelCase)

```php
// Resource transforma snake_case a camelCase
return [
    'accessToken' => $this->token,
    'isActive' => $user->is_active,
    'lastSignInAt' => $user->last_sign_in_at,
];
```

---

## Rutas

Registradas en `AppServiceProvider`:

```php
private array $moduleRoutes = [
    'auth' => 'Auth/Http/auth.api.php',
];
```

Endpoints: `/api/{module}/...`

---

## Estándares de Datos

| Campo        | Estándar      | Códigos                         |
| ------------ | ------------- | ------------------------------- |
| DocumentType | SUNAT Cat. 06 | 1=DNI, 4=CE, 6=RUC, 7=Pasaporte |
| Gender       | ISO 5218      | 1=M, 2=F, 0=No conocido, 9=N/A  |

---

## Morph Map (Portabilidad)

```php
// AppServiceProvider - usa nombres de tabla, no clases
Relation::enforceMorphMap([
    'profile_admins' => Admin::class,
]);
```

---

## Comandos

```bash
php artisan serve                    # Servidor
php artisan migrate:fresh --seed     # Reset + seed
php artisan route:list               # Ver rutas
```

---

_Actualizado: Enero 2026_
