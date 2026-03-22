<?php

namespace App\Models\Academy\Enums;

enum EnrollmentStatus: string
{
    case Active = 'active';
    case Cancelled = 'cancelled';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Activo',
            self::Cancelled => 'Cancelado',
            self::Completed => 'Completado',
        };
    }
}
