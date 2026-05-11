<?php

namespace App\Modules\AcademyPanel\Academy\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;
use App\Models\Behavior\Profile;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class StudentGuardianRequest extends ApiFormRequest
{
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        // Auto-fill student_id from the authenticated student's JWT profile
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $profileId = $payload->get('prf');

            if ($profileId) {
                $profile = Profile::find($profileId);
                if ($profile) {
                    $this->merge(['student_id' => $profile->profileable_id]);
                }
            }
        } catch (\Exception) {
            // Let validation handle the missing student_id
        }
    }

    public function rules(): array
    {
        $id = $this->id ?? null;

        return [
            'id'        => $id ? 'exists:academy_student_guardians,id' : 'nullable',
            'student_id' => ['required', 'integer', 'exists:profile_students,core_person_id'],
            'full_name'  => ['required', 'string', 'max:150'],
            'kinship'    => ['required', 'string', 'max:50'],
            'phone'      => ['required', 'string', 'max:15'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists'          => 'El apoderado no existe',
            'student_id.required' => 'El estudiante es requerido',
            'student_id.exists'  => 'El estudiante no existe',
            'full_name.required' => 'El nombre completo es requerido',
            'full_name.max'      => 'El nombre completo debe tener máximo 150 caracteres',
            'kinship.required'   => 'El parentesco es requerido',
            'kinship.max'        => 'El parentesco debe tener máximo 50 caracteres',
            'phone.required'     => 'El celular es requerido',
            'phone.max'          => 'El celular debe tener máximo 15 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'         => 'ID',
            'student_id' => 'Estudiante',
            'full_name'  => 'Nombre completo',
            'kinship'    => 'Parentesco',
            'phone'      => 'Celular',
        ];
    }
}
