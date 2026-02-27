<div class="container">
    <h4 class="mb-4">Available Exams</h4>
    <div class="card">
        <table class="table table-striped mb-0">
            <thead>
                <tr><th>Title</th><th>Subject</th><th>Time limit</th><th>Total marks</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                    <tr>
                        <td>{{ $exam->title }}</td>
                        <td>{{ $exam->subject->name }}</td>
                        <td>{{ $exam->time_limit_minutes }} min</td>
                        <td>{{ $exam->total_marks }}</td>
                        <td>
                            @php $attempt = auth()->user()->examAttempts()->where('exam_id', $exam->id)->latest()->first(); @endphp
                            @if($attempt && $attempt->submitted_at)
                                @php $status = $attempt->status ?: 'graded'; @endphp
                                @if($status === 'graded')
                                    <a href="{{ route('student.results') }}" class="btn btn-sm btn-outline-secondary">View result</a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled>Pending review</button>
                                @endif
                            @else
                                <a href="{{ route('student.exams.take', $exam) }}" class="btn btn-sm btn-primary">Take exam</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No exams available for your class.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
