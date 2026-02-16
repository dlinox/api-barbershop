<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Core\Person;
use Illuminate\Http\Request;
use App\Modules\Administrator\Academy\Repositories\AttendanceRepository;
use App\Modules\Administrator\Academy\Repositories\GroupRepository;
use App\Models\Academy\Attendance;
use App\Models\Academy\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function __construct(
        private AttendanceRepository $attendanceRepository,
        private GroupRepository $groupRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->attendanceRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->attendanceRepository->update($data);
    }

    public function delete(int $id)
    {
        return $this->attendanceRepository->delete($id);
    }

    public function getGroupsByAttendance()
    {
        $groups = $this->groupRepository->getActiveGroups();
        $groups = $groups->map(function ($group) {

            $status = null;
            $deadline = $group->attendanceDeadlines()->where('date', date('Y-m-d'))->first();
            if ($deadline) {
                if ($deadline->check_in_deadline && $deadline->check_out_deadline) {
                    $status = 'finished';
                } else if (!$deadline->check_in_deadline && !$deadline->check_out_deadline) {
                    $status = 'check-in';
                } else if ($deadline->check_in_deadline && !$deadline->check_out_deadline) {
                    $status = 'check-out';
                }
            }

            $group->attendance_deadline_status = $status;

            return $group;
        });
        return $groups;
    }

    public function startAttendanceDeadline(array $data): void
    {
        $group = $this->groupRepository->find($data['group_id']);
        $deadline = $group->attendanceDeadlines()->where('date', date('Y-m-d'))->first();
        if (!$deadline) {
            $deadline = $group->attendanceDeadlines()->create([
                'date' => date('Y-m-d'),
            ]);
        }
    }

    public function updateAttendanceDeadline(array $data): void
    {

        $status = $data['status'];

        $group = $this->groupRepository->find($data['group_id']);
        $deadline = $group->attendanceDeadlines()->where('date', date('Y-m-d'))->first();
        if (!$deadline) {
            throw new ApiException("Aun no se ha iniciado la asistencia del grupo", 400);
        }

        if ($status == 'check-in') {
            if ($deadline->check_out_deadline === null) {
                $deadline->check_in_deadline = $deadline->check_in_deadline ?  $deadline->check_in_deadline : date('Y-m-d H:i:s');
            } else {
                throw new ApiException("La asistencia del grupo ya ha finalizado", 400);
            }
        } else {
            if ($deadline->check_in_deadline === null) {
                throw new ApiException("El registro de entrada aun no se ha finalizado", 400);
            } else {
                $deadline->check_out_deadline = $deadline->check_out_deadline ?  $deadline->check_out_deadline : date('Y-m-d H:i:s');
            }
        }

        $deadline->save();
    }

    public function registerAttendanceByDocument(array $data): void
    {
        $person = Person::where('document_number', $data['identifier'])->first();
        if (!$person) throw new ApiException("No se encontro registro con el documento {$data['identifier']}", 400);
        $student = $person->studentRelation()->first();
        if (!$student) throw new ApiException("No se encontro estudiante con el documento {$data['identifier']}", 400);

        $groupAttendances = $this->attendanceRepository->getGroupAttendanceWithDeadline();

        if ($groupAttendances->isEmpty()) throw new ApiException("No se encontro grupo con asistencia habilitada para el dia actual", 400);

        $groupIds = $groupAttendances->pluck('group_id')->toArray();

        $enrollments = $student->enrollments()->whereIn('group_id', $groupIds)->get();
        if ($enrollments->isEmpty()) throw new ApiException("El estudiante no esta inscrito en ningun grupo con asistencia habilitada", 400);

        $enrollmenGroupIds = $enrollments->pluck('group_id')->toArray();

        $attendancesDeadlines = $groupAttendances->whereIn('group_id', $enrollmenGroupIds);
        if ($attendancesDeadlines->isEmpty()) throw new ApiException("El estudiante no esta inscrito en ningun grupo con asistencia habilitada", 400);

        $type = $data['type'];

        try {
            DB::beginTransaction();

            if ($type === 'check-in') {
                $attendanceDeadlines = $attendancesDeadlines->where('check_in_deadline', null)->where('check_out_deadline', null);
                if ($attendanceDeadlines->isEmpty()) throw new ApiException("No se encontro grupo con asistencia habilitada para registrar entrada", 400);
                $attendanceDeadlinesIds = $attendanceDeadlines->pluck('id')->toArray();

                $enrollmentsIds = $enrollments->pluck('id')->toArray();
                $attendances = Attendance::whereIn('attendance_deadline_id', $attendanceDeadlinesIds)->whereIn('enrollment_id', $enrollmentsIds)->get();

                if ($attendances->isEmpty()) {
                    foreach ($enrollmentsIds as $enrollmentId) {
                        foreach ($attendanceDeadlines as $attendanceDeadline) {

                            $group = $attendanceDeadline->group;
                            $schedule = $group->schedule;

                            $scheduleStart = Carbon::createFromTimeString($schedule->start_time);
                            $toleranceTime = (int) $group->attendance_tolerance_minutes;
                            $limitTime = $scheduleStart->copy()->addMinutes($toleranceTime);

                            $status = 'present';
                            if (Carbon::now()->greaterThan($limitTime)) {
                                $status = 'late';
                            }

                            Attendance::create([
                                'attendance_deadline_id' => $attendanceDeadline->id,
                                'enrollment_id' => $enrollmentId,
                                'check_in' => date('H:i:s'),
                                'status' => $status,
                                'observation' => 'Registro manual por documento',
                            ]);
                        }
                    }
                } else {

                    $attendanceCheckIns = $attendances->where('check_in', null);

                    if ($attendanceCheckIns->isEmpty()) throw new ApiException("El estudiante ya registro entrada", 400);

                    foreach ($attendances as $attendance) {
                        if ($attendance->check_in) continue;

                        $group = $attendance->attendanceDeadline->group;
                        $schedule = $group->schedule;

                        $scheduleStart = Carbon::createFromTimeString($schedule->start_time);
                        $toleranceTime = (int) $group->attendance_tolerance_minutes;
                        $limitTime = $scheduleStart->copy()->addMinutes($toleranceTime);

                        $status = 'present';
                        if (Carbon::now()->greaterThan($limitTime)) {
                            $status = 'late';
                        }

                        $attendance->update([
                            'check_in' => date('H:i:s'),
                            'status' => $status,
                            'observation' => 'Registro manual por documento',
                        ]);
                    }
                }
            } else {
                $attendanceDeadlines = $attendancesDeadlines->where('check_in_deadline', '!=', null)->where('check_out_deadline', null);
                if ($attendanceDeadlines->isEmpty()) throw new ApiException("No se encontro grupo con asistencia habilitada para registrar salida", 400);

                $attendanceDeadlinesIds = $attendanceDeadlines->pluck('id')->toArray();

                $enrollmentsIds = $enrollments->pluck('id')->toArray();
                $attendances = Attendance::whereIn('attendance_deadline_id', $attendanceDeadlinesIds)->whereIn('enrollment_id', $enrollmentsIds)->get();

                if ($attendances->isEmpty()) {
                    foreach ($enrollmentsIds as $enrollmentId) {
                        foreach ($attendanceDeadlinesIds as $attendanceDeadlineId) {
                            Attendance::create([
                                'attendance_deadline_id' => $attendanceDeadlineId,
                                'enrollment_id' => $enrollmentId,
                                'check_out' => date('H:i:s'),
                                'observation' => 'No se registro asistencia de entrada (Registro manual por documento)',
                                'status' => 'late',
                            ]);
                        }
                    }
                } else {

                    $attendanceCheckOuts = $attendances->where('check_out', null);

                    if ($attendanceCheckOuts->isEmpty()) throw new ApiException("El estudiante ya registro salida", 400);

                    foreach ($attendances as $attendance) {
                        if ($attendance->check_out) continue;
                        $attendance->update([
                            'check_out' => date('H:i:s'),
                            'observation' => $attendance->check_in ? null : 'No se registro asistencia de entrada (Registro manual por documento)',
                            'status' => $attendance->check_in ?  $attendance->status : 'late',
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), 400);
        }
    }

    public function registerAttendanceByCode(array $data): void
    {

        $type = $data['type'];
        $enrollmentId = $data['identifier'];

        $enrollment = Enrollment::where('id', $enrollmentId)->first();
        if (!$enrollment) throw new ApiException("No se encontro estudiante", 400);

        $attendanceDeadline = $this->attendanceRepository->getAttendanceDeadlineByGroup($enrollment->group_id);
        if (!$attendanceDeadline) throw new ApiException("No se encontro grupo con asistencia habilitada para registrar entrada", 400);

        $attendance = Attendance::where('attendance_deadline_id', $attendanceDeadline->id)->where('enrollment_id', $enrollmentId)->first();

        if ($type == 'check-in') {
            if (!$attendance) {
                $group = $attendanceDeadline->group;
                $schedule = $group->schedule;

                $scheduleStart = Carbon::createFromTimeString($schedule->start_time);
                $toleranceTime = (int) $group->attendance_tolerance_minutes;
                $limitTime = $scheduleStart->copy()->addMinutes($toleranceTime);

                $status = 'present';
                if (Carbon::now()->greaterThan($limitTime)) {
                    $status = 'late';
                }

                Attendance::create([
                    'attendance_deadline_id' => $attendanceDeadline->id,
                    'enrollment_id' => $enrollmentId,
                    'check_in' => date('H:i:s'),
                    'status' => $status,
                ]);
            } else {
                if ($attendance->check_in) throw new ApiException("El estudiante ya registro entrada", 400);

                $group = $attendanceDeadline->group;
                $schedule = $group->schedule;

                $scheduleStart = Carbon::createFromTimeString($schedule->start_time);
                $toleranceTime = (int) $group->attendance_tolerance_minutes;
                $limitTime = $scheduleStart->copy()->addMinutes($toleranceTime);

                $status = 'present';
                if (Carbon::now()->greaterThan($limitTime)) {
                    $status = 'late';
                }

                $attendance->update([
                    'check_in' => date('H:i:s'),
                    'status' => $status,
                ]);
            }
        } else {
            if (!$attendance) {
                $attendance = Attendance::create([
                    'attendance_deadline_id' => $attendanceDeadline->id,
                    'enrollment_id' => $enrollmentId,
                    'check_out' => date('H:i:s'),
                    'status' => 'late',
                    'observation' => 'No se registro asistencia de entrada.',
                ]);
            } else {
                if ($attendance->check_out) throw new ApiException("El estudiante ya registro salida", 400);
                if ($attendance->check_in) {

                    $attendance->update([
                        'check_out' => date('H:i:s'),
                        'status' => 'late',
                    ]);
                } else {
                    $attendance->update([
                        'check_out' => date('H:i:s'),
                        'status' => 'late',
                        'observation' => 'No se registro asistencia de entrada.',
                    ]);
                }
            }
        }
    }
}
