<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use App\Common\Helpers\PdfHelper;
use App\Common\Helpers\FileHelper;
use App\Common\Services\BasePdfService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Modules\Administrator\Academy\Repositories\EnrollmentRepository;
use App\Modules\Administrator\Academy\Repositories\EnrollmentPaymentRepository;
use App\Modules\Administrator\Treasury\Repositories\Actions\CreateIncomeAction;
use App\Modules\Administrator\Academy\Repositories\Actions\UpdateEnrollmentAction;
use App\Modules\Administrator\Academy\Repositories\Actions\SyncEnrollmentMaterialsAction;
use App\Modules\Administrator\Academy\Repositories\Queries\EnrollmentDetailQuery;

class EnrollmentService extends BasePdfService
{
    public function __construct(
        private EnrollmentRepository $enrollmentRepository,
        private EnrollmentPaymentRepository $enrollmentPaymentRepository,
        private CreateIncomeAction $createIncomeAction,
        private UpdateEnrollmentAction $updateEnrollmentAction,
        private SyncEnrollmentMaterialsAction $syncEnrollmentMaterialsAction,
        private EnrollmentDetailQuery $enrollmentDetailQuery,
    ) {}

    public function dataTable($request)
    {
        return $this->enrollmentRepository->dataTable($request);
    }

    public function save($data)
    {

        try {
            DB::beginTransaction();

            $enrollmentData = [
                'id' => $data['id'],
                'profile_student_id' => $data['student_id'],
                'group_id' => $data['group_id'],
                'date' => $data['date'],
            ];

            $enrollment = $this->enrollmentRepository->save($enrollmentData);

            // Obtener el infrastructure_id para el grupo
            $group = Group::with('branch.infrastructure')->findOrFail($data['group_id']);
            $infrastructureId = $group->branch->getInfrastructureId();

            // ─── Sincronizar materiales ───
            $this->syncEnrollmentMaterialsAction->execute(
                $enrollment->id,
                $data['materials'] ?? [],
                $infrastructureId,
            );

            $payments = $data['payments'];

            if (count($payments) > 0) {

                $enrollmentPayment = $enrollment->payments()->create([]);

                foreach ($payments as $payment) {
                    $enrollmentPayment->details()->create(
                        [
                            'enrollment_payment_id' => $enrollmentPayment->id,
                            'group_payment_plan_id' => $payment['plan_id'],
                            'type' => $payment['type'],
                            'subtotal' => $payment['subtotal'],
                            'discount' => $payment['discount'],
                            'total' => $payment['total'],
                        ]
                    );
                }

                // ─── Crear el income (comprobante de ingreso) ───
                $this->createIncomeAction->execute(
                    data: $data['income'],
                    infrastructureId: $infrastructureId,
                    transactionableType: 'academy_enrollment_payments',
                    transactionableId: $enrollmentPayment->id,
                );
            }

            DB::commit();
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function registerPayment($data)
    {
        try {
            DB::beginTransaction();

            $enrollmentPayment = $this->enrollmentPaymentRepository->create([
                'enrollment_id' => $data['enrollment_id'],
            ]);

            foreach ($data['payments'] as $payment) {
                $enrollmentPayment->details()->create([
                    'group_payment_plan_id' => $payment['plan_id'],
                    'type' => $payment['type'],
                    'subtotal' => $payment['subtotal'],
                    'discount' => $payment['discount'],
                    'total' => $payment['total'],
                ]);
            }

            // ─── Crear el income (comprobante de ingreso) ───
            $enrollment = Enrollment::findOrFail($data['enrollment_id']);
            $group = Group::with('branch.infrastructure')->findOrFail($enrollment->group_id);
            $infrastructureId = $group->branch->getInfrastructureId();

            $this->createIncomeAction->execute(
                data: $data['income'],
                infrastructureId: $infrastructureId,
                transactionableType: 'academy_enrollment_payments',
                transactionableId: $enrollmentPayment->id,
            );

            DB::commit();

            return;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getEnrollment($id)
    {
        return $this->enrollmentRepository->getEnrollment($id);
    }

    public function update($data)
    {
        $this->updateEnrollmentAction->execute($data);
    }

    private const PDF_TYPE = 'registration_certificate';
    private const PDF_DISK = 'local';
    private const PDF_FOLDER = 'enrollments';

    public function generatePdf($id)
    {
        $enrollment = Enrollment::findOrFail($id);

        // ─── Buscar archivo existente ───
        $existingFile = $enrollment->files()
            ->where('type', self::PDF_TYPE)
            ->first();

        if ($existingFile && FileHelper::fileExists($existingFile->disk, $existingFile->path)) {
            $content = file_get_contents(FileHelper::getFilePath($existingFile->disk, $existingFile->path));

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "inline; filename=\"{$existingFile->name}\"",
            ]);
        }

        // ─── Generar PDF ───
        $enrollmentLoaded = ($this->enrollmentDetailQuery)($id);

        $data = array_merge(
            $this->baseData(),
            $this->enrollmentDetailQuery->toBladeData($enrollmentLoaded),
        );

        $html = View::make('enrollments.registration-certificate', $data)->render();
        $mpdf = PdfHelper::createFromHtml($html);

        $pdfContent = $mpdf->Output('', 'S');
        $filename = FileHelper::generateUniqueFilename("ficha-matricula-{$id}", 'pdf');
        $path = self::PDF_FOLDER . '/' . $filename;

        // ─── Guardar en disco ───
        FileHelper::saveFile($pdfContent, $path, self::PDF_DISK);

        // ─── Eliminar registro anterior si existe (archivo ya no existe en disco) ───
        if ($existingFile) {
            $existingFile->delete();
        }

        // ─── Registrar en core_files ───
        $enrollment->files()->create([
            'type' => self::PDF_TYPE,
            'name' => "ficha-matricula-{$id}.pdf",
            'path' => $path,
            'disk' => self::PDF_DISK,
            'mime_type' => 'application/pdf',
            'size' => strlen($pdfContent),
        ]);

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"ficha-matricula-{$id}.pdf\"",
        ]);
    }

    public function getDetail($id)
    {
        return ($this->enrollmentDetailQuery)($id);
    }
}
