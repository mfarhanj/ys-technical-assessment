<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class LecturerResults extends Component
{
    public ?int $examId = null;
    public string $studentSearch = '';
    public ?int $selectedAttemptId = null;
    public array $openTextMarks = [];

    public function selectAttempt(int $attemptId): void
    {
        $this->selectedAttemptId = $attemptId;
        $this->loadOpenTextMarks();
    }

    public function clearSelection(): void
    {
        $this->selectedAttemptId = null;
        $this->openTextMarks = [];
    }

    public function saveDraft(): void
    {
        $this->saveMarks(publish: false);
    }

    public function publishResult(): void
    {
        $this->saveMarks(publish: true);
    }

    private function loadOpenTextMarks(): void
    {
        $attempt = $this->getSelectedAttempt();
        if (!$attempt) {
            $this->openTextMarks = [];
            return;
        }

        $awarded = $attempt->awarded_marks ?? [];
        $marks = [];
        foreach ($attempt->exam->questions as $q) {
            if ($q->type !== Question::TYPE_OPEN_TEXT) {
                continue;
            }
            $marks[$q->id] = $awarded[$q->id] ?? null;
        }
        $this->openTextMarks = $marks;
    }

    private function getSelectedAttempt(): ?ExamAttempt
    {
        if (!$this->selectedAttemptId) {
            return null;
        }

        $lecturerId = (int) Auth::id();

        return ExamAttempt::query()
            ->whereKey($this->selectedAttemptId)
            ->whereNotNull('submitted_at')
            ->whereHas('exam', fn ($q) => $q->where('created_by', $lecturerId))
            ->with(['user', 'exam.subject', 'exam.questions'])
            ->first();
    }

    private function saveMarks(bool $publish): void
    {
        $attempt = $this->getSelectedAttempt();
        if (!$attempt) {
            return;
        }

        $lecturerId = (int) Auth::id();
        $answers = $attempt->answers ?? [];
        $awarded = $attempt->awarded_marks ?? [];

        foreach ($attempt->exam->questions as $q) {
            if ($q->type !== Question::TYPE_MULTIPLE_CHOICE) {
                continue;
            }
            $ans = $answers[$q->id] ?? null;
            $isCorrect = $q->correct_answer !== null && (string) $ans === (string) $q->correct_answer;
            $awarded[$q->id] = $isCorrect ? (int) $q->marks : 0;
        }

        foreach ($attempt->exam->questions as $q) {
            if ($q->type !== Question::TYPE_OPEN_TEXT) {
                continue;
            }

            $key = (string) $q->id;
            $raw = $this->openTextMarks[$key] ?? $this->openTextMarks[$q->id] ?? null;
            $field = "openTextMarks.{$q->id}";

            if ($raw === '' || $raw === null) {
                if ($publish) {
                    throw ValidationException::withMessages([$field => 'Marks are required to publish results.']);
                }
                $awarded[$q->id] = null;
                continue;
            }

            if (!is_numeric($raw)) {
                throw ValidationException::withMessages([$field => 'Marks must be a number.']);
            }

            $val = (int) $raw;
            if ($val < 0 || $val > (int) $q->marks) {
                throw ValidationException::withMessages([$field => "Marks must be between 0 and {$q->marks}."]);
            }

            $awarded[$q->id] = $val;
        }

        $score = 0;
        foreach ($awarded as $v) {
            if (is_int($v)) {
                $score += $v;
            } elseif (is_numeric($v)) {
                $score += (int) $v;
            }
        }

        $update = [
            'awarded_marks' => $awarded,
            'score' => $score,
        ];

        if ($publish) {
            $update['status'] = 'graded';
            $update['graded_at'] = now();
            $update['graded_by'] = $lecturerId;
        } else {
            if (($attempt->status ?: 'graded') !== 'graded') {
                $update['status'] = 'pending_review';
            }
        }

        $attempt->update($update);

        $message = $publish ? 'Results published.' : 'Marks saved (draft).';
        session()->flash('message', $message);
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function render()
    {
        $lecturerId = (int) Auth::id();

        $exams = Exam::query()
            ->where('created_by', $lecturerId)
            ->orderBy('title')
            ->get();

        $attemptsQuery = ExamAttempt::query()
            ->whereNotNull('submitted_at')
            ->whereHas('exam', fn ($q) => $q->where('created_by', $lecturerId))
            ->with(['user', 'exam.subject'])
            ->latest('submitted_at');

        if ($this->examId) {
            $attemptsQuery->where('exam_id', $this->examId);
        }

        $search = trim($this->studentSearch);
        if ($search !== '') {
            $attemptsQuery->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $attempts = $attemptsQuery->limit(200)->get();

        $selectedAttempt = $this->getSelectedAttempt();

        return view('livewire.lecturer-results', [
            'exams' => $exams,
            'attempts' => $attempts,
            'selectedAttempt' => $selectedAttempt,
        ]);
    }
}

