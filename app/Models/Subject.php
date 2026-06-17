<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectEnrollment;
use App\Models\User;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'class',
        'access_code',
        'created_by',
    ];

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'subject_id');
    }

    public function publishedModules()
    {
        return $this->hasMany(Module::class, 'subject_id')->where('published', true);
    }

    public function progress()
    {
        return $this->hasMany(StudentProgress::class, 'subject_id');
    }

    public function enrollments()
    {
        return $this->hasMany(SubjectEnrollment::class, 'subject_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'subject_enrollments', 'subject_id', 'user_id')
            ->withTimestamps();
    }
}
