<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->string('status')->default('in_progress')->after('submitted_at');
            $table->json('awarded_marks')->nullable()->after('answers'); // { question_id => int|null }
            $table->timestamp('graded_at')->nullable()->after('awarded_marks');
            $table->unsignedBigInteger('graded_by')->nullable()->after('graded_at');

            $table->index(['status', 'submitted_at']);
            $table->foreign('graded_by')->references('id')->on('users')->nullOnDelete();
        });

        // Backfill existing submitted attempts as graded so results remain visible.
        DB::table('exam_attempts')
            ->whereNotNull('submitted_at')
            ->update([
                'status' => 'graded',
            ]);
    }

    public function down(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropForeign(['graded_by']);
            $table->dropIndex(['status', 'submitted_at']);
            $table->dropColumn(['status', 'awarded_marks', 'graded_at', 'graded_by']);
        });
    }
};

