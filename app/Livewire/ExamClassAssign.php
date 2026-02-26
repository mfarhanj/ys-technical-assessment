<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;

class ExamClassAssign extends Component
{
    public Exam $exam;
    public $selectedClasses = [];

    public function mount(Exam $exam)
    {
        $this->exam = $exam;
        $this->selectedClasses = $exam->classes()->pluck('id')->map(fn ($id) => (string) $id)->all();
    }

    public function updatedSelectedClasses()
    {
        $this->exam->classes()->sync($this->selectedClasses);
        $message = 'Class assignment updated.';
        session()->flash('message', $message);
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function render()
    {
        $classes = \App\Models\ClassModel::orderBy('name')->get();
        return view('livewire.exam-class-assign', ['classes' => $classes]);
    }
}
