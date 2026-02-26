<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Manage class: {{ $class->name }}</h4>
        <a href="{{ route('lecturer.classes') }}" class="btn btn-outline-secondary">Back to classes</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Students in this class</div>
                <ul class="list-group list-group-flush">
                    @forelse($studentsInClass as $s)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $s->name }} <small class="text-muted">{{ $s->email }}</small>
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeStudent({{ $s->id }})">Remove</button>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No students. Add below.</li>
                    @endforelse
                </ul>
            </div>
            <div class="card">
                <div class="card-header">Add student</div>
                <div class="card-body">
                    @forelse($studentsNotInClass as $s)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $s->name }} ({{ $s->email }})</span>
                            <button type="button" class="btn btn-sm btn-primary" wire:click="addStudent({{ $s->id }})">Add</button>
                        </div>
                    @empty
                        <p class="text-muted mb-0">All registered students are in this class.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Subjects for this class</div>
                <ul class="list-group list-group-flush">
                    @forelse($subjectsInClass as $subj)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $subj->name }}
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeSubject({{ $subj->id }})">Remove</button>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No subjects. Add below.</li>
                    @endforelse
                </ul>
            </div>
            <div class="card">
                <div class="card-header">Add subject</div>
                <div class="card-body">
                    @forelse($subjectsNotInClass as $subj)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $subj->name }}</span>
                            <button type="button" class="btn btn-sm btn-primary" wire:click="addSubject({{ $subj->id }})">Add</button>
                        </div>
                    @empty
                        <p class="text-muted mb-0">All subjects are assigned to this class.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
