<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id', 'question_text', 'type', 'order', 'marks',
        'options', 'correct_answer',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public const TYPE_MULTIPLE_CHOICE = 'multiple_choice';
    public const TYPE_OPEN_TEXT = 'open_text';

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function isMultipleChoice(): bool
    {
        return $this->type === self::TYPE_MULTIPLE_CHOICE;
    }
}
