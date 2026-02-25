<?php

namespace App\Livewire;

use App\Models\ClassModel;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use Livewire\Component;

class ExamForm extends Component
{
    public ?Exam $exam = null;
    public $title = '';
    public $description = '';
    public $subject_id = '';
    public $time_limit_minutes = 15;
    public $is_published = false;

    /** @var array<int, array{question_text: string, type: string, marks: int, options: ?array, correct_answer: ?string}> */
    public $questions = [];

    public function mount(?Exam $exam = null)
    {
        if ($exam && $exam->id) {
            $this->exam = $exam;
            $this->title = $exam->title;
            $this->description = $exam->description ?? '';
            $this->subject_id = (string) $exam->subject_id;
            $this->time_limit_minutes = $exam->time_limit_minutes;
            $this->is_published = $exam->is_published;
            foreach ($exam->questions()->orderBy('order')->get() as $i => $q) {
                $this->questions[] = [
                    'id' => $q->id,
                    'question_text' => $q->question_text,
                    'type' => $q->type,
                    'marks' => $q->marks,
                    'options' => $q->options ?? [],
                    'correct_answer' => $q->correct_answer ?? '',
                ];
            }
        } else {
            $this->questions = [
                ['id' => null, 'question_text' => '', 'type' => 'multiple_choice', 'marks' => 1, 'options' => ['A' => '', 'B' => '', 'C' => '', 'D' => ''], 'correct_answer' => ''],
            ];
        }
    }

    public function addQuestion()
    {
        $this->questions[] = ['id' => null, 'question_text' => '', 'type' => 'multiple_choice', 'marks' => 1, 'options' => ['A' => '', 'B' => '', 'C' => '', 'D' => ''], 'correct_answer' => ''];
    }

    public function removeQuestion($index)
    {
        array_splice($this->questions, $index, 1);
        if (empty($this->questions)) {
            $this->addQuestion();
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'time_limit_minutes' => 'required|integer|min:1|max:300',
        ]);
        $totalMarks = 0;
        foreach ($this->questions as $q) {
            if (trim($q['question_text'] ?? '') !== '') {
                $this->validateQuestion($q);
                $totalMarks += (int) ($q['marks'] ?? 1);
            }
        }

        if ($this->exam && $this->exam->id) {
            $exam = $this->exam;
            $exam->update([
                'title' => $this->title,
                'description' => $this->description,
                'subject_id' => (int) $this->subject_id,
                'time_limit_minutes' => (int) $this->time_limit_minutes,
                'is_published' => $this->is_published,
                'total_marks' => $totalMarks,
            ]);
            $existingIds = [];
            foreach ($this->questions as $order => $q) {
                if (trim($q['question_text'] ?? '') === '') continue;
                $opts = $q['options'] ?? [];
                $correct = $q['type'] === 'open_text' ? null : ($q['correct_answer'] ?? null);
                if (!empty($q['id'])) {
                    $question = Question::where('exam_id', $exam->id)->find($q['id']);
                    if ($question) {
                        $question->update([
                            'question_text' => $q['question_text'],
                            'type' => $q['type'],
                            'order' => $order,
                            'marks' => (int) ($q['marks'] ?? 1),
                            'options' => $q['type'] === 'multiple_choice' ? $opts : null,
                            'correct_answer' => $correct,
                        ]);
                        $existingIds[] = $question->id;
                    }
                } else {
                    $newQ = $exam->questions()->create([
                        'question_text' => $q['question_text'],
                        'type' => $q['type'],
                        'order' => $order,
                        'marks' => (int) ($q['marks'] ?? 1),
                        'options' => $q['type'] === 'multiple_choice' ? $opts : null,
                        'correct_answer' => $correct,
                    ]);
                    $existingIds[] = $newQ->id;
                }
            }
            $exam->questions()->whereNotIn('id', $existingIds)->delete();
        } else {
            $exam = Exam::create([
                'title' => $this->title,
                'description' => $this->description,
                'subject_id' => (int) $this->subject_id,
                'created_by' => auth()->id(),
                'time_limit_minutes' => (int) $this->time_limit_minutes,
                'total_marks' => $totalMarks,
                'is_published' => $this->is_published,
            ]);
            foreach ($this->questions as $order => $q) {
                if (trim($q['question_text'] ?? '') === '') continue;
                $exam->questions()->create([
                    'question_text' => $q['question_text'],
                    'type' => $q['type'],
                    'order' => $order,
                    'marks' => (int) ($q['marks'] ?? 1),
                    'options' => $q['type'] === 'multiple_choice' ? ($q['options'] ?? []) : null,
                    'correct_answer' => $q['type'] === 'open_text' ? null : ($q['correct_answer'] ?? null),
                ]);
            }
        }
        session()->flash('message', 'Exam saved.');
        return $this->redirect(route('lecturer.exams'), navigate: true);
    }

    protected function validateQuestion(array $q)
    {
        if (($q['type'] ?? '') === 'multiple_choice') {
            $opts = $q['options'] ?? [];
            $correct = $q['correct_answer'] ?? '';
            if (empty($correct) || empty($opts[$correct])) {
                throw \Illuminate\Validation\ValidationException::withMessages(['questions' => ['Each multiple choice question must have options and a correct answer selected.']]);
            }
        }
    }

    public function render()
    {
        return view('livewire.exam-form', [
            'subjects' => Subject::orderBy('name')->get(),
            'classes' => ClassModel::orderBy('name')->get(),
        ]);
    }
}
