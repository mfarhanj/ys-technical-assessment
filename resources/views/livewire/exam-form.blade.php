<div class="container">
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="card mb-4">
        <div class="card-header">{{ $exam ? 'Edit Exam' : 'Create Exam' }}</div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model="title" required>
                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description (optional)</label>
                <textarea class="form-control" wire:model="description" rows="2"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Subject</label>
                    <select class="form-select" wire:model="subject_id" required>
                        <option value="">Select subject</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Time limit (minutes)</label>
                    <input type="number" class="form-control" wire:model="time_limit_minutes" min="1" max="300">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label d-block">Published</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" wire:model="is_published" id="pub">
                        <label class="form-check-label" for="pub">Visible to assigned classes</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Questions</span>
            <button type="button" class="btn btn-sm btn-primary" wire:click="addQuestion">+ Add question</button>
        </div>
        <div class="card-body">
            @foreach($questions as $index => $q)
                <div class="border rounded p-3 mb-3" wire:key="q-{{ $index }}">
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Question {{ $index + 1 }}</strong>
                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeQuestion({{ $index }})">Remove</button>
                    </div>
                    <div class="mb-2">
                        <textarea class="form-control" wire:model="questions.{{ $index }}.question_text" rows="2" placeholder="Question text"></textarea>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" wire:model="questions.{{ $index }}.type">
                                <option value="multiple_choice">Multiple choice</option>
                                <option value="open_text">Open text</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control form-control-sm" wire:model="questions.{{ $index }}.marks" min="1" placeholder="Marks">
                        </div>
                    </div>
                    @if(($questions[$index]['type'] ?? '') === 'multiple_choice')
                        <div class="small">
                            @foreach(['A','B','C','D'] as $opt)
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text" style="width: 30px;">{{ $opt }}</span>
                                    <input type="text" class="form-control" wire:model="questions.{{ $index }}.options.{{ $opt }}" placeholder="Option {{ $opt }}">
                                    <div class="form-check form-check-inline ms-2">
                                        <input class="form-check-input" type="radio" name="correct_{{ $index }}" value="{{ $opt }}" wire:model="questions.{{ $index }}.correct_answer" id="c{{ $index }}_{{ $opt }}">
                                        <label class="form-check-label" for="c{{ $index }}_{{ $opt }}">Correct</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Assign to classes</div>
        <div class="card-body">
            <p class="text-muted small">Assign this exam to classes so students in those classes can take it. You can assign after saving the exam from the exam list.</p>
            @if($exam && $exam->id)
                @livewire('exam-class-assign', ['exam' => $exam], key('exam-class-'.$exam->id))
            @endif
        </div>
    </div>

    <div class="mb-4">
        <button type="button" class="btn btn-primary" wire:click="save">Save Exam</button>
        <a href="{{ route('lecturer.exams') }}" class="btn btn-secondary">Cancel</a>
    </div>
</div>
