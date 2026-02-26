<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['code' => 'Y1C', 'name' => 'Year 1 Computing'],
            ['code' => 'Y2C', 'name' => 'Year 2 Computing'],
        ];

        foreach ($classes as $data) {
            ClassModel::firstOrCreate(
                ['code' => $data['code']],
                ['name' => $data['name']]
            );
        }

        $class = ClassModel::where('code', 'Y1C')->first();
        $subject = Subject::where('code', 'MATH')->first();
        $students = User::where('role', User::ROLE_STUDENT)->pluck('id');

        if ($class && $subject && !$class->subjects()->where('subject_id', $subject->id)->exists()) {
            $class->subjects()->attach($subject->id);
        }

        if ($class && $students->isNotEmpty()) {
            foreach ($students as $studentId) {
                if (!$class->students()->where('user_id', $studentId)->exists()) {
                    $class->students()->attach($studentId);
                }
            }
        }
    }
}
