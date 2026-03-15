
<?php


use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Security\Http\Controllers\RoleController;
use App\Modules\Administrator\Security\Http\Controllers\AdminController;

Route::middleware(['auth:api'])->prefix('/admins')->group(function () {
    Route::post('/data-table', [AdminController::class, 'dataTable'])->name('admins.dataTable')->middleware('permission:security.admin.view');
    Route::post('/save', [AdminController::class, 'save'])->name('admins.save')->middleware('permission:security.admin.create,security.admin.edit');
    Route::post('/sync-infrastructures', [AdminController::class, 'syncInfrastructures'])->name('admins.syncInfrastructures')->middleware('permission:security.admin.sync_infrastructures');
});

Route::middleware(['auth:api'])->prefix('/roles')->group(function () {
    Route::post('/data-table', [RoleController::class, 'dataTable'])->name('roles.dataTable')->middleware('permission:security.role.view');
    Route::post('/save', [RoleController::class, 'save'])->name('roles.save')->middleware('permission:security.role.create,security.role.edit');
    Route::get('/select-items', [RoleController::class, 'selectItems'])->name('roles.selectItems');
    Route::get('/permissions/{level}', [RoleController::class, 'permissions'])->name('roles.permissions');
    Route::post('/sync-permissions', [RoleController::class, 'syncPermissions'])->name('roles.syncPermissions')->middleware('permission:security.role.sync_permissions');
});
