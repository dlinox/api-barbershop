<?php
return [
    [
        'name' => 'barbershop',
        'display_name' => 'Barbería',
        'type' => 'feature',
        'level' => '1',
        'parent_id' => null,
        'children' => [
            [
                'name' => 'barbershop.client',
                'display_name' => 'Clientes',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'barbershop.client.view',
                        'display_name' => 'Gestionar Clientes',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'barbershop.client.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'barbershop.client.edit', 'display_name' => 'Editar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'barbershop.barber',
                'display_name' => 'Barberos',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'barbershop.barber.view',
                        'display_name' => 'Gestionar Barberos',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'barbershop.barber.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'barbershop.barber.edit', 'display_name' => 'Editar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'barbershop.branch',
                'display_name' => 'Sucursales',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'barbershop.branch.view',
                        'display_name' => 'Gestionar Sucursales',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'barbershop.branch.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'barbershop.branch.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'barbershop.branch.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'barbershop.category',
                'display_name' => 'Categorías',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'barbershop.category.view',
                        'display_name' => 'Gestionar Categorías',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'barbershop.category.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'barbershop.category.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'barbershop.category.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'barbershop.service',
                'display_name' => 'Servicios',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'barbershop.service.view',
                        'display_name' => 'Gestionar Servicios',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'barbershop.service.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'barbershop.service.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'barbershop.service.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'barbershop.reservation',
                'display_name' => 'Reservaciones',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'barbershop.reservation.view',
                        'display_name' => 'Gestionar Reservaciones',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'barbershop.reservation.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'barbershop.reservation.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'barbershop.ticket',
                'display_name' => 'Tickets',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'barbershop.ticket.view',
                        'display_name' => 'Gestionar Tickets',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'barbershop.ticket.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'barbershop.ticket.cancel', 'display_name' => 'Cancelar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
        ]
    ]
];
