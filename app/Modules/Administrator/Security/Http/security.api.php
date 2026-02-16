
<?php


use Illuminate\Support\Facades\Route;
use App\Modules\Administrator\Security\Http\Controllers\RoleController;
use App\Modules\Administrator\Security\Http\Controllers\AdminController;

Route::middleware(['auth:api'])->prefix('/admins')->group(function () {
    Route::post('/data-table', [AdminController::class, 'dataTable'])->name('admins.dataTable');
    Route::post('/save', [AdminController::class, 'save'])->name('admins.save');
    // Route::get('/select-items', [AdminController::class, 'selectItems'])->name('admins.selectItems');
});

Route::middleware(['auth:api'])->prefix('/roles')->group(function () {
    Route::post('/data-table', [RoleController::class, 'dataTable'])->name('roles.dataTable');
    Route::post('/save', [RoleController::class, 'save'])->name('roles.save');
    Route::get('/select-items', [RoleController::class, 'selectItems'])->name('roles.selectItems');
});
