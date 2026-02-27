<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Academy\Services\EnrollmentService;
use App\Modules\Administrator\Academy\Http\Requests\Enrollment\EnrollmentWithIncomeRequest;
use App\Modules\Administrator\Academy\Http\Requests\Enrollment\EnrollmentRegisterPaymentRequest;
use App\Modules\Administrator\Academy\Http\Resources\Enrollment\EnrollmentDataTableItemResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EnrollmentController
{
    public function __construct(
        private EnrollmentService $enrollmentService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->enrollmentService->dataTable($request);
        $items['data'] = EnrollmentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(EnrollmentWithIncomeRequest $request)
    {
        $req = $request->validated();

        $this->enrollmentService->save($req);

        return ApiResponse::success(null, 'Registro guardado correctamente');
    }

    public function registerPayment(EnrollmentRegisterPaymentRequest $request)
    {
        $data = $request->validated();
        $this->enrollmentService->registerPayment($data);
        return ApiResponse::success(null, 'Registro guardado correctamente');
    }

    public function getEnrollment($id)
    {
        $enrollment = $this->enrollmentService->getEnrollment($id);
        $enrollment = new EnrollmentDataTableItemResource($enrollment);
        return ApiResponse::success($enrollment);
    }

    /**
     * Valida un array de datos usando las reglas, mensajes y atributos de un FormRequest.
     */
    private function validateWith($formRequest, array $data): array
    {
        $validator = Validator::make(
            $data,
            $formRequest->rules(),
            $formRequest->messages(),
            $formRequest->attributes()
        );

        if ($validator->fails()) {
            throw new HttpResponseException(
                ApiResponse::error(
                    'Los datos proporcionados no son válidos.',
                    $validator->errors()->messages(),
                    422,
                )
            );
        }

        return $validator->validated();
    }
}
