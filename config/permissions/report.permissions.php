<?php
return [
    [
        'name' => 'report',
        'display_name' => 'Reportes',
        'type' => 'feature',
        'level' => '1',
        'parent_id' => null,
        'children' => [
            [
                'name' => 'report.academy',
                'display_name' => 'Reportes de Academia',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'report.academy.view',
                        'display_name' => 'Ver Reportes de Academia',
                        'type' => 'view',
                    ],
                ]
            ],
            [
                'name' => 'report.barbershop',
                'display_name' => 'Reportes de Barbería',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'report.barbershop.view',
                        'display_name' => 'Ver Reportes de Barbería',
                        'type' => 'view',
                    ],
                ]
            ],
            [
                'name' => 'report.treasury',
                'display_name' => 'Reportes de Tesorería',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'report.treasury.view',
                        'display_name' => 'Ver Reportes de Tesorería',
                        'type' => 'view',
                    ],
                ]
            ],
            [
                'name' => 'report.inventory',
                'display_name' => 'Reportes de Inventario',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'report.inventory.view',
                        'display_name' => 'Ver Reportes de Inventario',
                        'type' => 'view',
                    ],
                ]
            ],
        ]
    ]
];
