# Guía para Crear un CRUD Simple

Esta guía describe los pasos necesarios para implementar un CRUD simple siguiendo el patrón utilizado en la entidad **PaymentMethods** (módulo `Setting`) o **Branch** (módulo `Academy`).

---

## Backend (API Laravel)

### Estructura de Archivos

Para cada nueva entidad (por ejemplo, `Level`), se deben crear los siguientes archivos dentro del módulo correspondiente en `app/Modules/Administrator/{Modulo}`:

1.  **Model**: `app/Models/{Dominio}/Level.php`
    - Usa traits `HasDataTable` (obligatorio) y opcionalmente `HasInfrastructure`.
    - Define `$table`, `$fillable`, `$hidden`, `$casts`, `$searchColumns`.

2.  **Controller**: `Http/Controllers/LevelController.php`
    - No extiende ninguna clase base.
    - Inyecta el Service via constructor promotion.
    - Métodos estándar: `dataTable`, `save`, `delete`, `selectItems`.
    - Retorna `ApiResponse::success()`.

3.  **Service**: `Services/LevelService.php`
    - Capa delgada que delega al Repository.
    - Inyecta el Repository via constructor promotion.

4.  **Repository**: `Repositories/LevelRepository.php`
    - Interactúa con el Modelo de Eloquent.
    - Métodos: `dataTable()` (usa `Model::dataTable($request)`), `createOrUpdate()` (usa `Model::updateOrCreate`), `delete()`, `getActive*()`.

5.  **Request**: `Http/Requests/Level/LevelRequest.php`
    - Extiende `App\Common\Http\Requests\ApiFormRequest`.
    - Define `rules()`, `messages()`, `attributes()`.
    - El `id` usa patrón condicional: `$id ? 'exists:tabla,id' : 'nullable'`.
    - Los campos `name` usan `unique:tabla,column,{id}` para ignorar el registro actual en updates.

6.  **Resources**:
    - `Http/Resources/Level/LevelDataTableItemResource.php`: Mapea campos a camelCase para la tabla.
    - `Http/Resources/Level/LevelSelectItemResource.php`: Formato `{value, title}` para selectores.

### Registrar Rutas

Edite el archivo `*.api.php` del módulo. Patrón estándar:

```php
use App\Modules\Administrator\{Modulo}\Http\Controllers\LevelController;

Route::middleware(['auth:api'])->prefix('/levels')->group(function () {
    Route::post('/data-table', [LevelController::class, 'dataTable'])->name('levels.dataTable')->middleware('permission:modulo.level.view');
    Route::post('/save', [LevelController::class, 'save'])->name('levels.save')->middleware('permission:modulo.level.create,modulo.level.edit');
    Route::get('/select-items', [LevelController::class, 'selectItems'])->name('levels.selectItems');
    Route::delete('/delete/{id}', [LevelController::class, 'delete'])->name('levels.delete')->middleware('permission:modulo.level.delete');
});
```

### Convenciones API

- **camelCase <-> snake_case**: El frontend envía camelCase; `ApiFormRequest.prepareForValidation()` convierte a snake_case. Los Resources convierten de vuelta a camelCase.
- **Endpoint único `save`**: Create y Update usan el mismo `POST /save`. Si el `id` es `null` crea, si existe actualiza.
- **Repository usa `updateOrCreate`**: `Model::updateOrCreate(['id' => $data['id']], $data)`.
- **Permisos**: Siguen formato `modulo.entidad.accion` (view, create, edit, delete).

---

## Frontend (Vue + Vuetify)

### Estructura de Archivos

Para cada entidad se crean archivos en dos ubicaciones:

**Capa de dominio** (`src/app/modules/Administrator/{Modulo}/`):

1.  **Model**: `models/level.model.ts` — Interface TypeScript de la entidad.
2.  **DTO**: `dtos/level-request.dto.ts` — Extiende el model con `id: number | null`.
3.  **Service Interface**: `services/level-service.ts` — Contrato con `dataTable`, `save`, `delete`.
4.  **Service HTTP**: `services/level-service.http.ts` — Implementación HTTP con `api.post`/`api.delete`.

**Capa de presentación** (`src/ui/modules/Administrator/{Modulo}/Level/`):

5.  **View**: `views/Level.view.vue` — Página con título, botones de exportación y el componente tabla.
6.  **Table Component**: `components/LevelTable/LevelTable.component.vue` — DataTable con filtros, acciones CRUD, ConfirmDialog.
7.  **Table Composable**: `components/LevelTable/useLevelTable.ts` — Headers, función dataTable, delete.
8.  **Table Reports**: `components/LevelTable/level-table.reports.ts` — Exportación Excel/PDF.
9.  **Form Component**: `components/LevelForm/LevelForm.component.vue` — Dialog con formulario.
10. **Form Composable**: `components/LevelForm/useLevelForm.ts` — Estado del form, reglas, submit, setItem.

### Registrar Ruta

Agregar la ruta hija en el archivo `src/ui/modules/Administrator/routes/{modulo}.routes.ts`:

```typescript
{
  path: "level",
  name: "LevelView",
  component: () =>
    import("@/ui/modules/Administrator/{Modulo}/Level/views/Level.view.vue"),
  meta: {
    requiresAuth: true,
    permissions: ["modulo.level.view"],
  },
},
```

### Agregar al Menú

Agregar un `v-list-item` en el componente de menú del módulo (`{Modulo}Menu.component.vue`):

```vue
<v-list-item
  v-permission="['modulo.level.view']"
  value="level"
  title="Niveles"
  class="px-3"
  :to="{ name: 'LevelView' }"
  link
  exact
>
  <template #prepend>
    <v-icon size="x-small"><IconLevel /></v-icon>
  </template>
</v-list-item>
```

---

## Notas Adicionales

- **No inventar**: Siga estrictamente el patrón existente.
- **Sin Tests**: Para estos CRUDs simples, no se requieren pruebas automatizadas.
- **Modelos**: Asegúrese de que el Modelo tenga definidos `$fillable`, `$casts`, `$searchColumns` y use el trait `HasDataTable`.
- **Componentes globales**: `DataTableServer` y `ConfirmDialog` están registrados globalmente en `main.ts`.
- **Validaciones frontend**: Usar utilidades de `@/core/utils/validations` (`isRequired`, `isRequiredNumber`, etc.).
- **Estado/Status**: Usar constantes de `@/ui/common/constants/status.constants` (`STATUS_ITEMS`, `STATUS_MAPPER`).
