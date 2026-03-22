<?php

namespace App\Common\Enums;

enum DayOfWeek: string
{
    case Sunday = '0';
    case Monday = '1';
    case Tuesday = '2';
    case Wednesday = '3';
    case Thursday = '4';
    case Friday = '5';
    case Saturday = '6';

    public function label(): string
    {
        return match ($this) {
            self::Sunday => 'Dom',
            self::Monday => 'Lun',
            self::Tuesday => 'Mar',
            self::Wednesday => 'Mié',
            self::Thursday => 'Jue',
            self::Friday => 'Vie',
            self::Saturday => 'Sáb',
        };
    }
}
