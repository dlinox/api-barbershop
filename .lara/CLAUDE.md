# CLAUDE.md — Reglas y Convenciones del Proyecto

Este archivo define las reglas, patrones y convenciones de este proyecto Laravel para que la IA los siga estrictamente al generar o modificar código.

---

## 0. Reglas Críticas de Edición de Archivos

### 0.1 NUNCA usar `create_file` para sobrescribir archivos existentes en Windows

En Windows, la herramienta `create_file` **AGREGA contenido al final** del archivo en lugar de sobreescribirlo, aunque se haya hecho `Remove-Item` antes. Esto produce archivos duplicados con doble `<template>`, doble `class`, etc.

**Patrón correcto para reescribir un archivo existente:**

```powershell
# ✅ Usar Set-Content — sobreescribe atómicamente
$content = @'
... contenido completo ...
'@
Remove-Item $target -Force -ErrorAction SilentlyContinue
Set-Content -Path $target -Value $content -Encoding UTF8 -NoNewline

# Verificar resultado
([regex]::Matches((Get-Content $target -Raw), '<template>')).Count  # debe ser 1
```

**Cuándo aplica:**
- Archivos `.vue`: verificar que `<template>` y `<script` aparezcan exactamente 1 vez
- Archivos `.php`: verificar que `class NombreClase` aparezca exactamente 1 vez
- Siempre verificar con `[regex]::Matches` después de reescribir

### 0.2 Regla `replace_string_in_file`

Al reemplazar código en un archivo, el `oldString` **DEBE incluir TODO el código que se está reemplazando** — no solo la parte que cambia.

- Si se reescribe un método: incluir el método COMPLETO (firma + cuerpo + llave de cierre) en `oldString`
- Si se reescribe una clase: incluir toda la clase en `oldString`, o bien usar `Set-Content` en la terminal
- Antes de editar, leer siempre el archivo completo para confirmar el `oldString` exacto

---

## 1. Arquitectura General

- **Tipo:** Monolito modular Laravel 11 (API RESTful pura, sin vistas Blade).
- **Auth:** JWT (`php-open-source-saver/jwt-auth`). El token viaja en el header `Authorization: Bearer {token}`.
- **Claim personalizado JWT:** `prf` = ID del perfil activo. El middleware `CheckPermission` lo extrae para resolver permisos.
- **Locale:** `es` (Spanish). Los mensajes de error y atributos de validación van en español.
- **Timezone:** `America/Lima`.
- **DB:** MySQL/MariaDB, nombres de tablas y columnas siempre en `snake_case`.

---

## 2. Estructura de Módulos

Todos los módulos viven en `app/Modules/`. La estructura estándar de un módulo es:

```
app/Modules/{Role}/{Feature}/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   ├── Resources/
│   └── {feature}.api.php      ← archivo de rutas del módulo
├── Services/
└── Repositories/
```

Los modelos Eloquent **no viven dentro del módulo**, sino en `app/Models/{Domain}/`.

El `RouteServiceProvider` descubre y registra automáticamente todos los archivos `*.api.php` ubicados directamente dentro de cualquier carpeta `Http/` bajo `app/Modules/`. **No toques la lógica de descubrimiento automático.**

---

## 3. Transformación camelCase ↔ snake_case

### 3.1 Entrada (Frontend → API)

El frontend envía los datos en **camelCase**. La clase base `ApiFormRequest` los convierte automáticamente a **snake_case** antes de la validación mediante `prepareForValidation()`.

**Regla:** En los `rules()` y `attributes()` de los FormRequests, **siempre usar snake_case**. Nunca escribir reglas con claves camelCase.

```php
// ✅ Correcto
public function rules(): array
{
    return [
        'first_name'       => ['required', 'string', 'max:100'],
        'paternal_surname' => ['nullable', 'string', 'max:80'],
        'document_type'    => ['required', 'string'],
    ];
}
```

Los errores de validación son transformados de vuelta a camelCase automáticamente por `ApiFormRequest` antes de devolverse al cliente.

### 3.2 Salida (API → Frontend)

La API **siempre devuelve camelCase** en los Resources. La transformación es explícita en cada Resource.

```php
// ✅ Correcto en un Resource
return [
    'id'             => $this->id,
    'ticketNumber'   => $this->ticket_number,   // snake_case DB → camelCase JSON
    'cashSessionId'  => $this->cash_session_id,
    'isActive'       => (bool) $this->is_active,
    'createdAt'      => $this->created_at?->format('Y-m-d H:i:s'),
];
```

**Regla:** Nunca expongas campos `snake_case` directamente en un Resource. Siempre mapéalos a camelCase.

---

## 4. FormRequests

- Todos los FormRequests extienden `App\Common\Http\Requests\ApiFormRequest`.
- Nunca extiendas `Illuminate\Foundation\Http\FormRequest` directamente.
- Incluir siempre un array `attributes()` con los nombres de campo en español (para mensajes de error legibles).
- Para validaciones anidadas usar notación dot: `'services.*.service_id'`.
- Cuando un request combina reglas de otro request (ej. enrollment + income), instanciar el otro request y mezclar sus reglas con prefijo:

```php
$incomeRequest = new IncomeRequest();
$incomeRules = collect($incomeRequest->rules())
    ->mapWithKeys(fn($rule, $key) => ["income.{$key}" => $rule])
    ->all();

return array_merge($enrollmentRules, $incomeRules);
```

---

## 5. Resources

- Extender siempre `Illuminate\Http\Resources\Json\JsonResource`.
- La llave del array devuelto por `toArray()` debe estar en **camelCase**.
- Usar **casts explícitos** para numéricos: `(float)`, `(int)`, `(bool)`. No confíes en que Laravel castee automáticamente en el JSON.
- Formatear fechas explícitamente: `->format('Y-m-d H:i:s')` o `->format('d/m/Y')`.
- Para relaciones, usar `$this->whenLoaded('relation', fn() => ...)` para evitar N+1.
- Para Enums, usar `EnumClass::tryFrom($value)?->label()` para mostrar texto legible.
- Para colecciones anidadas, usar `->map(fn($item) => [...])` en lugar de Resources anidados cuando el objeto es simple.

```php
// ✅ Ejemplo correcto
public function toArray($request): array
{
    return [
        'id'          => $this->id,
        'fullName'    => $this->person->full_name,
        'amount'      => (float) $this->amount,
        'isActive'    => (bool) $this->is_active,
        'createdAt'   => $this->created_at?->format('Y-m-d H:i:s'),
        'services'    => $this->whenLoaded('services', fn() =>
            $this->services->map(fn($s) => [
                'serviceId' => $s->service_id,
                'amount'    => (float) $s->amount,
            ])
        ),
    ];
}
```

---

## 6. Controladores

- Los controladores son **delgados**: solo reciben el request, llaman al servicio y devuelven la respuesta.
- Usar **inyección de dependencias en el constructor** para inyectar el servicio correspondiente.
- **Nunca** poner lógica de negocio ni queries en el controlador.
- Siempre devolver usando `ApiResponse::*()`.
- Los métodos CRUD estándar son: `dataTable`, `save`, `delete`, `selectItems`.

```php
// ✅ Patrón estándar
class ServiceController
{
    public function __construct(
        private ServiceService $serviceService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->serviceService->dataTable($request);
        $items['data'] = ServiceDataTableResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(ServiceRequest $request)
    {
        $this->serviceService->save($request->validated());
        return ApiResponse::success(null, 'Servicio guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->serviceService->delete($id);
        return ApiResponse::success(null, 'Servicio eliminado correctamente');
    }

    public function selectItems()
    {
        $items = $this->serviceService->getActiveServices();
        return ApiResponse::success(ServiceSelectItemResource::collection($items));
    }
}
```

---

## 7. Servicios

- Un servicio orquesta la lógica de negocio y coordina repositorios.
- Usar **inyección de dependencias en el constructor** para repositorios.
- Envolver operaciones de escritura en `DB::beginTransaction()` / `DB::commit()` / `DB::rollBack()`.
- Retornar modelos Eloquent (no arrays ni DTOs).
- Lanzar `ApiException` (o excepciones estándar de PHP) para errores de negocio.

```php
// ✅ Patrón estándar
public function save(array $data): Model
{
    try {
        DB::beginTransaction();
        $result = $this->repository->createOrUpdate($data);
        DB::commit();
        return $result;
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
```

---

## 8. Repositorios

- Los repositorios son la **única** capa que toca la base de datos.
- Usar Eloquent (`Model::query()`, `updateOrCreate`, `find`, `get`, etc.), **nunca** `DB::statement` raw salvo casos excepcionales justificados.
- El método `createOrUpdate` usa `Model::updateOrCreate(['id' => $data['id']], $data)`.
- Para dataTables usar el scope `->dataTable($request)` del trait `HasDataTable` del modelo.
- Antes de eliminar, verificar relaciones dependientes y lanzar excepción descriptiva si existen.
- Aplicar scopes multi-tenant con el trait `HasInfrastructureScope` cuando aplique.

```php
// ✅ Patrón estándar
public function dataTable($request)
{
    $query = MyModel::query();
    if (empty($request->sortBy)) {
        $query->orderBy('id', 'desc');
    }
    return $query->dataTable($request);
}

public function createOrUpdate(array $data): MyModel
{
    return MyModel::updateOrCreate(['id' => $data['id'] ?? null], $data);
}

public function delete(int $id): void
{
    $record = MyModel::findOrFail($id);
    if ($record->children()->exists()) {
        throw new \Exception('No se puede eliminar porque tiene elementos relacionados');
    }
    $record->delete();
}
```

---

## 9. Modelos Eloquent

- Vivir en `app/Models/{Domain}/` (no dentro de módulos).
- Tablas con prefijo de dominio: `core_`, `academy_`, `barbershop_`, `inventory_`, `treasury_`, `behavior_`, `profile_`, `auth_`.
- Siempre declarar `$fillable` (nunca usar `$guarded = []`).
- Usar `$casts` para booleanos, fechas e integers: `'is_active' => 'boolean'`.
- Los modelos que necesiten dataTables deben usar el trait `HasDataTable` y declarar `public static $searchColumns`.
- Los accessors para propiedades computadas siguen la convención Laravel 9+ (método con retorno typed o `Attribute`).

```php
// ✅ Modelo estándar
class Service extends Model
{
    use HasDataTable;

    protected $table = 'barbershop_services';

    protected $fillable = ['name', 'category_id', 'price', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'price'     => 'integer',
    ];

    public static $searchColumns = ['barbershop_services.name'];
}
```

---

## 10. Rutas

- Cada módulo tiene su propio archivo `{feature}.api.php` dentro de `Http/`.
- **Todas** las rutas llevan `middleware('auth:api')`.
- Prefijos en snake_case-plural: `/api/barbershop-services`, `/api/academy-branches`.
- Nombrar rutas como `{resource}.{action}`: `services.dataTable`, `services.save`.
- Endpoints CRUD estándar:
  - `POST  /data-table` → listar con paginación/filtros
  - `POST  /save`       → crear o actualizar
  - `DELETE /delete/{id}` → eliminar
  - `GET  /select-items` → lista para dropdowns (sin permiso requerido)
- Los permisos se aplican por middleware inline en la ruta:

```php
// ✅ Patrón de rutas
Route::middleware(['auth:api'])->prefix('/barbershop-services')->group(function () {
    Route::post('/data-table', [ServiceController::class, 'dataTable'])
        ->name('services.dataTable')
        ->middleware('permission:barbershop.service.view');

    Route::post('/save', [ServiceController::class, 'save'])
        ->name('services.save')
        ->middleware('permission:barbershop.service.create,barbershop.service.edit');

    Route::delete('/delete/{id}', [ServiceController::class, 'delete'])
        ->name('services.delete')
        ->middleware('permission:barbershop.service.delete');

    Route::get('/select-items', [ServiceController::class, 'selectItems'])
        ->name('services.selectItems');
    // ↑ Sin permiso: es un diccionario interno
});
```

---

## 11. Sistema de Permisos

- Los permisos se definen en archivos de configuración bajo `config/permissions/`.
- Estructura jerárquica: `feature` → `module` → `view` → `action`.
- Nombres en formato: `{feature}.{module}.{action}` (todo en minúsculas, separado por puntos).
- El campo `level` solo se declara en la raíz (`feature`). Los hijos lo heredan.
- Tipos válidos: `feature`, `module`, `view`, `action`.
- Después de agregar permisos al config, ejecutar: `php artisan db:seed --class=PermissionSeeder`.
- En el middleware de ruta, **usar coma (`,`) para OR** entre permisos. **Nunca usar pipe (`|`)**.

```php
// ✅ Correcto — OR: basta con tener uno
->middleware('permission:academy.branch.create,academy.branch.edit')

// ❌ Incorrecto — pipe no está soportado
->middleware('permission:academy.branch.create|academy.branch.edit')
```

- Los usuarios con rol `super_admin` (level `0`) **bypass** todos los permisos automáticamente.
- **Los endpoints `select-items` o similares que devuelven catálogos internos no requieren permiso.**

---

## 12. Respuestas HTTP

Usar siempre `App\Common\Http\Responses\ApiResponse`. **Nunca** devolver `response()->json()` directamente.

| Situación | Método |
|---|---|
| Éxito general | `ApiResponse::success($data, 'Mensaje')` |
| Creación | `ApiResponse::success($data, 'Creado', 201)` |
| Error de negocio | `ApiResponse::error('Mensaje', null, 400)` |
| No autorizado | `ApiResponse::unauthorized('Mensaje')` |
| Sin permiso | `ApiResponse::forbidden('Mensaje')` |
| No encontrado | `ApiResponse::notFound('Mensaje')` |
| Error de validación | `ApiResponse::validationError($errors)` |
| Error de servidor | `ApiResponse::serverError('Mensaje')` |

**Estructura JSON de éxito:**
```json
{ "success": true, "message": "...", "data": { ... } }
```

**Estructura JSON de error:**
```json
{ "success": false, "message": "...", "errors": { ... } }
```

---

## 13. Autenticación JWT

- Login: `POST /api/auth/sign-in` → devuelve `token`.
- Todas las rutas protegidas requieren `Authorization: Bearer {token}`.
- El claim `prf` en el JWT almacena el `profile_id` activo del usuario.
- El middleware `CheckPermission` extrae `prf` del token y resuelve permisos del perfil.
- Si el usuario tiene un solo perfil, se auto-selecciona en el login.
- Si tiene múltiples perfiles, debe llamar `POST /api/auth/select-profile/{id}` para obtener un token con `prf` definido.
- Para renovar: `POST /api/auth/refresh`.

---

## 14. Tablas de Base de Datos (Migraciones)

- **Siempre** prefixar el nombre de la tabla con el dominio:
  - `core_*` → entidades compartidas (personas, infraestructuras)
  - `auth_*` → usuarios y credenciales
  - `behavior_*` → roles, permisos, sesiones
  - `profile_*` → perfiles por rol
  - `academy_*` → academia
  - `barbershop_*` → barbería
  - `inventory_*` → inventario
  - `treasury_*` → tesorería
- Columnas siempre en `snake_case`.
- Usar `$table->timestamps()` en todas las tablas.
- Definir `foreign()` con `onDelete('restrict')` por defecto; usar `nullOnDelete()` o `cascadeOnDelete()` cuando esté justificado.

---

## 15. Enums

- Los Enums viven en `app/Common/Enums/`.
- Siempre son `backed enums` (string o int).
- Incluir un método `label(): string` que devuelva el texto legible en español.
- En Resources y Responses usar `EnumClass::tryFrom($value)?->label()` para mostrar texto al frontend.

```php
// ✅ Ejemplo
enum DocumentType: string
{
    case DNI = 'DN';
    case Passport = 'PS';

    public function label(): string
    {
        return match ($this) {
            self::DNI      => 'DNI',
            self::Passport => 'Pasaporte',
        };
    }
}
```

---

## 16. Trait HasDataTable

Los modelos que necesiten listar en tablas paginadas deben usar el trait `HasDataTable`.

- Declarar `public static $searchColumns` con los nombres completos de columna (`tabla.columna`).
- El repositorio llama `Model::query()->dataTable($request)` que retorna `{ data, currentPage, perPage, total }`.
- El controlador envuelve `$items['data']` con el Resource collection antes de retornar.

```php
// En el controlador
$items = $this->service->dataTable($request);
$items['data'] = MyDataTableResource::collection($items['data']);
return ApiResponse::success($items);
```

---

## 17. Excepciones

- Para errores de negocio lanzar `App\Common\Exceptions\ApiException`.
- Para permisos denegados el middleware lanza `PermissionDeniedException` automáticamente.
- El `Handler` global captura estas excepciones y devuelve la respuesta JSON apropiada.
- **No** envolver bloques enteros en try/catch genéricos en controladores; dejar que el handler global se encargue, salvo transacciones de BD en servicios.

---

## 18. Generación de PDFs

- Usar `App\Common\Helpers\PdfHelper` (basado en mPDF).
- Formatos disponibles: `PdfHelper::A4`, `PdfHelper::A5`, `PdfHelper::TICKET`.
- Templates HTML en `resources/pdf/`.

```php
$html = file_get_contents(resource_path('pdf/my-template.html'));
$mpdf = PdfHelper::createFromHtml($html);
return PdfHelper::inline($mpdf, 'archivo.pdf');   // Ver en navegador
return PdfHelper::download($mpdf, 'archivo.pdf'); // Descargar
```

---

## 19. Testing

- Framework: **Pest PHP**.
- Tests en `tests/Feature/` (integración) y `tests/Unit/` (unitarios).
- Seguir el patrón existente en `tests/TestCase.php`.

---

## 20. Checklist al Crear un Nuevo Módulo

1. Crear `app/Modules/{Role}/{Feature}/Http/{feature}.api.php` con las rutas.
2. Crear `Controllers/`, `Requests/`, `Resources/` dentro de `Http/`.
3. Crear `Services/` y `Repositories/`.
4. Crear modelos en `app/Models/{Domain}/` (no dentro del módulo).
5. Crear migración con prefijo de dominio en `database/migrations/`.
6. Agregar permisos en `config/permissions/{domain}.permissions.php`.
7. Ejecutar `php artisan db:seed --class=PermissionSeeder` para registrar los permisos.
8. Todos los FormRequests extienden `ApiFormRequest`.
9. Todas las respuestas usan `ApiResponse::*()`.
10. Todos los Resources transforman snake_case → camelCase explícitamente.
