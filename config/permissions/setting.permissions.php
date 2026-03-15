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
        ]
    ]
];
