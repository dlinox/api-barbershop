---
name: permissions_management
description: Guía sobre cómo crear permisos, sembrarlos en base de datos y proteger rutas de API
---

# Gestión de Permisos y Rutas

Esta habilidad documenta el proceso y convenciones para crear, gestionar y asignar permisos a las rutas del API dentro del proyecto.

## 1. Creación de Nuevos Permisos

Los permisos no se hardcodean en la base de datos, sino que se declaran en archivos de configuración.

- **Ubicación:** `config/permissions/{nombre_modulo}.permissions.php`
- **Estructura típica:**

```php
return [
    [
        'name' => 'modulo.recurso',
        'display_name' => 'Nombre Descriptivo',
        'type' => 'module', // Tipos comunes: module, view, action
        'level' => '1', // 1: Admin. Solo es necesario definirlo en el padre absoluto.
        'children' => [
            [
                'name' => 'modulo.recurso.view',
                'display_name' => 'Ver Recurso',
                'type' => 'view',
                'children' => [
                    [
                        'name' => 'modulo.recurso.create',
                        'display_name' => 'Crear Recurso',
                        'type' => 'action',
                    ],
                    // más acciones...
                ],
            ],
        ],
    ]
];
```

**Nota Importante:** El atributo `level` se hereda del nodo padre recursivamente gracias al seeder, por lo que solo necesitas poner el de más alto nivel.

## 2. Reflejar los Permisos en Base de Datos

Una vez creado o actualizado el archivo en `config/permissions/`, hay que ejecutar el seeder especializado, el cual actualizará o creará un registro en la tabla `behavior_permissions`:

```bash
php artisan db:seed --class=PermissionSeeder
```

## 3. Localizar los Archivos de Rutas

Este proyecto **no** expone sus endpoints principales en `routes/api.php` de forma convencional. El proyecto está altamente modularizado.

Para encontrar las rutas correspondientes a un módulo, busca bajo el directorio `app/Modules/`:

- **Patrón de ruta:** `app/Modules/{Rol}/{Modulo}/Http/{modulo}.api.php`
- **Ejemplo (Academy para Administrator):** `app/Modules/Administrator/Academy/Http/academy.api.php`

## 4. Proteger Rutas con Middleware Custom

Para asignar permisos a un endpoint, se utiliza el middleware custom `permission`.

**CRÍTICO:** Cuando un endpoint requiera **más de un permiso (Lógica OR)**, el delimitador que usa el middleware es siempre una **COMA (`,`)**. NUNCA usar la pleca/pipe (`|`).

- **Middleware filepath:** `app/Common/Http/Middleware/CheckPermission.php`

**Ejemplo correcto:**

```php
Route::middleware(['auth:api'])->prefix('/resources')->group(function () {
    // Un solo permiso
    Route::post('/data-table', [ResourceController::class, 'dataTable'])
        ->name('resources.dataTable')
        ->middleware('permission:modulo.recurso.view');

    // Múltiples permisos (Ej: puede crear O editar)
    Route::post('/save', [ResourceController::class, 'save'])
        ->name('resources.save')
        ->middleware('permission:modulo.recurso.create,modulo.recurso.edit'); // <-- Coma (,) !!
});
```

## 5. Exámenes y Criterio en Rutas GET

**IMPORTANTE (Pensamiento Crítico):** NO TODAS las rutas de tipo `GET` deben ir sin permisos. Al asegurar rutas de lectura, se debe aplicar un razonamiento analítico riguroso sobre QUÉ datos devuelve el endpoint:

- **Listados Secundarios o Selectores Básicos:** Las rutas que obtienen listados simples o "select-items" (generalmente para combos, dropdowns o select filters) **no** deben llevar validación del middleware de permisos si su información no representa riesgo de seguridad o fuga de datos. Restringirlas por defecto causaría errores en cascada entre módulos (por ejemplo: rellenar el selector de 'branches' en el formulario de creación de otro módulo fallaría si ese usuario no tiene permiso explícito de ver todos los branches).
- **Consultas con Datos Sensibles o Vistas Protagonistas:** Por el contrario, rutas de tipo GET que exponen listados completos (`get-active-and-upcoming`, exportar reportes, listado detallado de un recurso directo a la vista principal, listados paginados) o traen información privada o financiera, **SÍ** deben estar protegidos bajo el respectivo permiso de lectura (`.view` por ejemplo).

**Regla de oro:** Piensa de manera lógica y crítica si dejar el endpoint expuesto (concenso "público" dentro de `auth:api`) es indispensable para otros catálogos del sistema y es seguro. 

**Ejemplo correcto de una ruta exenta por su reusabilidad como diccionario interno (sin middleware `permission`):**
```php
Route::get('/select-items', [BranchController::class, 'selectItems'])->name('branches.selectItems');
```

## 6. Persistencia de Permisos en el FrontEnd (Vue 3)

Al manejar permisos en el UI se requiere ocultar y/o bloquear accesos a vistas, menús y botones para que concuerden con las restricciones del backend.

### A. Proteger Vistas completas a través de Vue Router
Se debe establecer el permiso base o de visualización en el archivo de rutas del módulo (Ej: `academy.routes.ts`) bajo la propiedad `meta.permissions`:

```typescript
  {
    path: "branch",
    name: "BranchView",
    component: () => import("@/ui/modules/Administrator/Academy/Branch/views/Branch.view.vue"),
    meta: {
      requiresAuth: true,
      permissions: ["academy.branch.view"], // Validación array requerida para permitir entrada a la vista
    },
  },
```

### B. Proteger UI y botones dentro del Componente
El proyecto cuenta con una directiva de Vue `v-permission` registrada globalmente (`app.directive("permission", permissionDirective);`). Es la forma recomendada, limpia y directa para ocultar o deshabilitar elementos del DOM según el permiso del usuario, sin necesidad de inyectar el store manualmente.

```vue
<template>
  <!-- Ocultar botón de Nuevo mediante Directiva -->
  <v-btn v-permission="['academy.branch.create']">Nuevo</v-btn>

  <!-- Ocultar opción del Menú Lateral -->
  <v-list-item v-permission="['academy.branch.view']" title="Sedes" :to="{ name: 'BranchView' }" />
</template>
```

**Nota Frontend:** La directiva recibe un arreglo de strings (e.j. `['academy.branch.delete']`). Internamente remueve o deshabilita el elemento HTML del DOM si el usuario autenticado en sesión no cumple el privilegio.
