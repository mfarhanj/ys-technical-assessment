<div class="container">
    <h4 class="mb-4">My Results</h4>
    <div class="card">
        <table class="table table-striped mb-0">
            <thead>
                <tr><th>Exam</th><th>Subject</th><th>Score</th><th>Submitted at</th></tr>
            </thead>
            <tbody>
                @forelse($attempts as $a)
                    <tr>
                        <td>{{ $a->exam->title }}</td>
                        <td>{{ $a->exam->subject->name }}</td>
                        <td>{{ $a->score }}/{{ $a->total_marks }}</td>
                        <td>{{ $a->submitted_at?->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted">No results yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
