<?php

namespace App\Livewire;

use App\Models\ClassModel;
use Livewire\Component;

class ClassList extends Component
{
    public $name = '';
    public $code = '';
    public $editingId = null;
    public $editName = '';
    public $editCode = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'nullable|string|max:50',
    ];

    public function save()
    {
        $this->validate();
        ClassModel::create(['name' => $this->name, 'code' => $this->code ?: null]);
        $this->reset(['name', 'code']);
        session()->flash('message', 'Class created.');
    }

    public function edit($id)
    {
        $c = ClassModel::findOrFail($id);
        $this->editingId = $id;
        $this->editName = $c->name;
        $this->editCode = $c->code ?? '';
    }

    public function update()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editCode' => 'nullable|string|max:50',
        ]);
        $c = ClassModel::findOrFail($this->editingId);
        $c->update(['name' => $this->editName, 'code' => $this->editCode ?: null]);
        $this->cancelEdit();
        session()->flash('message', 'Class updated.');
    }

    public function cancelEdit()
    {
        $this->editingId = $this->editName = $this->editCode = null;
    }

    public function delete($id)
    {
        ClassModel::findOrFail($id)->delete();
        $this->cancelEdit();
        session()->flash('message', 'Class deleted.');
    }

    public function render()
    {
        return view('livewire.class-list', [
            'classes' => ClassModel::withCount(['students', 'subjects'])->orderBy('name')->get(),
        ]);
    }
}
