<?php
return [
    [
        'name' => 'security',
        'display_name' => 'Seguridad',
        'type' => 'feature',
        'level' => '1',
        'parent_id' => null,
        'children' => [
            [
                'name' => 'security.admin',
                'display_name' => 'Administradores',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'security.admin.view',
                        'display_name' => 'Gestionar Administradores',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'security.admin.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'security.admin.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'security.admin.sync_infrastructures', 'display_name' => 'Asignar Infraestructuras', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'security.role',
                'display_name' => 'Roles',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'security.role.view',
                        'display_name' => 'Gestionar Roles',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'security.role.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'security.role.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'security.role.sync_permissions', 'display_name' => 'Asignar Permisos', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
        ]
    ]
];
