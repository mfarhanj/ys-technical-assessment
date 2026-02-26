<div class="container">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Add Class</span>
        </div>
        <div class="card-body">
            <form wire:submit="save" class="row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" wire:model="name" placeholder="Class name" required>
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" wire:model="code" placeholder="Code (optional)">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Classes</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr><th>Name</th><th>Code</th><th>Students</th><th>Subjects</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($classes as $class)
                        <tr>
                            @if($editingId === $class->id)
                                <td colspan="5">
                                    <form wire:submit="update" class="row g-2 align-items-center">
                                        <div class="col-auto"><input type="text" class="form-control form-control-sm" wire:model="editName" required></div>
                                        <div class="col-auto"><input type="text" class="form-control form-control-sm" wire:model="editCode" placeholder="Code"></div>
                                        <div class="col-auto"><button type="submit" class="btn btn-sm btn-success">Save</button></div>
                                        <div class="col-auto"><button type="button" class="btn btn-sm btn-secondary" wire:click="cancelEdit">Cancel</button></div>
                                    </form>
                                </td>
                            @else
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->code ?? '-' }}</td>
                                <td>{{ $class->students_count }}</td>
                                <td>{{ $class->subjects_count }}</td>
                                <td>
                                    <a href="{{ route('lecturer.classes.manage', $class) }}" class="btn btn-sm btn-outline-primary">Manage</a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $class->id }})">Edit</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $class->id }})" wire:confirm="Delete this class?">Delete</button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted">No classes yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
