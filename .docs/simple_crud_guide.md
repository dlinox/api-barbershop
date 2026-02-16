# Guía para Crear un CRUD Simple

Esta guía describe los pasos necesarios para implementar un CRUD simple en el módulo `Administrator/Academy`, siguiendo el patrón utilizado en la entidad **Branch** (`BranchController`, `BranchService`, etc.).

## Estructura de Archivos

Para cada nueva entidad (por ejemplo, `Level`), se deben crear los siguientes archivos en `app/Modules/Administrator/Academy`:

1.  **Controller**: `Http/Controllers/LevelController.php`
    - Maneja las peticiones HTTP.
    - Inyecta el Service.
    - Métodos estándar: `dataTable`, `save`.

2.  **Service**: `Services/LevelService.php`
    - Contiene la lógica de negocio.
    - Inyecta el Repository.
    - Llama a los métodos del repositorio.

3.  **Repository**: `Repositories/LevelRepository.php`
    - Interactúa con el Modelo de Eloquent.
    - Métodos: `dataTable` (usando el trait `HasDataTable`), `createOrUpdate`, `getActiveLevels`.

4.  **Request**: `Http/Requests/Level/LevelRequest.php`
    - Valida los datos de entrada para crear o actualizar.
    - Define las reglas (`rules`), mensajes (`messages`) y atributos (`attributes`).

5.  **Resources**:
    - `Http/Resources/Level/LevelDataTableItemResource.php`: Formatea los datos para la tabla.
    - `Http/Resources/Level/LevelSelectItemResource.php`: Formatea los datos para selectores (combo boxes).

## Pasos para Implementar

1.  **Copiar el Patrón Branch**:
    - Tome como base los archivos de `Branch` y cópielos renombrándolos con el nombre de su nueva entidad.

2.  **Ajustar Namespaces y Clases**:
    - Actualice el `namespace` y el nombre de la `class` en cada archivo.
    - Asegúrese de importar el Modelo correcto (ej. `App\Models\Academy\Level`).

3.  **Definir Campos y Validación**:
    - En el **Request**, ajuste las reglas de validación según los campos de la tabla en la base de datos.
    - En los **Resources**, mapee los campos del modelo a la estructura JSON deseada.

4.  **Registrar Rutas**:
    - Edite `app/Modules/Administrator/Academy/Http/academy.api.php`.
    - Agregue un grupo de rutas similar al de `branches`:

    ```php
    use App\Modules\Administrator\Academy\Http\Controllers\LevelController;

    Route::middleware(['auth:api'])->prefix('/levels')->group(function () {
        Route::post('/data-table', [LevelController::class, 'dataTable'])->name('levels.dataTable');
        Route::post('/save', [LevelController::class, 'save'])->name('levels.save');
        Route::post('/select-items', [LevelController::class, 'selectItems'])->name('levels.selectItems');
    });
    ```

## Notas Adicionales

- **No inventar**: Siga estrictamente el patrón existente.
- **Sin Tests**: Para estos CRUDs simples, no se requieren pruebas automatizadas ni ejecución de comandos complejos.
- **Modelos**: Asegúrese de que el Modelo (`App\Models\Academy\Level`) tenga definidos los `$fillable` y `$casts` correctos.
