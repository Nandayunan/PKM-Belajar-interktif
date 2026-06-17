<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClassHistory extends Model
{
    protected $fillable = [
        'user_id',
        'academic_year',
        'student_class',
        'homeroom_teacher',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
