<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $lecturer = User::firstOrCreate(
            ['email' => 'lecturer@example.com'],
            ['name' => 'Lecturer 1', 'password' => Hash::make('password'), 'role' => User::ROLE_LECTURER]
        );
        if (!$lecturer->isLecturer()) {
            $lecturer->update(['role' => User::ROLE_LECTURER]);
        }

        $student = User::firstOrCreate(
            ['email' => 'student@example.com'],
            ['name' => 'Student 1', 'password' => Hash::make('password'), 'role' => User::ROLE_STUDENT]
        );
        $student = User::firstOrCreate(
            ['email' => 'student2@example.com'],
            ['name' => 'Student 2', 'password' => Hash::make('password'), 'role' => User::ROLE_STUDENT]
        );
        if (!$student->isStudent()) {
            $student->update(['role' => User::ROLE_STUDENT]);
        }
    }
}
