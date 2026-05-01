<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'password',
        'role',
        'class',
        'homeroom_teacher',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'password' => 'hashed',
        'role' => 'integer',
    ];

    // Relationships
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'created_by');
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'created_by');
    }

    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class, 'user_id');
    }

    public function progress()
    {
        return $this->hasMany(StudentProgress::class, 'user_id');
    }

    public function teacherSettings()
    {
        return $this->hasOne(TeacherSettings::class, 'teacher_id');
    }

    // Helpers
    public function isTeacher()
    {
        return $this->role === 1;
    }

    public function isStudent()
    {
        return $this->role === 0;
    }
}
