<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;

class ExamList extends Component
{
    public function togglePublish($id)
    {
        $exam = Exam::where('created_by', auth()->id())->findOrFail($id);
        $exam->update(['is_published' => !$exam->is_published]);
        session()->flash('message', $exam->is_published ? 'Exam published.' : 'Exam unpublished.');
    }

    public function delete($id)
    {
        $exam = Exam::where('created_by', auth()->id())->findOrFail($id);
        $exam->delete();
        session()->flash('message', 'Exam deleted.');
    }

    public function render()
    {
        $exams = Exam::where('created_by', auth()->id())
            ->with('subject')
            ->withCount('questions')
            ->orderByDesc('created_at')
            ->get();
        return view('livewire.exam-list', ['exams' => $exams]);
    }
}
