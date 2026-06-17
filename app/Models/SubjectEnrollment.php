<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Subject;

class SubjectEnrollment extends Model
{
    protected $table = 'subject_enrollments';

    protected $fillable = [
        'user_id',
        'subject_id',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
