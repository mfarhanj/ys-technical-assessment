<?php

namespace App\Livewire;

use App\Models\ClassModel;
use App\Models\User;
use App\Models\Subject;
use Livewire\Component;

class ClassManage extends Component
{
    public ClassModel $class;

    public function addStudent($userId)
    {
        $user = User::where('role', User::ROLE_STUDENT)->findOrFail($userId);
        if (!$this->class->students()->where('user_id', $userId)->exists()) {
            $this->class->students()->attach($userId);
            session()->flash('message', 'Student added.');
        }
    }

    public function removeStudent($userId)
    {
        $this->class->students()->detach($userId);
        session()->flash('message', 'Student removed.');
    }

    public function addSubject($subjectId)
    {
        if (!$this->class->subjects()->where('subject_id', $subjectId)->exists()) {
            $this->class->subjects()->attach($subjectId);
            session()->flash('message', 'Subject added.');
        }
    }

    public function removeSubject($subjectId)
    {
        $this->class->subjects()->detach($subjectId);
        session()->flash('message', 'Subject removed.');
    }

    public function render()
    {
        $studentsInClass = $this->class->students()->orderBy('name')->get();
        $studentsNotInClass = User::where('role', User::ROLE_STUDENT)
            ->whereNotIn('id', $studentsInClass->pluck('id'))
            ->orderBy('name')
            ->get();
        $subjectsInClass = $this->class->subjects()->orderBy('name')->get();
        $subjectsNotInClass = Subject::whereNotIn('id', $subjectsInClass->pluck('id'))->orderBy('name')->get();
        return view('livewire.class-manage', [
            'studentsInClass' => $studentsInClass,
            'studentsNotInClass' => $studentsNotInClass,
            'subjectsInClass' => $subjectsInClass,
            'subjectsNotInClass' => $subjectsNotInClass,
        ]);
    }
}
