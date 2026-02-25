<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
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

    public function progress()
    {
        return $this->hasMany(StudentProgress::class, 'subject_id');
    }
}
