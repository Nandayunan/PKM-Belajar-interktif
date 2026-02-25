<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'module_id',
        'question',
        'type',
        'options',
        'correct_answer',
        'points',
        'created_by',
        'published',
    ];

    protected $casts = [
        'options' => 'json',
        'published' => 'boolean',
    ];

    // Relationships
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class, 'question_id');
    }
}
