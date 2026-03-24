<?php

use Illuminate\Support\Facades\Route;
use App\Common\Http\Controllers\ServerTimeController;
use App\Common\Helpers\PdfHelper;

Route::middleware('auth:api')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'API is running',
            'version' => '1.0.0'
        ]);
    });

    Route::get('/server-time', [ServerTimeController::class, 'getServerTime']);

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

// Test Comprobante de Ingreso (mock data)
Route::get('/test-receipt', function () {
    $data = [
        'company' => (object) [
            'trade_name' => 'BarberShop Pro',
            'name'       => 'BarberShop Pro S.A.C.',
            'ruc'        => '20612345678',
            'address'    => 'Av. Los Héroes 456, Lima',
            'phone'      => '(01) 234-5678',
        ],

        'receipt_full_number' => 'B001-00000042',
        'transaction_date'    => now()->format('d/m/Y'),
        'status'              => 'Completado',

        'client_name'     => 'Juan Carlos Pérez López',
        'client_document' => '72345678',

        'details' => [
            ['description' => 'Corte de cabello clásico',   'quantity' => 1, 'unit_price' => 35.00, 'discount' => 0,    'subtotal' => 35.00],
            ['description' => 'Afeitado con navaja',         'quantity' => 1, 'unit_price' => 25.00, 'discount' => 5.00, 'subtotal' => 20.00],
            ['description' => 'Tinte de barba',              'quantity' => 1, 'unit_price' => 40.00, 'discount' => 0,    'subtotal' => 40.00],
        ],

        'subtotal' => 100.00,
        'discount' => 5.00,
        'tax'      => 0,
        'total'    => 95.00,

        'payment_methods' => [
            ['method' => 'Efectivo', 'amount' => 50.00, 'reference' => null],
            ['method' => 'Yape',     'amount' => 45.00, 'reference' => '987654321'],
        ],

        'observations'  => 'Cliente frecuente — aplicar descuento preferencial.',
        'generated_by'  => 'admin',
        'generated_at'  => now()->format('d/m/Y H:i'),
    ];

    $html = view('incomes.receipt', $data)->render();
    $mpdf = PdfHelper::createFromHtml($html);

    return PdfHelper::inline($mpdf, 'test-receipt.pdf');
});

// Module routes are now registered in AppServiceProvider
