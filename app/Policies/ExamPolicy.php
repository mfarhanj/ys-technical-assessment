<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;

class ExamPolicy
{
    public function update(User $user, Exam $exam): bool
    {
        return (int) $exam->created_by === (int) $user->id;
    }

    public function delete(User $user, Exam $exam): bool
    {
        return (int) $exam->created_by === (int) $user->id;
    }
}
