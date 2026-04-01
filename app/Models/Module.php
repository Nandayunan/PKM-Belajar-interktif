<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'subject_id',
        'name',
        'module_number',
        'description',
        'content',
        'video_url',
        'pdf_path',
        'created_by',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'module_id');
    }

    public function progress()
    {
        return $this->hasMany(StudentProgress::class, 'module_id');
    }
}
