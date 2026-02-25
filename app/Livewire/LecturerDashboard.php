<?php

namespace App\Livewire;

use App\Models\ClassModel;
use App\Models\Exam;
use App\Models\Subject;
use Livewire\Component;

class LecturerDashboard extends Component
{
    public function render()
    {
        $stats = [
            'classes' => ClassModel::count(),
            'subjects' => Subject::count(),
            'exams' => Exam::where('created_by', auth()->id())->count(),
        ];
        return view('livewire.lecturer-dashboard', ['stats' => $stats]);
    }
}
