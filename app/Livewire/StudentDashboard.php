<?php

namespace App\Livewire;

use App\Models\Exam;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StudentDashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $classIds = $user->classes()->pluck('classes.id');
        $availableExams = Exam::where('is_published', true)
            ->whereHas('classes', fn ($q) => $q->whereIn('classes.id', $classIds))
            ->count();
        $attemptsCount = $user->examAttempts()->count();
        return view('livewire.student-dashboard', [
            'availableExams' => $availableExams,
            'attemptsCount' => $attemptsCount,
        ]);
    }
}
