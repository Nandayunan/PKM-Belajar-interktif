<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSettings extends Model
{
    protected $table = 'teacher_settings';

    protected $fillable = [
        'teacher_id',
        'show_correct_answers',
        'show_wrong_answers',
        'show_score',
    ];

    protected $casts = [
        'show_correct_answers' => 'boolean',
        'show_wrong_answers' => 'boolean',
        'show_score' => 'boolean',
    ];

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
