<?php

namespace App\Models\Academy\Enums;

enum PaymentPlanType: string
{
    case Enrollment = 'enrollment';
    case Monthly = 'monthly';

    public function label(): string
    {
        return match ($this) {
            self::Enrollment => 'Matrícula',
            self::Monthly => 'Mensualidad',
        };
    }
}
