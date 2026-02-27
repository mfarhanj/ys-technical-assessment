<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Student Results</h4>
        @if($selectedAttempt)
            <button class="btn btn-sm btn-outline-secondary" wire:click="clearSelection">Back to list</button>
        @endif
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Exam</label>
                            <select class="form-select" wire:model="examId">
                                <option value="">All my exams</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Student</label>
                            <input type="text" class="form-control" placeholder="Search name/email" wire:model.debounce.300ms="studentSearch">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Exam</th>
                                <th class="text-nowrap">Score</th>
                                <th>Status</th>
                                <th class="text-nowrap">Submitted</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attempts as $a)
                                @php $status = $a->status ?: 'graded'; @endphp
                                <tr @class(['table-primary' => (int)($selectedAttempt?->id ?? 0) === (int)$a->id])>
                                    <td>
                                        <div class="fw-semibold">{{ $a->user->name }}</div>
                                        <div class="text-muted small">{{ $a->user->email }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $a->exam->title }}</div>
                                        <div class="text-muted small">{{ $a->exam->subject->name }}</div>
                                    </td>
                                    <td class="text-nowrap">{{ $a->score }}/{{ $a->total_marks }}</td>
                                    <td class="text-nowrap">
                                        <span class="badge bg-{{ $status === 'graded' ? 'success' : 'warning text-dark' }}">
                                            {{ $status === 'graded' ? 'Graded' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">{{ $a->submitted_at?->format('d M Y H:i') }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" wire:click="selectAttempt({{ $a->id }})">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-muted">No submitted attempts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            @if(!$selectedAttempt)
                <div class="card">
                    <div class="card-body text-muted">
                        Select an attempt to view the student’s answers.
                    </div>
                </div>
            @else
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="fw-semibold">{{ $selectedAttempt->user->name }} <span class="text-muted">({{ $selectedAttempt->user->email }})</span></div>
                                <div class="text-muted">
                                    {{ $selectedAttempt->exam->title }} — {{ $selectedAttempt->exam->subject->name }}
                                </div>
                                <div class="text-muted small">
                                    Started: {{ $selectedAttempt->started_at?->format('d M Y H:i') ?? '-' }}
                                    · Submitted: {{ $selectedAttempt->submitted_at?->format('d M Y H:i') ?? '-' }}
                                </div>
                            </div>
                            <div class="text-end">
                                @php $selectedStatus = $selectedAttempt->status ?: 'graded'; @endphp
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <div class="badge bg-success fs-6">
                                        {{ $selectedAttempt->score }}/{{ $selectedAttempt->total_marks }}
                                    </div>
                                    <span class="badge bg-{{ $selectedStatus === 'graded' ? 'success' : 'warning text-dark' }}">
                                        {{ $selectedStatus === 'graded' ? 'Graded' : 'Pending review' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if(($selectedAttempt->status ?: 'graded') !== 'graded')
                            <div class="d-flex gap-2 mt-3">
                                <button class="btn btn-outline-primary" wire:click="saveDraft">Save marks (draft)</button>
                                <button class="btn btn-primary" wire:click="publishResult">Publish result</button>
                            </div>
                            <div class="text-muted small mt-2">
                                Students will only see the final score after you publish.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        @foreach($selectedAttempt->exam->questions as $index => $q)
                            @php
                                $studentAnswer = $selectedAttempt->answers[$q->id] ?? null;
                                $isMcq = $q->type === \App\Models\Question::TYPE_MULTIPLE_CHOICE;
                                $correctKey = $q->correct_answer;
                                $isCorrect = $isMcq && $correctKey !== null && (string)$studentAnswer === (string)$correctKey;
                                $studentLabel = $isMcq ? ($q->options[$studentAnswer] ?? null) : null;
                                $correctLabel = $isMcq ? ($q->options[$correctKey] ?? null) : null;
                            @endphp

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-bold">Q{{ $index + 1 }} ({{ $q->marks }} mark{{ $q->marks > 1 ? 's' : '' }})</div>
                                        <div>{{ $q->question_text }}</div>
                                    </div>
                                    @if($isMcq)
                                        <span class="badge bg-{{ $isCorrect ? 'success' : 'danger' }}">
                                            {{ $isCorrect ? 'Correct' : 'Wrong' }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Open text</span>
                                    @endif
                                </div>

                                <div class="mt-2">
                                    <div class="small text-muted fw-semibold">Student answer</div>
                                    @if($isMcq)
                                        @if($studentAnswer === null || $studentAnswer === '')
                                            <div class="text-muted">No answer</div>
                                        @else
                                            <div>
                                                <span class="badge bg-light text-dark border">{{ $studentAnswer }}</span>
                                                <span class="ms-2">{{ $studentLabel }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="border rounded p-2 bg-light">
                                            {!! nl2br(e((string)($studentAnswer ?? ''))) !!}
                                        </div>
                                    @endif
                                </div>

                                @if($isMcq)
                                    <div class="mt-2">
                                        <div class="small text-muted fw-semibold">Correct answer</div>
                                        @if($correctKey === null || $correctKey === '')
                                            <div class="text-muted">Not set</div>
                                        @else
                                            <div>
                                                <span class="badge bg-light text-dark border">{{ $correctKey }}</span>
                                                <span class="ms-2">{{ $correctLabel }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @elseif($q->correct_answer)
                                    <div class="mt-2">
                                        <div class="small text-muted fw-semibold">Reference answer</div>
                                        <div class="border rounded p-2 bg-light">
                                            {!! nl2br(e((string)$q->correct_answer)) !!}
                                        </div>
                                    </div>
                                @endif

                                @if($q->type === \App\Models\Question::TYPE_OPEN_TEXT && (($selectedAttempt->status ?: 'graded') !== 'graded'))
                                    <div class="mt-3">
                                        <label class="form-label small text-muted fw-semibold mb-1">Marks awarded (0–{{ $q->marks }})</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input
                                                type="number"
                                                min="0"
                                                max="{{ $q->marks }}"
                                                class="form-control form-control-sm"
                                                style="max-width: 140px;"
                                                wire:model.lazy="openTextMarks.{{ $q->id }}"
                                            >
                                            <span class="text-muted small">/ {{ $q->marks }}</span>
                                        </div>
                                        @error("openTextMarks.$q->id")
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                            </div>

                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

