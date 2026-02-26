<div class="container">
    <div class="card mb-4">
        <div class="card-header">Add Subject</div>
        <div class="card-body">
            <form wire:submit="save" class="row g-2">
                <div class="col-md-4"><input type="text" class="form-control" wire:model="name" placeholder="Subject name" required></div>
                <div class="col-md-3"><input type="text" class="form-control" wire:model="code" placeholder="Code (optional)"></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary">Add</button></div>
            </form>
            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="card">
        <div class="card-header">Subjects</div>
        <table class="table table-striped mb-0">
            <thead><tr><th>Name</th><th>Code</th><th>Exams</th><th></th></tr></thead>
            <tbody>
                @forelse($subjects as $s)
                    <tr>
                        @if($editingId === $s->id)
                            <td colspan="4">
                                <form wire:submit="update" class="row g-2">
                                    <div class="col-auto"><input type="text" class="form-control form-control-sm" wire:model="editName" required></div>
                                    <div class="col-auto"><input type="text" class="form-control form-control-sm" wire:model="editCode"></div>
                                    <div class="col-auto"><button type="submit" class="btn btn-sm btn-success">Save</button><button type="button" class="btn btn-sm btn-secondary" wire:click="cancelEdit">Cancel</button></div>
                                </form>
                            </td>
                        @else
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->code ?? '-' }}</td>
                            <td>{{ $s->exams_count }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $s->id }})">Edit</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $s->id }})" wire:confirm="Delete this subject?">Delete</button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted">No subjects yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
