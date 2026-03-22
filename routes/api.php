<?php

use Illuminate\Support\Facades\Route;
use App\Common\Http\Controllers\ServerTimeController;
use App\Common\Http\Controllers\DashboardController;
use App\Common\Helpers\PdfHelper;

Route::middleware('auth:api')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'API is running',
            'version' => '1.0.0'
        ]);
    });

    Route::get('/server-time', [ServerTimeController::class, 'getServerTime']);

    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/summary', [DashboardController::class, 'summary']);
        Route::get('/revenue-chart', [DashboardController::class, 'revenueChart']);
        Route::get('/tickets-by-barber', [DashboardController::class, 'ticketsByBarber']);
        Route::get('/top-services', [DashboardController::class, 'topServices']);
        Route::get('/enrollments-by-group', [DashboardController::class, 'enrollmentsByGroup']);
        Route::get('/attendance-overview', [DashboardController::class, 'attendanceOverview']);
        Route::get('/cash-flow-chart', [DashboardController::class, 'cashFlowChart']);
        Route::get('/low-stock-alerts', [DashboardController::class, 'lowStockAlerts']);
        Route::get('/recent-tickets', [DashboardController::class, 'recentTickets']);
        Route::get('/reservations-by-status', [DashboardController::class, 'reservationsByStatus']);
        Route::get('/payroll-summary', [DashboardController::class, 'payrollSummary']);
    });
});

// Test PDF
Route::get('/test-pdf', function () {
    $html = file_get_contents(resource_path('templates/test/test.html'));
    $html = str_replace('{{date}}', now()->format('d/m/Y H:i:s'), $html);
    $mpdf = PdfHelper::createFromHtml($html);

    return PdfHelper::inline($mpdf, 'test.pdf');
});

// Test Ficha de Matrícula (usa data hardcodeada para preview sin BD)
Route::get('/test-ficha-matricula', function () {
    $data = [
        'enrollment_id'     => 42,
        'branch_name'       => 'Sede Principal - Lima',
        'student_full_name' => 'Carlos Andrés Ramírez Torres',
        'document_type'     => 'DNI',
        'document_number'   => '72345678',
        'date_birth'        => '15/03/2010',
        'gender'            => 'Masculino',
        'phone'             => '987 654 321',
        'email'             => 'carlos.ramirez@email.com',
        'address'           => 'Av. Los Olivos 1234, San Isidro, Lima',
        'guardians'         => [
            ['full_name' => 'María Torres de Ramírez', 'kinship' => 'Madre', 'phone' => '912 345 678'],
            ['full_name' => 'Pedro Ramírez Soto', 'kinship' => 'Padre', 'phone' => '998 765 432'],
        ],
        'group_name'        => 'Grupo A - Básico 2026-I',
        'level_name'        => 'Básico',
        'schedule_shift'    => 'Mañana',
        'schedule_time'     => '09:00 - 11:00',
        'days_of_week'      => 'Lunes, Miércoles, Viernes',
        'start_date'        => '01/04/2026',
        'end_date'          => '30/06/2026',
        'enrollment_date'   => now()->format('d/m/Y'),
        'enrollment_status' => 'Activa',
        'payment_plans'     => [
            ['type' => 'Matrícula', 'start_date' => '01/04/2026', 'end_date' => '01/04/2026', 'amount' => 150.00],
            ['type' => 'Mensualidad', 'start_date' => '01/04/2026', 'end_date' => '30/04/2026', 'amount' => 200.00],
            ['type' => 'Mensualidad', 'start_date' => '01/05/2026', 'end_date' => '31/05/2026', 'amount' => 200.00],
            ['type' => 'Mensualidad', 'start_date' => '01/06/2026', 'end_date' => '30/06/2026', 'amount' => 200.00],
        ],
        'materials' => [
            ['name' => 'Kit de peines profesionales', 'quantity' => 1],
            ['name' => 'Capa de corte', 'quantity' => 1],
            ['name' => 'Tijeras de práctica', 'quantity' => 2],
        ],
        'generated_at' => now()->format('d/m/Y H:i:s'),
    ];

    $html = view('enrollments.registration-certificate', $data)->render();
    $footerHtml = view('enrollments.common.footer', $data)->render();
    $mpdf = PdfHelper::createFromHtml($html, footerHtml: $footerHtml);

    return PdfHelper::inline($mpdf, 'ficha-matricula.pdf');
});

// Module routes are now registered in AppServiceProvider
