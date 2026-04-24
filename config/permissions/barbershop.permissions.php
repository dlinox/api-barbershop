<?php
return [
    [
        'name' => 'barbershop_panel',
        'display_name' => 'Panel Barbería',
        'type' => 'feature',
        'level' => '1',
        'parent_id' => null,
        'children' => [
            [
                'name' => 'barbershop_panel.dashboard',
                'display_name' => 'Dashboard',
                'type' => 'module',
                'children' => [
                    ['name' => 'barbershop_panel.dashboard.general', 'display_name' => 'Dashboard Barbería',   'type' => 'view'],
                    ['name' => 'barbershop_panel.dashboard.finance', 'display_name' => 'Dashboard Financiero', 'type' => 'view'],
                ],
            ],
            [
                'name' => 'barbershop_panel.reception',
                'display_name' => 'Recepción',
                'type' => 'module',
                'children' => [
                    ['name' => 'barbershop_panel.reception.cash_session', 'display_name' => 'Apertura de Caja', 'type' => 'view'],
                    ['name' => 'barbershop_panel.reception.pos',          'display_name' => 'Punto de Venta',   'type' => 'view'],
                    ['name' => 'barbershop_panel.reception.ticket',       'display_name' => 'Tickets',          'type' => 'view'],
                ],
            ],
            [
                'name' => 'barbershop_panel.persons',
                'display_name' => 'Personas',
                'type' => 'module',
                'children' => [
                    ['name' => 'barbershop_panel.persons.barber', 'display_name' => 'Barberos',      'type' => 'view'],
                    ['name' => 'barbershop_panel.persons.worker', 'display_name' => 'Trabajadores',  'type' => 'view'],
                    ['name' => 'barbershop_panel.persons.client', 'display_name' => 'Clientes',      'type' => 'view'],
                ],
            ],
            [
                'name' => 'barbershop_panel.attendance',
                'display_name' => 'Asistencias',
                'type' => 'module',
                'children' => [
                    ['name' => 'barbershop_panel.attendance.barber', 'display_name' => 'Asistencia Barberos',      'type' => 'view'],
                    ['name' => 'barbershop_panel.attendance.worker', 'display_name' => 'Asistencia Trabajadores',  'type' => 'view'],
                ],
            ],
            [
                'name' => 'barbershop_panel.config',
                'display_name' => 'Configuración',
                'type' => 'module',
                'children' => [
                    ['name' => 'barbershop_panel.config.category', 'display_name' => 'Categorías', 'type' => 'view'],
                    ['name' => 'barbershop_panel.config.service',  'display_name' => 'Servicios',  'type' => 'view'],
                ],
            ],
            [
                'name' => 'barbershop_panel.inventory',
                'display_name' => 'Inventario',
                'type' => 'module',
                'children' => [
                    ['name' => 'barbershop_panel.inventory.supplier',       'display_name' => 'Proveedores',      'type' => 'view'],
                    ['name' => 'barbershop_panel.inventory.product',        'display_name' => 'Productos',        'type' => 'view'],
                    ['name' => 'barbershop_panel.inventory.stock',          'display_name' => 'Stock',            'type' => 'view'],
                    ['name' => 'barbershop_panel.inventory.purchase_order', 'display_name' => 'Órdenes de Compra','type' => 'view'],
                ],
            ],
            [
                'name' => 'barbershop_panel.report',
                'display_name' => 'Reportes',
                'type' => 'module',
                'children' => [
                    ['name' => 'barbershop_panel.report.view', 'display_name' => 'Ver Reportes', 'type' => 'view'],
                ],
            ],
            [
                'name' => 'barbershop_panel.finance',
                'display_name' => 'Finanzas',
                'type' => 'module',
                'children' => [
                    ['name' => 'barbershop_panel.finance.income',           'display_name' => 'Ingresos',               'type' => 'view'],
                    ['name' => 'barbershop_panel.finance.general_expense',  'display_name' => 'Gastos Generales',       'type' => 'view'],
                    ['name' => 'barbershop_panel.finance.barber_advance',   'display_name' => 'Adelantos Barberos',     'type' => 'view'],
                    ['name' => 'barbershop_panel.finance.barber_payment',   'display_name' => 'Pagos Barberos',         'type' => 'view'],
                    ['name' => 'barbershop_panel.finance.worker_advance',   'display_name' => 'Adelantos Trabajadores', 'type' => 'view'],
                    ['name' => 'barbershop_panel.finance.worker_payment',   'display_name' => 'Pagos Trabajadores',     'type' => 'view'],
                ],
            ],
        ],
    ],
];
