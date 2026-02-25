<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;

class StudentExamList extends Component
{
    public function render()
    {
        $user = auth()->user();
        $classIds = $user->classes()->pluck('classes.id');
        $exams = Exam::where('is_published', true)
            ->whereHas('classes', fn ($q) => $q->whereIn('classes.id', $classIds))
            ->with('subject')
            ->orderBy('title')
            ->get();
        return view('livewire.student-exam-list', ['exams' => $exams]);
    }
}
