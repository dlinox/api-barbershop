<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Academy\Branch;
use App\Models\Academy\Room;
use App\Models\Academy\Schedule;
use App\Models\Academy\Level;
use App\Models\Academy\Group;
use App\Models\Academy\GroupPaymentPlan;

class AcademySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->seedBranches();
        $this->seedRooms();
        $this->seedSchedules();
        $this->seedLevels();
        $this->seedGroups();
        $this->seedGroupPaymentPlans();
    }

    private function seedBranches(): void
    {
        $branches = [
            [
                'id' => 1,
                'name' => 'Escuela Bárbaros Juliaca',
                'address' => 'Jr. Unión N° 209',
                'ubication' => 'Centro',
                'is_active' => true,
                'created_at' => '2026-02-16 16:40:08',
                'updated_at' => '2026-02-17 05:16:20'
            ]
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }

    private function seedRooms(): void
    {
        $rooms = [
            ['id' => 1, 'branch_id' => 1, 'number' => '101', 'description' => null, 'capacity' => 20, 'floor' => 1, 'is_active' => true, 'created_at' => '2026-02-16 16:40:08', 'updated_at' => '2026-02-16 16:40:08'],
            ['id' => 2, 'branch_id' => 1, 'number' => '102', 'description' => null, 'capacity' => 20, 'floor' => 1, 'is_active' => true, 'created_at' => '2026-02-17 07:53:07', 'updated_at' => '2026-02-17 07:53:07'],
            ['id' => 3, 'branch_id' => 1, 'number' => '103', 'description' => null, 'capacity' => 20, 'floor' => 1, 'is_active' => true, 'created_at' => '2026-02-17 19:12:31', 'updated_at' => '2026-02-17 19:12:31'],
            ['id' => 4, 'branch_id' => 1, 'number' => '104', 'description' => null, 'capacity' => 20, 'floor' => 1, 'is_active' => true, 'created_at' => '2026-02-17 19:12:59', 'updated_at' => '2026-02-17 19:12:59'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }

    private function seedSchedules(): void
    {
        $schedules = [
            ['id' => 1, 'shift' => 'morning', 'start_time' => '09:00:00', 'end_time' => '12:30:00', 'is_active' => true, 'created_at' => '2026-02-16 16:40:08', 'updated_at' => '2026-02-17 05:17:26'],
            ['id' => 2, 'shift' => 'afternoon', 'start_time' => '14:00:00', 'end_time' => '16:00:00', 'is_active' => true, 'created_at' => '2026-02-17 05:17:46', 'updated_at' => '2026-02-20 23:00:37'],
            ['id' => 3, 'shift' => 'afternoon', 'start_time' => '16:00:00', 'end_time' => '18:00:00', 'is_active' => true, 'created_at' => '2026-02-17 05:17:58', 'updated_at' => '2026-02-20 23:00:55'],
            ['id' => 4, 'shift' => 'morning', 'start_time' => '18:00:00', 'end_time' => '20:00:00', 'is_active' => true, 'created_at' => '2026-02-17 05:18:10', 'updated_at' => '2026-02-20 23:03:00'],
            ['id' => 5, 'shift' => 'morning', 'start_time' => '09:00:00', 'end_time' => '19:00:00', 'is_active' => true, 'created_at' => '2026-02-17 07:46:56', 'updated_at' => '2026-02-17 07:46:56'],
            ['id' => 7, 'shift' => 'afternoon', 'start_time' => '14:00:00', 'end_time' => '17:30:00', 'is_active' => true, 'created_at' => '2026-02-20 23:00:26', 'updated_at' => '2026-02-20 23:00:26'],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }

    private function seedLevels(): void
    {
        $levels = [
            ['id' => 1, 'order' => 2, 'name' => 'Intermedio', 'description' => '2h', 'duration_months' => 3, 'is_active' => true, 'created_at' => '2026-02-16 16:40:08', 'updated_at' => '2026-02-19 13:42:16'],
            ['id' => 2, 'order' => 3, 'name' => 'Avanzado', 'description' => '2h', 'duration_months' => 3, 'is_active' => true, 'created_at' => '2026-02-17 05:27:48', 'updated_at' => '2026-02-19 13:42:06'],
            ['id' => 4, 'order' => 1, 'name' => 'Básico', 'description' => '3h 30', 'duration_months' => 2, 'is_active' => true, 'created_at' => '2026-02-17 07:50:24', 'updated_at' => '2026-02-19 13:46:28'],
        ];

        foreach ($levels as $level) {
            Level::create($level);
        }
    }

    private function seedGroups(): void
    {
        $groups = [
            ['id' => 3, 'branch_id' => 1, 'level_id' => 4, 'schedule_id' => 1, 'room_id' => 1, 'teacher_id' => null, 'name' => '2 MESES (9-12:30) - JESUS SALAS', 'start_date' => '2026-01-08', 'end_date' => '2026-03-08', 'enrollment_price' => 250.00, 'monthly_price' => 650.00, 'days_of_week' => '1,2,3,4,5', 'attendance_tolerance_minutes' => 20, 'is_active' => true, 'created_at' => '2026-02-17 19:19:56', 'updated_at' => '2026-02-19 13:54:06'],
            ['id' => 9, 'branch_id' => 1, 'level_id' => 4, 'schedule_id' => 1, 'room_id' => 2, 'teacher_id' => null, 'name' => '2 MESES (9-12:30) - RONALDINO CALCINA', 'start_date' => '2026-01-08', 'end_date' => '2026-03-08', 'enrollment_price' => 250.00, 'monthly_price' => 650.00, 'days_of_week' => '1,2,3,4,5', 'attendance_tolerance_minutes' => 20, 'is_active' => true, 'created_at' => '2026-02-19 13:53:45', 'updated_at' => '2026-02-19 13:53:58'],
            ['id' => 10, 'branch_id' => 1, 'level_id' => 4, 'schedule_id' => 1, 'room_id' => 4, 'teacher_id' => null, 'name' => '2 MESES (9-12:30) - MIDWAR SANDOVAL', 'start_date' => '2026-02-09', 'end_date' => '2026-04-09', 'enrollment_price' => 250.00, 'monthly_price' => 650.00, 'days_of_week' => '1,2,3,4,5', 'attendance_tolerance_minutes' => 20, 'is_active' => true, 'created_at' => '2026-02-19 14:02:23', 'updated_at' => '2026-02-19 14:02:42'],
            ['id' => 11, 'branch_id' => 1, 'level_id' => 4, 'schedule_id' => 1, 'room_id' => 3, 'teacher_id' => null, 'name' => '2 MESES (9-12:30) - ALEX SANTOS', 'start_date' => '2026-01-16', 'end_date' => '2026-03-16', 'enrollment_price' => 250.00, 'monthly_price' => 650.00, 'days_of_week' => '1,3,2,4,5', 'attendance_tolerance_minutes' => 20, 'is_active' => true, 'created_at' => '2026-02-19 14:11:13', 'updated_at' => '2026-02-19 14:11:31'],
            ['id' => 12, 'branch_id' => 1, 'level_id' => 1, 'schedule_id' => 2, 'room_id' => 2, 'teacher_id' => null, 'name' => '3 MESES (2-4) - RONALDINO CALCINA', 'start_date' => '2026-02-09', 'end_date' => '2026-05-09', 'enrollment_price' => 250.00, 'monthly_price' => 450.00, 'days_of_week' => '1,2,3,5,4', 'attendance_tolerance_minutes' => 20, 'is_active' => true, 'created_at' => '2026-02-20 19:38:43', 'updated_at' => '2026-02-20 23:21:56'],
        ];

        foreach ($groups as $group) {
            Group::create($group);
        }
    }

    private function seedGroupPaymentPlans(): void
    {
        $groupPaymentPlans = [
            ['id' => 18, 'group_id' => 9, 'type' => 'enrollment', 'start_date' => '2026-01-08', 'end_date' => '2026-03-08', 'amount' => 250.00, 'created_at' => '2026-02-19 13:53:58', 'updated_at' => '2026-02-19 13:53:58'],
            ['id' => 19, 'group_id' => 9, 'type' => 'monthly', 'start_date' => '2026-01-08', 'end_date' => '2026-02-08', 'amount' => 650.00, 'created_at' => '2026-02-19 13:53:58', 'updated_at' => '2026-02-19 13:53:58'],
            ['id' => 20, 'group_id' => 9, 'type' => 'monthly', 'start_date' => '2026-02-08', 'end_date' => '2026-03-08', 'amount' => 650.00, 'created_at' => '2026-02-19 13:53:58', 'updated_at' => '2026-02-19 13:53:58'],
            ['id' => 21, 'group_id' => 3, 'type' => 'enrollment', 'start_date' => '2026-01-08', 'end_date' => '2026-03-08', 'amount' => 250.00, 'created_at' => '2026-02-19 13:54:06', 'updated_at' => '2026-02-19 13:54:06'],
            ['id' => 22, 'group_id' => 3, 'type' => 'monthly', 'start_date' => '2026-01-08', 'end_date' => '2026-02-08', 'amount' => 650.00, 'created_at' => '2026-02-19 13:54:06', 'updated_at' => '2026-02-19 13:54:06'],
            ['id' => 23, 'group_id' => 3, 'type' => 'monthly', 'start_date' => '2026-02-08', 'end_date' => '2026-03-08', 'amount' => 650.00, 'created_at' => '2026-02-19 13:54:06', 'updated_at' => '2026-02-19 13:54:06'],
            ['id' => 27, 'group_id' => 10, 'type' => 'enrollment', 'start_date' => '2026-02-09', 'end_date' => '2026-04-09', 'amount' => 250.00, 'created_at' => '2026-02-19 14:02:42', 'updated_at' => '2026-02-19 14:02:42'],
            ['id' => 28, 'group_id' => 10, 'type' => 'monthly', 'start_date' => '2026-02-09', 'end_date' => '2026-03-09', 'amount' => 650.00, 'created_at' => '2026-02-19 14:02:42', 'updated_at' => '2026-02-19 14:02:42'],
            ['id' => 29, 'group_id' => 10, 'type' => 'monthly', 'start_date' => '2026-03-09', 'end_date' => '2026-04-09', 'amount' => 650.00, 'created_at' => '2026-02-19 14:02:42', 'updated_at' => '2026-02-19 14:02:42'],
            ['id' => 33, 'group_id' => 11, 'type' => 'enrollment', 'start_date' => '2026-01-16', 'end_date' => '2026-03-16', 'amount' => 250.00, 'created_at' => '2026-02-19 14:11:31', 'updated_at' => '2026-02-19 14:11:31'],
            ['id' => 34, 'group_id' => 11, 'type' => 'monthly', 'start_date' => '2026-01-16', 'end_date' => '2026-02-16', 'amount' => 650.00, 'created_at' => '2026-02-19 14:11:31', 'updated_at' => '2026-02-19 14:11:31'],
            ['id' => 35, 'group_id' => 11, 'type' => 'monthly', 'start_date' => '2026-02-16', 'end_date' => '2026-03-16', 'amount' => 650.00, 'created_at' => '2026-02-19 14:11:31', 'updated_at' => '2026-02-19 14:11:31'],
            ['id' => 44, 'group_id' => 12, 'type' => 'enrollment', 'start_date' => '2026-02-09', 'end_date' => '2026-05-09', 'amount' => 250.00, 'created_at' => '2026-02-20 23:21:56', 'updated_at' => '2026-02-20 23:21:56'],
            ['id' => 45, 'group_id' => 12, 'type' => 'monthly', 'start_date' => '2026-02-09', 'end_date' => '2026-03-09', 'amount' => 450.00, 'created_at' => '2026-02-20 23:21:56', 'updated_at' => '2026-02-20 23:21:56'],
            ['id' => 46, 'group_id' => 12, 'type' => 'monthly', 'start_date' => '2026-03-09', 'end_date' => '2026-04-09', 'amount' => 450.00, 'created_at' => '2026-02-20 23:21:56', 'updated_at' => '2026-02-20 23:21:56'],
            ['id' => 47, 'group_id' => 12, 'type' => 'monthly', 'start_date' => '2026-04-09', 'end_date' => '2026-05-09', 'amount' => 450.00, 'created_at' => '2026-02-20 23:21:56', 'updated_at' => '2026-02-20 23:21:56'],
        ];

        foreach ($groupPaymentPlans as $groupPaymentPlan) {
            GroupPaymentPlan::create($groupPaymentPlan);
        }
    }
}
