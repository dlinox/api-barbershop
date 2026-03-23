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
                'display_name' => 'Gastos de Caja',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'treasury.expense.view',
                        'display_name' => 'Gestionar Gastos de Caja',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'treasury.expense.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'treasury.expense.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'treasury.general_expense',
                'display_name' => 'Gastos Generales',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'treasury.general_expense.view',
                        'display_name' => 'Gestionar Gastos Generales',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'treasury.general_expense.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'treasury.general_expense.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'treasury.general_expense.cancel', 'display_name' => 'Anular', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'treasury.expense_type',
                'display_name' => 'Tipos de Gasto',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'treasury.expense_type.view',
                        'display_name' => 'Gestionar Tipos de Gasto',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'treasury.expense_type.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'treasury.expense_type.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'treasury.expense_type.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
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
