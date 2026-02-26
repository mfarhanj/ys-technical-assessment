<div class="container" x-data="{
    timeLeft: {{ $exam->time_limit_minutes * 60 }},
    started: false,
    init() {
        @if($submitted) return; @endif
        const start = {{ $startedAtTimestamp ?? now()->timestamp }};
        const limit = {{ $exam->time_limit_minutes * 60 }};
        const update = () => {
            const elapsed = Math.floor(Date.now() / 1000) - start;
            this.timeLeft = Math.max(0, limit - elapsed);
            if (this.timeLeft <= 0) {
                this.$wire.submit();
                return;
            }
            this.started = true;
        };
        update();
        const t = setInterval(update, 1000);
        this.$watch('timeLeft', v => { if (v <= 0) clearInterval(t); });
    },
    get minutes() { return Math.floor(this.timeLeft / 60); },
    get seconds() { return this.timeLeft % 60; }
}">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>{{ $exam->title }}</h4>
        @if(!$submitted)
            <div class="badge bg-{{ $exam->time_limit_minutes >= 1 ? 'primary' : 'secondary' }} fs-6" x-show="started" x-cloak>
                <span x-text="String(minutes).padStart(2,'0') + ':' + String(seconds).padStart(2,'0')"></span> remaining
            </div>
        @endif
    </div>

    @if($submitted)
        <div class="card mb-4">
            <div class="card-body text-center">
                <h5>Exam submitted</h5>
                <p class="display-6 mb-0">Score: {{ $score }}/{{ $totalMarks }}</p>
                <a href="{{ route('student.exams') }}" class="btn btn-primary mt-3">Back to exams</a>
            </div>
        </div>
    @else
        <form wire:submit="submit" class="mb-4">
            @foreach($exam->questions as $index => $q)
                <div class="card mb-3" wire:key="q-{{ $q->id }}">
                    <div class="card-body">
                        <p class="fw-bold">Q{{ $index + 1 }} ({{ $q->marks }} mark{{ $q->marks > 1 ? 's' : '' }})</p>
                        <p>{{ $q->question_text }}</p>
                        @if($q->type === 'multiple_choice' && $q->options)
                            <div class="ms-3">
                                @foreach($q->options as $key => $label)
                                    @if(trim($label))
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" id="a{{ $q->id }}_{{ $key }}"
                                                   wire:model="answers.{{ $q->id }}">
                                            <label class="form-check-label" for="a{{ $q->id }}_{{ $key }}">{{ $key }}. {{ $label }}</label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @elseif($q->type === 'open_text')
                            <textarea class="form-control" wire:model="answers.{{ $q->id }}" rows="3" placeholder="Your answer"></textarea>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Submit exam</button>
                <a href="{{ route('student.exams') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    @endif
</div>
