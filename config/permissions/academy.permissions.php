<?php
return [
    [
        'name' => 'academy_panel',
        'display_name' => 'Panel Academia',
        'type' => 'feature',
        'level' => '1',
        'parent_id' => null,
        'children' => [
            [
                'name' => 'academy_panel.dashboard',
                'display_name' => 'Dashboard',
                'type' => 'module',
                'children' => [
                    ['name' => 'academy_panel.dashboard.general', 'display_name' => 'Dashboard Academia',    'type' => 'view'],
                    ['name' => 'academy_panel.dashboard.finance', 'display_name' => 'Dashboard Financiero', 'type' => 'view'],
                ],
            ],
            [
                'name' => 'academy_panel.group',
                'display_name' => 'Grupos',
                'type' => 'module',
                'children' => [
                    ['name' => 'academy_panel.group.view', 'display_name' => 'Grupos', 'type' => 'view'],
                ],
            ],
            [
                'name' => 'academy_panel.students',
                'display_name' => 'Estudiantes',
                'type' => 'module',
                'children' => [
                    ['name' => 'academy_panel.students.db',         'display_name' => 'DB Estudiantes',          'type' => 'view'],
                    ['name' => 'academy_panel.students.enrollment', 'display_name' => 'Inscripciones',           'type' => 'view'],
                    ['name' => 'academy_panel.students.attendance', 'display_name' => 'Asistencia Estudiantes', 'type' => 'view'],
                ],
            ],
            [
                'name' => 'academy_panel.teachers',
                'display_name' => 'Docentes',
                'type' => 'module',
                'children' => [
                    ['name' => 'academy_panel.teachers.db',         'display_name' => 'DB Docentes',         'type' => 'view'],
                    ['name' => 'academy_panel.teachers.attendance', 'display_name' => 'Asistencia Docentes', 'type' => 'view'],
                ],
            ],
            [
                'name' => 'academy_panel.workers',
                'display_name' => 'Trabajadores',
                'type' => 'module',
                'children' => [
                    ['name' => 'academy_panel.workers.db',         'display_name' => 'DB Trabajadores',         'type' => 'view'],
                    ['name' => 'academy_panel.workers.attendance', 'display_name' => 'Asistencia Trabajadores', 'type' => 'view'],
                ],
            ],
            [
                'name' => 'academy_panel.reception',
                'display_name' => 'Recepción',
                'type' => 'module',
                'children' => [
                    ['name' => 'academy_panel.reception.cash_session', 'display_name' => 'Apertura de Caja', 'type' => 'view'],
                    ['name' => 'academy_panel.reception.pos',          'display_name' => 'Punto de Venta',   'type' => 'view'],
                ],
            ],
            [
                'name' => 'academy_panel.inventory',
                'display_name' => 'Inventario',
                'type' => 'module',
                'children' => [
                    ['name' => 'academy_panel.inventory.stock',          'display_name' => 'Stock',             'type' => 'view'],
                    ['name' => 'academy_panel.inventory.purchase_order', 'display_name' => 'Órdenes de Compra', 'type' => 'view'],
                ],
            ],
            [
                'name' => 'academy_panel.finance',
                'display_name' => 'Finanzas',
                'type' => 'module',
                'children' => [
                    ['name' => 'academy_panel.finance.income',           'display_name' => 'Ingresos',               'type' => 'view'],
                    ['name' => 'academy_panel.finance.general_expense',  'display_name' => 'Gastos Generales',       'type' => 'view'],
                    ['name' => 'academy_panel.finance.teacher_payment',  'display_name' => 'Pagos Docentes',         'type' => 'view'],
                    ['name' => 'academy_panel.finance.teacher_advance',  'display_name' => 'Adelantos Docentes',     'type' => 'view'],
                    ['name' => 'academy_panel.finance.worker_payment',   'display_name' => 'Pagos Trabajadores',     'type' => 'view'],
                    ['name' => 'academy_panel.finance.worker_advance',   'display_name' => 'Adelantos Trabajadores', 'type' => 'view'],
                ],
            ],
            [
                'name' => 'academy_panel.report',
                'display_name' => 'Reportes',
                'type' => 'module',
                'children' => [
                    ['name' => 'academy_panel.report.view', 'display_name' => 'Reportes Academia', 'type' => 'view'],
                ],
            ],
            [
                'name' => 'academy_panel.config',
                'display_name' => 'Configuración',
                'type' => 'module',
                'children' => [
                    ['name' => 'academy_panel.config.room',      'display_name' => 'Aulas',       'type' => 'view'],
                    ['name' => 'academy_panel.config.schedule',  'display_name' => 'Horarios',    'type' => 'view'],
                    ['name' => 'academy_panel.config.level',     'display_name' => 'Niveles',     'type' => 'view'],
                    ['name' => 'academy_panel.config.material',  'display_name' => 'Materiales',  'type' => 'view'],
                    ['name' => 'academy_panel.config.supplier',  'display_name' => 'Proveedores', 'type' => 'view'],
                    ['name' => 'academy_panel.config.product',   'display_name' => 'Productos',   'type' => 'view'],
                ],
            ],
        ],
    ],
];
