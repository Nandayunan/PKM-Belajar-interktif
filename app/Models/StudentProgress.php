<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    protected $table = 'student_progress';

    protected $fillable = [
        'user_id',
        'subject_id',
        'module_id',
        'total_questions',
        'answered_questions',
        'correct_answers',
        'total_points',
        'earned_points',
        'percentage',
        'status',
    ];

    protected $casts = [
        'percentage' => 'float',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
