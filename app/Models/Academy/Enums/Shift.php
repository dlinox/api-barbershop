<?php

namespace App\Models\Academy\Enums;

enum Shift: string
{
    case Morning = 'morning';
    case Afternoon = 'afternoon';
    case Night = 'night';

    public function label(): string
    {
        return match ($this) {
            self::Morning => 'Mañana',
            self::Afternoon => 'Tarde',
            self::Night => 'Noche',
        };
    }
}
