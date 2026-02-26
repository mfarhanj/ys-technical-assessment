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
        $message = $exam->is_published ? 'Exam published.' : 'Exam unpublished.';
        session()->flash('message', $message);
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function delete($id)
    {
        $exam = Exam::where('created_by', auth()->id())->findOrFail($id);
        $exam->delete();
        $message = 'Exam deleted.';
        session()->flash('message', $message);
        $this->dispatch('notify', type: 'success', message: $message);
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
