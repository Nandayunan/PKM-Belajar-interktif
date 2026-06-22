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

    public function getClassChips(array $availableClassesByGrade = []): array
    {
        if (!$this->class) {
            return [];
        }

        $parts = explode('-', $this->class, 2);
        $grade = $parts[0];
        $sectionPart = $parts[1] ?? '';
        $actualGradeClasses = $availableClassesByGrade[$grade] ?? [];

        if (strtoupper($sectionPart) === 'ALL' || $sectionPart === '') {
            if (!empty($actualGradeClasses)) {
                return $actualGradeClasses;
            }

            if (strtoupper($sectionPart) === 'ALL') {
                return collect(['A', 'B', 'C', 'D'])->map(fn($section) => $grade . '-' . $section)->all();
            }

            return [$grade];
        }

        $subjectClassChips = [];
        foreach (explode(',', $sectionPart) as $section) {
            $section = trim(strtoupper($section));
            if ($section === '') {
                continue;
            }

            $sectionClass = $grade . '-' . $section;
            if (!empty($actualGradeClasses)) {
                if (in_array($sectionClass, $actualGradeClasses, true)) {
                    $subjectClassChips[] = $sectionClass;
                }
            } else {
                $subjectClassChips[] = $sectionClass;
            }
        }

        return $subjectClassChips;
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
