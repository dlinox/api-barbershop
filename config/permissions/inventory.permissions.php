<?php
return [
    [
        'name' => 'inventory',
        'display_name' => 'Inventario',
        'type' => 'feature',
        'level' => '1',
        'parent_id' => null,
        'children' => [
            [
                'name' => 'inventory.category',
                'display_name' => 'Categorías',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'inventory.category.view',
                        'display_name' => 'Gestionar Categorías',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'inventory.category.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'inventory.category.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'inventory.category.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'inventory.brand',
                'display_name' => 'Marcas',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'inventory.brand.view',
                        'display_name' => 'Gestionar Marcas',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'inventory.brand.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'inventory.brand.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'inventory.brand.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'inventory.supplier',
                'display_name' => 'Proveedores',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'inventory.supplier.view',
                        'display_name' => 'Gestionar Proveedores',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'inventory.supplier.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'inventory.supplier.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'inventory.supplier.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'inventory.product',
                'display_name' => 'Productos',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'inventory.product.view',
                        'display_name' => 'Gestionar Productos',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'inventory.product.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'inventory.product.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'inventory.product.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'inventory.stock',
                'display_name' => 'Stock',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'inventory.stock.view',
                        'display_name' => 'Gestionar Stock',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'inventory.stock.initialize', 'display_name' => 'Inicializar Stock', 'type' => 'action'],
                            ['name' => 'inventory.stock.adjust', 'display_name' => 'Ajustar Stock', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'inventory.kardex',
                'display_name' => 'Kardex',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'inventory.kardex.view',
                        'display_name' => 'Gestionar Kardex',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'inventory.kardex.register_movement', 'display_name' => 'Registrar Movimiento', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'inventory.purchase_order',
                'display_name' => 'Órdenes de Compra',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'inventory.purchase_order.view',
                        'display_name' => 'Gestionar Órdenes de Compra',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'inventory.purchase_order.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'inventory.purchase_order.receive', 'display_name' => 'Recibir Orden', 'type' => 'action'],
                            ['name' => 'inventory.purchase_order.cancel', 'display_name' => 'Cancelar Orden', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'inventory.sale',
                'display_name' => 'Ventas',
                'type' => 'module',
                'children' => [
                    [
                        'name' => 'inventory.sale.view',
                        'display_name' => 'Gestionar Ventas',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'inventory.sale.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'inventory.sale.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                            ['name' => 'inventory.sale.annul', 'display_name' => 'Anular', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
        ]
    ]
];
