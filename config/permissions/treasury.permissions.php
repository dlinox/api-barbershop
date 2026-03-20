<?php
return [
    [
        'name' => 'treasury',
        'display_name' => 'Tesorería',
        'type' => 'feature',
        'level' => '1',
        'parent_id' => null,
        'children' => [
            [
                'name' => 'treasury.cash_register',
                'display_name' => 'Cajas Registradoras',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'treasury.cash_register.view',
                        'display_name' => 'Gestionar Cajas',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'treasury.cash_register.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'treasury.cash_register.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'treasury.cash_register.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'treasury.cash_session',
                'display_name' => 'Sesiones de Caja',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'treasury.cash_session.view',
                        'display_name' => 'Gestionar Sesiones',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'treasury.cash_session.open', 'display_name' => 'Abrir Sesión', 'type' => 'action'],
                            ['name' => 'treasury.cash_session.close', 'display_name' => 'Cerrar Sesión', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'treasury.expense',
                'display_name' => 'Gastos',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'treasury.expense.view',
                        'display_name' => 'Gestionar Gastos',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'treasury.expense.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'treasury.expense.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'treasury.worker_attendance',
                'display_name' => 'Asistencia de Trabajadores',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'treasury.worker_attendance.view',
                        'display_name' => 'Ver Asistencia',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'treasury.worker_attendance.register', 'display_name' => 'Registrar', 'type' => 'action'],
                            ['name' => 'treasury.worker_attendance.edit', 'display_name' => 'Editar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'treasury.worker',
                'display_name' => 'Trabajadores',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'treasury.worker.view',
                        'display_name' => 'Gestionar Trabajadores',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'treasury.worker.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'treasury.worker.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'treasury.worker.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
        ]
    ]
];
