<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Group;

use App\Common\Http\Requests\ApiFormRequest;

class GroupRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:academy_groups,id' : 'nullable',
            // 'branch_id' => 'required|exists:academy_branches,id',
            'level_id' => 'required|exists:academy_levels,id',
            'schedule_id' => 'required|exists:academy_schedules,id',
            'room_id' => 'required|exists:academy_rooms,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'enrollment_price' => 'required|numeric|min:0',
            'monthly_price' => 'required|numeric|min:0',
            'days_of_week' => 'array|required|min:1|max:7',
            'days_of_week.*' => 'string',
            'attendance_tolerance_minutes' => 'nullable|integer|min:0',
            'payment_plans' => 'array|required|min:1',
            'payment_plans.*.start_date' => 'required|date',
            'payment_plans.*.end_date' => 'required|date|after_or_equal:payment_plans.*.start_date',
            'payment_plans.*.amount' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El grupo seleccionado no existe',
            // 'branch_id.required' => 'La sucursal es requerida',
            // 'branch_id.exists' => 'La sucursal seleccionada no existe',
            'level_id.required' => 'El nivel es requerido',
            'level_id.exists' => 'El nivel seleccionado no existe',
            'schedule_id.required' => 'El horario es requerido',
            'schedule_id.exists' => 'El horario seleccionado no existe',
            'room_id.required' => 'El aula es requerida',
            'room_id.exists' => 'El aula seleccionada no existe',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser texto',
            'name.max' => 'El nombre no debe exceder los 255 caracteres',
            'start_date.required' => 'La fecha de inicio es requerida',
            'start_date.date' => 'La fecha de inicio debe ser una fecha válida',
            'end_date.required' => 'La fecha de fin es requerida',
            'end_date.date' => 'La fecha de fin debe ser una fecha válida',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
            'enrollment_price.required' => 'El precio de inscripción es requerido',
            'enrollment_price.numeric' => 'El precio de inscripción debe ser un número',
            'enrollment_price.min' => 'El precio de inscripción no puede ser negativo',
            'monthly_price.required' => 'La mensualidad es requerida',
            'monthly_price.numeric' => 'La mensualidad debe ser un número',
            'monthly_price.min' => 'La mensualidad no puede ser negativa',
            'days_of_week.array' => 'Los días deben ser un array',
            'days_of_week.min' => 'Debe seleccionar al menos un día',
            'days_of_week.max' => 'No puede seleccionar más de 7 días',
            'days_of_week.*.string' => 'Los días deben ser texto',
            'attendance_tolerance_minutes.integer' => 'La tolerancia de asistencia debe ser un número entero',
            'attendance_tolerance_minutes.min' => 'La tolerancia de asistencia no puede ser negativa',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser verdadero o falso',

            'payment_plans.required' => 'Los planes de pago son requeridos',
            'payment_plans.array' => 'Los planes de pago deben ser un array',
            'payment_plans.min' => 'Debe seleccionar al menos un plan de pago',
            'payment_plans.*.start_date.required' => 'La fecha de inicio es requerida',
            'payment_plans.*.start_date.date' => 'La fecha de inicio debe ser una fecha válida',
            'payment_plans.*.end_date.required' => 'La fecha de fin es requerida',
            'payment_plans.*.end_date.date' => 'La fecha de fin debe ser una fecha válida',
            'payment_plans.*.end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
            'payment_plans.*.amount.required' => 'El monto es requerido',
            'payment_plans.*.amount.numeric' => 'El monto debe ser un número',
            'payment_plans.*.amount.min' => 'El monto no puede ser negativo',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            // 'branch_id' => 'Sucursal',
            'level_id' => 'Nivel',
            'schedule_id' => 'Horario',
            'room_id' => 'Aula',
            'name' => 'Nombre',
            'start_date' => 'Fecha de Inicio',
            'end_date' => 'Fecha de Fin',
            'enrollment_price' => 'Precio de Inscripción',
            'monthly_price' => 'Mensualidad',
            'days_of_week' => 'Días',
            'attendance_tolerance_minutes' => 'Tolerancia de Asistencia (minutos)',
            'is_active' => 'Estado',

            'payment_plans' => 'Planes de Pago',
            'payment_plans.*.start_date' => 'Fecha de Inicio',
            'payment_plans.*.end_date' => 'Fecha de Fin',
            'payment_plans.*.amount' => 'Monto',
        ];
    }
}
