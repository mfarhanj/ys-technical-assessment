<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;

class TakeExamController extends Controller
{
    public function show(Exam $exam)
    {
        $user = auth()->user();
        $classIds = $user->classes()->pluck('id');
        $assigned = $exam->classes()->whereIn('id', $classIds)->exists();
        if (!$assigned) {
            abort(403, 'This exam is not assigned to your class.');
        }
        if (!$exam->is_published) {
            abort(404, 'Exam not available.');
        }
        return view('student.take-exam', ['exam' => $exam]);
    }
}
