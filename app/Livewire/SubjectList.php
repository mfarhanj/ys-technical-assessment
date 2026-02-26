<?php

namespace App\Livewire;

use App\Models\Subject;
use Livewire\Component;

class SubjectList extends Component
{
    public $name = '';
    public $code = '';
    public $editingId = null;
    public $editName = '';
    public $editCode = '';

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);
        Subject::create(['name' => $this->name, 'code' => $this->code ?: null]);
        $this->reset(['name', 'code']);
        $message = 'Subject created.';
        session()->flash('message', $message);
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function edit($id)
    {
        $s = Subject::findOrFail($id);
        $this->editingId = $id;
        $this->editName = $s->name;
        $this->editCode = $s->code ?? '';
    }

    public function update()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editCode' => 'nullable|string|max:50',
        ]);
        Subject::findOrFail($this->editingId)->update(['name' => $this->editName, 'code' => $this->editCode ?: null]);
        $this->cancelEdit();
        $message = 'Subject updated.';
        session()->flash('message', $message);
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function cancelEdit()
    {
        $this->editingId = $this->editName = $this->editCode = null;
    }

    public function delete($id)
    {
        Subject::findOrFail($id)->delete();
        $this->cancelEdit();
        $message = 'Subject deleted.';
        session()->flash('message', $message);
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function render()
    {
        return view('livewire.subject-list', [
            'subjects' => Subject::withCount('exams')->orderBy('name')->get(),
        ]);
    }
}
