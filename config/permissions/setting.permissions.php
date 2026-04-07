<?php
return [
    [
        'name' => 'setting',
        'display_name' => 'Configuración',
        'type' => 'feature',
        'level' => '1',
        'parent_id' => null,
        'children' => [
            [
                'name' => 'setting.payment_method',
                'display_name' => 'Métodos de Pago',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'setting.payment_method.view',
                        'display_name' => 'Gestionar Métodos de Pago',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'setting.payment_method.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'setting.payment_method.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'setting.payment_method.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'setting.document_type',
                'display_name' => 'Tipos de Documento',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'setting.document_type.view',
                        'display_name' => 'Gestionar Tipos de Documento',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'setting.document_type.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'setting.document_type.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'setting.document_type.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'setting.calendar_holiday',
                'display_name' => 'Días Festivos',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'setting.calendar_holiday.view',
                        'display_name' => 'Gestionar Días Festivos',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'setting.calendar_holiday.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'setting.calendar_holiday.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'setting.calendar_holiday.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'setting.company',
                'display_name' => 'Datos de la Empresa',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'setting.company.view',
                        'display_name' => 'Gestionar Datos de la Empresa',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'setting.company.edit', 'display_name' => 'Editar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'setting.employee_schedule',
                'display_name' => 'Horarios de Empleados',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'setting.employee_schedule.view',
                        'display_name' => 'Gestionar Horarios de Empleados',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'setting.employee_schedule.edit', 'display_name' => 'Editar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
        ]
    ]
];
