<?php

namespace App\Modules\Administrator\Setting\Http\Requests\CalendarHoliday;

use App\Common\Http\Requests\ApiFormRequest;

class CalendarHolidayRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'required|integer|exists:core_calendar_holidays,id' : 'nullable',
            'date' => $id 
                ? 'required|date|unique:core_calendar_holidays,date,' . $id 
                : 'required|date|unique:core_calendar_holidays,date',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'El ID es requerido',
            'id.integer' => 'El ID debe ser un número entero',
            'id.exists' => 'El día festivo no existe',
            'date.required' => 'La fecha es requerida',
            'date.date' => 'La fecha debe ser una fecha válida',
            'date.unique' => 'Ya existe un evento para esta fecha',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un máximo de 200 caracteres',
            'description.string' => 'La descripción debe ser una cadena de texto',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser verdadero o falso',
        ];
    }
}
