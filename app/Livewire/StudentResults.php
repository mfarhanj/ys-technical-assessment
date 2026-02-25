<?php

namespace App\Livewire;

use Livewire\Component;

class StudentResults extends Component
{
    public function render()
    {
        $attempts = auth()->user()
            ->examAttempts()
            ->whereNotNull('submitted_at')
            ->with('exam.subject')
            ->latest('submitted_at')
            ->get();
        return view('livewire.student-results', ['attempts' => $attempts]);
    }
}
