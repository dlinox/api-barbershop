<?php
return [
    [
        'name' => 'hr',
        'display_name' => 'Recursos Humanos',
        'type' => 'feature',
        'level' => '1',
        'parent_id' => null,
        'children' => [
            [
                'name' => 'hr.worker',
                'display_name' => 'Trabajadores',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'hr.worker.view',
                        'display_name' => 'Gestionar Trabajadores',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'hr.worker.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'hr.worker.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'hr.worker.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
        ]
    ]
];
