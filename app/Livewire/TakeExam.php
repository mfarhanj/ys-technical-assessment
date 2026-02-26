<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use Livewire\Component;

class TakeExam extends Component
{
    public Exam $exam;
    public array $answers = [];
    public ?int $attemptId = null;
    public bool $submitted = false;
    public ?int $score = null;
    public ?int $totalMarks = null;
    public ?int $startedAtTimestamp = null;

    public function mount(Exam $exam)
    {
        $this->exam = $exam->load('questions');
        $user = auth()->user();
        $classIds = $user->classes()->pluck('classes.id');
        if (!$exam->classes()->whereIn('classes.id', $classIds)->exists() || !$exam->is_published) {
            abort(403, 'Exam not available.');
        }
        $existing = $user->examAttempts()->where('exam_id', $exam->id)->latest()->first();
        if ($existing) {
            if ($existing->submitted_at) {
                $this->submitted = true;
                $this->score = $existing->score;
                $this->totalMarks = $existing->total_marks;
                return;
            }
            $this->attemptId = $existing->id;
            $this->answers = $existing->answers ?? [];
            $this->startedAtTimestamp = $existing->started_at->timestamp;
            return;
        }
        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'user_id' => $user->id,
            'started_at' => now(),
            'total_marks' => $exam->total_marks,
            'answers' => [],
        ]);
        $this->attemptId = $attempt->id;
        $this->startedAtTimestamp = $attempt->started_at->timestamp;
    }

    public function submit()
    {
        if ($this->submitted || !$this->attemptId) return;
        $attempt = ExamAttempt::find($this->attemptId);
        if (!$attempt || $attempt->submitted_at) return;
        $score = 0;
        foreach ($this->exam->questions as $q) {
            $ans = $this->answers[$q->id] ?? null;
            if ($q->type === Question::TYPE_MULTIPLE_CHOICE && $q->correct_answer && (string) $ans === (string) $q->correct_answer) {
                $score += $q->marks;
            }
        }
        $attempt->update([
            'submitted_at' => now(),
            'answers' => $this->answers,
            'score' => $score,
        ]);
        $this->submitted = true;
        $this->score = $score;
        $this->totalMarks = $this->exam->total_marks;
        $message = 'Exam submitted. Score: ' . $score . '/' . $this->exam->total_marks;

        session()->flash('message', $message);
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function render()
    {
        return view('livewire.take-exam');
    }
}
