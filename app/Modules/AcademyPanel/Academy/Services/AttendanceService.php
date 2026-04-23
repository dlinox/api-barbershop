<?php

namespace App\Modules\AcademyPanel\Academy\Services;

use App\Common\Exceptions\ApiException;
use Illuminate\Http\Request;
use App\Modules\AcademyPanel\Academy\Repositories\AttendanceRepository;
use App\Modules\AcademyPanel\Academy\Repositories\GroupRepository;
use App\Models\Academy\Attendance;
use App\Models\Academy\Enrollment;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function __construct(
        private AttendanceRepository $attendanceRepository,
        private GroupRepository $groupRepository,
    ) {}

    public function dataTable(Request $request) { return $this->attendanceRepository->dataTable($request); }

    public function getGroupsByAttendance()
    {
        $groups = $this->groupRepository->getActiveAndUpcoming();
        return $groups->map(function ($group) {
            $status = null;
            $deadline = $group->attendanceDeadlines()->where('date', date('Y-m-d'))->first();
            if ($deadline) {
                if ($deadline->check_in_deadline && $deadline->check_out_deadline) $status = 'finished';
                elseif (!$deadline->check_in_deadline && !$deadline->check_out_deadline) $status = 'check-in';
                elseif ($deadline->check_in_deadline && !$deadline->check_out_deadline) $status = 'check-out';
            }
            $group->attendance_deadline_status = $status;
            return $group;
        });
    }

    public function startAttendanceDeadline(array $data): void
    {
        $group = $this->groupRepository->find($data['group_id']);
        $deadline = $group->attendanceDeadlines()->where('date', date('Y-m-d'))->first();
        if ($deadline) return;

        DB::beginTransaction();
        try {
            $students = $group->enrollments()->where('status', 'active')->get();
            $enrollmentsIds = $students->pluck('id')->toArray();
            $deadline = $group->attendanceDeadlines()->create(['date' => date('Y-m-d')]);
            foreach ($enrollmentsIds as $enrollmentId) {
                Attendance::create([
                    'attendance_deadline_id' => $deadline->id,
                    'enrollment_id' => $enrollmentId,
                    'status' => 'absent',
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateAttendanceDeadline(array $data): void
    {
        $group = $this->groupRepository->find($data['group_id']);
        $deadline = $group->attendanceDeadlines()->where('date', date('Y-m-d'))->first();
        if (!$deadline) throw new ApiException('No hay un período de asistencia activo para hoy', 400);
        $deadline->update($data);
    }

    public function registerAttendanceByDocument(array $data)
    {
        $deadline = \App\Models\Academy\AttendanceDeadline::where('group_id', $data['group_id'])
            ->where('date', date('Y-m-d'))->first();
        if (!$deadline) throw new ApiException('No hay un período de asistencia activo para hoy', 400);

        $person = \App\Models\Core\Person::where('document_number', $data['document_number'])->first();
        if (!$person) throw new ApiException('Persona no encontrada', 404);

        $enrollment = Enrollment::where('profile_student_id', $person->id)
            ->where('group_id', $data['group_id'])->where('status', 'active')->first();
        if (!$enrollment) throw new ApiException('El alumno no está inscrito en este grupo', 400);

        $attendance = Attendance::where('attendance_deadline_id', $deadline->id)
            ->where('enrollment_id', $enrollment->id)->first();
        if (!$attendance) throw new ApiException('No se encontró el registro de asistencia', 404);

        $field = !$deadline->check_in_deadline ? 'check_in' : 'check_out';
        $attendance->update([$field => date('H:i:s'), 'status' => 'present']);

        return ['message' => 'Asistencia registrada correctamente', 'student' => $person->name . ' ' . $person->paternal_surname];
    }

    public function registerAttendanceByCode(array $data)
    {
        return $this->registerAttendanceByDocument($data);
    }
}