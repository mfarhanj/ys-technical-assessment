<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;

class ExamViewController extends Controller
{
    public function edit(Exam $exam)
    {
        $this->authorize('update', $exam);
        return view('lecturer.exam-form', ['exam' => $exam]);
    }
}
