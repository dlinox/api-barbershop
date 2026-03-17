<?php
return [
    [
        'name' => 'academy',
        'display_name' => 'Academia',
        'type' => 'feature',
        'level' => '1',
        'parent_id' => null,
        'children' => [
            [
                'name' => 'academy.branch',
                'display_name' => 'Sucursales',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.branch.view',
                        'display_name' => 'Gestionar Sucursales',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.branch.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'academy.branch.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'academy.branch.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'academy.room',
                'display_name' => 'Aulas',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.room.view',
                        'display_name' => 'Gestionar Aulas',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.room.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'academy.room.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'academy.room.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'academy.level',
                'display_name' => 'Niveles Académicos',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.level.view',
                        'display_name' => 'Gestionar Niveles',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.level.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'academy.level.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'academy.level.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'academy.schedule',
                'display_name' => 'Horarios',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.schedule.view',
                        'display_name' => 'Gestionar Horarios',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.schedule.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'academy.schedule.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'academy.schedule.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'academy.group',
                'display_name' => 'Grupos',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.group.view',
                        'display_name' => 'Gestionar Grupos',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.group.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'academy.group.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'academy.group.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                            ['name' => 'academy.group.assign_teacher', 'display_name' => 'Asignar Docente', 'type' => 'action'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'academy.student',
                'display_name' => 'Estudiantes',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.student.view',
                        'display_name' => 'Gestionar Estudiantes',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.student.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'academy.student.edit', 'display_name' => 'Editar', 'type' => 'action'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'academy.teacher',
                'display_name' => 'Docentes',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.teacher.view',
                        'display_name' => 'Gestionar Docentes',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.teacher.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'academy.teacher.edit', 'display_name' => 'Editar', 'type' => 'action'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'academy.enrollment',
                'display_name' => 'Matrículas',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.enrollment.view',
                        'display_name' => 'Gestionar Matrículas',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.enrollment.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'academy.enrollment.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'academy.enrollment.register_payment', 'display_name' => 'Registrar Pago', 'type' => 'action'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'academy.attendance',
                'display_name' => 'Asistencias',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.attendance.view',
                        'display_name' => 'Gestionar Asistencias',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.attendance.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'academy.attendance.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'academy.attendance.register', 'display_name' => 'Registrar Asistencia', 'type' => 'action'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'academy.enrollment_payment',
                'display_name' => 'Pagos de Matrícula',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.enrollment_payment.view',
                        'display_name' => 'Gestionar Pagos',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.enrollment_payment.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'academy.enrollment_payment.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'academy.enrollment_payment.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'academy.material',
                'display_name' => 'Materiales / Cursos',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.material.view',
                        'display_name' => 'Gestionar Materiales',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.material.create', 'display_name' => 'Crear', 'type' => 'action'],
                            ['name' => 'academy.material.edit', 'display_name' => 'Editar', 'type' => 'action'],
                            ['name' => 'academy.material.delete', 'display_name' => 'Eliminar', 'type' => 'action'],
                        ]
                    ]
                ]
            ],
            [
                'name' => 'academy.teacher_attendance',
                'display_name' => 'Asistencia de Docentes',
                'type' => 'module',
                'level' => '1',
                'children' => [
                    [
                        'name' => 'academy.teacher_attendance.view',
                        'display_name' => 'Gestionar Asistencia de Docentes',
                        'type' => 'view',
                        'children' => [
                            ['name' => 'academy.teacher_attendance.register', 'display_name' => 'Registrar Asistencia', 'type' => 'action'],
                            ['name' => 'academy.teacher_attendance.edit', 'display_name' => 'Editar', 'type' => 'action'],
                        ]
                    ]
                ]
            ],
        ]
    ]
];
