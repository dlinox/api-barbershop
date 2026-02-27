<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use App\Models\Behavior\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void {}

    private function seedStudents(): void
    {

        $students = [
            ['core_person_id' => 6],
            ['core_person_id' => 7],
            ['core_person_id' => 8],
            ['core_person_id' => 9],
            ['core_person_id' => 10],
            ['core_person_id' => 11],
            ['core_person_id' => 12],
            ['core_person_id' => 13],
            ['core_person_id' => 14],
            ['core_person_id' => 15],
            ['core_person_id' => 16],
            ['core_person_id' => 17],
            ['core_person_id' => 18],
            ['core_person_id' => 19],
            ['core_person_id' => 20],
            ['core_person_id' => 21],
            ['core_person_id' => 22],
            ['core_person_id' => 23],
            ['core_person_id' => 24],
            ['core_person_id' => 25],
            ['core_person_id' => 26],
            ['core_person_id' => 27],
            ['core_person_id' => 28],
            ['core_person_id' => 29],
            ['core_person_id' => 30],
            ['core_person_id' => 31],
            ['core_person_id' => 32],
            ['core_person_id' => 33],
            ['core_person_id' => 34],
            ['core_person_id' => 35],
            ['core_person_id' => 36],
            ['core_person_id' => 37],
            ['core_person_id' => 38],
            ['core_person_id' => 39],
        ];

        foreach ($students as $student) {
            \App\Models\Profile\Student::create($student);
        }
    }

    private function seedUserStudents(): void
    {
        $users = [
            [
                'id' => 3,
                'username' => '76063570',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 4,
                'username' => '70063570',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 5,
                'username' => '12312323',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 6,
                'username' => '76832299',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 7,
                'username' => '61893646',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 8,
                'username' => '62805432',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 9,
                'username' => '73820089',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 10,
                'username' => '61939362',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 11,
                'username' => '61783700',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 12,
                'username' => '63017506',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 13,
                'username' => '48022417',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 14,
                'username' => '61892341',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 15,
                'username' => '61321205',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 16,
                'username' => '62528271',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 17,
                'username' => '62480235',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 18,
                'username' => '45910327',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 19,
                'username' => '12312301',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 20,
                'username' => '12312302',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 21,
                'username' => '12312303',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 22,
                'username' => '72233424',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 23,
                'username' => '70838415',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 24,
                'username' => '74310292',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 25,
                'username' => '61091109',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 26,
                'username' => '73651532',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 27,
                'username' => '73541260',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 28,
                'username' => '61000410',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 29,
                'username' => '71654064',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 30,
                'username' => '60665191',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 31,
                'username' => '73171929',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 32,
                'username' => '62824124',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 33,
                'username' => '60417095',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 34,
                'username' => '60597092',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 35,
                'username' => '75899252',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 36,
                'username' => '62486607',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 37,
                'username' => '60908831',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 38,
                'username' => '61906283',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
            [
                'id' => 39,
                'username' => '76507044',
                'password' => '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa',
            ],
        ];

        foreach ($users as $user) {
            User::insert([
                'id' => $user['id'],
                'username' => $user['username'],
                'password' => $user['password'],
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedBehaviorStudents()
    {
        $behaviorStudents = [
            [
                'id' => 6,
                'auth_user_id' => 6,
                'profileable_type' => 'profile_students',
                'profileable_id' => 6,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 7,
                'auth_user_id' => 7,
                'profileable_type' => 'profile_students',
                'profileable_id' => 7,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 8,
                'auth_user_id' => 8,
                'profileable_type' => 'profile_students',
                'profileable_id' => 8,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 9,
                'auth_user_id' => 9,
                'profileable_type' => 'profile_students',
                'profileable_id' => 9,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 10,
                'auth_user_id' => 10,
                'profileable_type' => 'profile_students',
                'profileable_id' => 10,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 11,
                'auth_user_id' => 11,
                'profileable_type' => 'profile_students',
                'profileable_id' => 11,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 12,
                'auth_user_id' => 12,
                'profileable_type' => 'profile_students',
                'profileable_id' => 12,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 13,
                'auth_user_id' => 13,
                'profileable_type' => 'profile_students',
                'profileable_id' => 13,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 14,
                'auth_user_id' => 14,
                'profileable_type' => 'profile_students',
                'profileable_id' => 14,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 15,
                'auth_user_id' => 15,
                'profileable_type' => 'profile_students',
                'profileable_id' => 15,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 16,
                'auth_user_id' => 16,
                'profileable_type' => 'profile_students',
                'profileable_id' => 16,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 17,
                'auth_user_id' => 17,
                'profileable_type' => 'profile_students',
                'profileable_id' => 17,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 18,
                'auth_user_id' => 18,
                'profileable_type' => 'profile_students',
                'profileable_id' => 18,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 19,
                'auth_user_id' => 19,
                'profileable_type' => 'profile_students',
                'profileable_id' => 19,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 20,
                'auth_user_id' => 20,
                'profileable_type' => 'profile_students',
                'profileable_id' => 20,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 21,
                'auth_user_id' => 21,
                'profileable_type' => 'profile_students',
                'profileable_id' => 21,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 22,
                'auth_user_id' => 22,
                'profileable_type' => 'profile_students',
                'profileable_id' => 22,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 23,
                'auth_user_id' => 23,
                'profileable_type' => 'profile_students',
                'profileable_id' => 23,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 24,
                'auth_user_id' => 24,
                'profileable_type' => 'profile_students',
                'profileable_id' => 24,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 25,
                'auth_user_id' => 25,
                'profileable_type' => 'profile_students',
                'profileable_id' => 25,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 26,
                'auth_user_id' => 26,
                'profileable_type' => 'profile_students',
                'profileable_id' => 26,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 27,
                'auth_user_id' => 27,
                'profileable_type' => 'profile_students',
                'profileable_id' => 27,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 28,
                'auth_user_id' => 28,
                'profileable_type' => 'profile_students',
                'profileable_id' => 28,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 29,
                'auth_user_id' => 29,
                'profileable_type' => 'profile_students',
                'profileable_id' => 29,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 30,
                'auth_user_id' => 30,
                'profileable_type' => 'profile_students',
                'profileable_id' => 30,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 31,
                'auth_user_id' => 31,
                'profileable_type' => 'profile_students',
                'profileable_id' => 31,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 32,
                'auth_user_id' => 32,
                'profileable_type' => 'profile_students',
                'profileable_id' => 32,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 33,
                'auth_user_id' => 33,
                'profileable_type' => 'profile_students',
                'profileable_id' => 33,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 34,
                'auth_user_id' => 34,
                'profileable_type' => 'profile_students',
                'profileable_id' => 34,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 35,
                'auth_user_id' => 35,
                'profileable_type' => 'profile_students',
                'profileable_id' => 35,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 36,
                'auth_user_id' => 36,
                'profileable_type' => 'profile_students',
                'profileable_id' => 36,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 37,
                'auth_user_id' => 37,
                'profileable_type' => 'profile_students',
                'profileable_id' => 37,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 38,
                'auth_user_id' => 38,
                'profileable_type' => 'profile_students',
                'profileable_id' => 38,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
            [
                'id' => 39,
                'auth_user_id' => 39,
                'profileable_type' => 'profile_students',
                'profileable_id' => 39,
                'behavior_role_id' => 2,
                'is_active' => 1,
            ],
        ];

        foreach ($behaviorStudents as $behaviorStudent) {
            Profile::create($behaviorStudent);
        }
    }
}
