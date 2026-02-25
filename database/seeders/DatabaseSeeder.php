<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        $lecturer = User::where('email', 'lecturer@example.com')->first();
        $student = User::where('email', 'student@example.com')->first();

        $class = ClassModel::firstOrCreate(['code' => 'Y1C'], ['name' => 'Year 1 Computing']);
        $subject = Subject::firstOrCreate(['code' => 'MATH'], ['name' => 'Mathematics']);

        if (!$class->subjects()->where('subject_id', $subject->id)->exists()) {
            $class->subjects()->attach($subject->id);
        }
        if (!$class->students()->where('user_id', $student->id)->exists()) {
            $class->students()->attach($student->id);
        }

        $exam = Exam::firstOrCreate(
            ['title' => 'Math Quiz 1', 'created_by' => $lecturer->id],
            [
                'description' => 'Basic arithmetic',
                'subject_id' => $subject->id,
                'time_limit_minutes' => 15,
                'total_marks' => 3,
                'is_published' => true,
            ]
        );
        if ($exam->total_marks === 0) {
            $exam->update(['total_marks' => 3]);
        }

        if (!$exam->classes()->where('class_id', $class->id)->exists()) {
            $exam->classes()->attach($class->id);
        }

        if ($exam->questions()->count() === 0) {
            $exam->questions()->create([
                'question_text' => 'What is 2 + 2?',
                'type' => Question::TYPE_MULTIPLE_CHOICE,
                'order' => 0,
                'marks' => 1,
                'options' => ['A' => '3', 'B' => '4', 'C' => '5', 'D' => '6'],
                'correct_answer' => 'B',
            ]);
            $exam->questions()->create([
                'question_text' => 'What is 10 - 3?',
                'type' => Question::TYPE_MULTIPLE_CHOICE,
                'order' => 1,
                'marks' => 1,
                'options' => ['A' => '6', 'B' => '7', 'C' => '8', 'D' => '9'],
                'correct_answer' => 'B',
            ]);
            $exam->questions()->create([
                'question_text' => 'Explain what addition is in one sentence.',
                'type' => Question::TYPE_OPEN_TEXT,
                'order' => 2,
                'marks' => 1,
                'options' => null,
                'correct_answer' => null,
            ]);
        }
    }
}
