<?php

namespace App\Common\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class DateHelper
{
    /**
     * Convierte una cadena CSV de números de días (0=Dom, 1=Lun, ..., 6=Sáb)
     * a una colección de nombres cortos de días en español.
     */
    public static function getDayNamesFromCsv(string $daysOfWeek): Collection
    {
        return collect(explode(',', $daysOfWeek))->map(function ($day) {
            return Carbon::now()
                ->startOfWeek(Carbon::SUNDAY)
                ->addDays((int) $day)
                ->locale('es')
                ->shortDayName;
        });
    }

    /**
     * Convierte una cadena CSV de números de días a un array de enteros.
     */
    public static function getDayNumbersFromCsv(?string $daysOfWeek): array
    {
        return $daysOfWeek ? array_map('intval', explode(',', $daysOfWeek)) : [];
    }

    /**
     * Formatea una fecha al formato d-m-Y (ej: 22-02-2026).
     */
    public static function formatDate(string $date, string $format = 'd-m-Y'): string
    {
        return Carbon::parse($date)->format($format);
    }

    /**
     * Formatea una hora al formato 12h (ej: 8:00 AM).
     */
    public static function formatTime(string $time, string $format = 'g:i A'): string
    {
        return Carbon::parse($time)->format($format);
    }
}
