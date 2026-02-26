<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['code' => 'MATH', 'name' => 'Mathematics'],
            ['code' => 'ENG', 'name' => 'English'],
            ['code' => 'SCI', 'name' => 'Science'],
        ];

        foreach ($subjects as $data) {
            Subject::firstOrCreate(
                ['code' => $data['code']],
                ['name' => $data['name']]
            );
        }
    }
}
