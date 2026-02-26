<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Exams</h4>
        <a href="{{ route('lecturer.exams.create') }}" class="btn btn-primary">Create Exam</a>
    </div>
    <div class="card">
        <table class="table table-striped mb-0">
            <thead>
                <tr><th>Title</th><th>Subject</th><th>Questions</th><th>Time</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                    <tr>
                        <td>{{ $exam->title }}</td>
                        <td>{{ $exam->subject->name }}</td>
                        <td>{{ $exam->questions_count }}</td>
                        <td>{{ $exam->time_limit_minutes }} min</td>
                        <td>
                            @if($exam->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('lecturer.exams.edit', $exam) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <button type="button" class="btn btn-sm btn-outline-{{ $exam->is_published ? 'warning' : 'success' }}" wire:click="togglePublish({{ $exam->id }})">
                                {{ $exam->is_published ? 'Unpublish' : 'Publish' }}
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $exam->id }})" wire:confirm="Delete this exam?">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">No exams. Create one to get started.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
