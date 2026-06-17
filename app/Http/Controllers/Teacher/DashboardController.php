<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Module;
use App\Models\Question;
use App\Models\StudentProgress;
use App\Models\User;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestionAnswer;
use App\Models\TeacherNote;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $subjects = Subject::where('created_by', $user->id)->get();
        $modules = Module::whereIn('subject_id', $subjects->pluck('id'))->get();
        $questions = Question::whereIn('module_id', $modules->pluck('id'))->get();

        $totalSubjects = $subjects->count();
        $totalModules = $modules->count();
        $totalQuestions = $questions->count();
        $totalStudents = User::where('role', 0)->count();

        $rawProgress = StudentProgress::whereIn('subject_id', $subjects->pluck('id'))
            ->with('user', 'subject')
            ->get();

        // Aggregate progress by user + subject so each student appears once per subject
        $studentProgress = $rawProgress->groupBy(function ($p) {
            return $p->user_id . '_' . $p->subject_id;
        })->map(function ($group) {
            $first = $group->first();
            $total_questions = $group->sum('total_questions');
            $answered = $group->sum('answered_questions');
            $correct = $group->sum('correct_answers');
            $total_points = $group->sum('total_points');
            $earned_points = $group->sum('earned_points');

            $percentage = $total_questions > 0 ? ($correct / max(1, $total_questions)) * 100 : 0;

            // determine status: if any in_progress -> in_progress, elseif all completed -> completed, else not_started
            $statuses = $group->pluck('status')->unique()->filter()->values();
            if ($statuses->contains('in_progress')) {
                $status = 'in_progress';
            } elseif ($statuses->contains('completed') && $statuses->count() === 1) {
                $status = 'completed';
            } else {
                $status = $statuses->isEmpty() ? 'not_started' : $statuses->first();
            }

            $first->total_questions = $total_questions;
            $first->answered_questions = $answered;
            $first->correct_answers = $correct;
            $first->total_points = $total_points;
            $first->earned_points = $earned_points;
            $first->percentage = $percentage;
            $first->status = $status;

            return $first;
        })->values();

        // Fetch recent submissions (answers) for questions created by this teacher
        $submissions = QuestionAnswer::whereHas('question', function ($q) use ($user) {
            $q->where('created_by', $user->id);
        })
            ->with('user', 'question', 'question.module')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($a) => $a->user_id);

        $selectedAcademicYear = $request->query('academic_year');
        $selectedClass = $request->query('class');
        $searchQ = $request->query('q');
        $activeTab = $request->query('tab');

        // Fetch class list (distinct class values from students), scoped by active academic year if set
            // Fetch class list (distinct class values from students), scoped by selected academic year
            $classes = User::where('role', 0)
                ->when($selectedAcademicYear, fn($q) => $q->where('academic_year', $selectedAcademicYear))
                ->whereNotNull('class')
                ->where('class', '!=', '')
                ->select('class', \DB::raw('count(*) as total'))
                ->groupBy('class')
                ->get()
                ->map(fn($r) => (object)['name' => $r->class, 'count' => $r->total]);

            $academicYears = AcademicYear::orderBy('name')->get();
            foreach ($academicYears as $year) {
                $year->student_count = User::where('role', 0)
                    ->where('academic_year', $year->name)
                    ->count();
                $year->class_count = User::where('role', 0)
                    ->where('academic_year', $year->name)
                    ->whereNotNull('class')
                    ->where('class', '!=', '')
                    ->distinct()
                    ->count('class');
            }

        $studentsQuery = User::where('role', 0);
        if ($selectedAcademicYear) {
            $studentsQuery->where('academic_year', $selectedAcademicYear);
        }
        if ($selectedClass) {
            $studentsQuery->where('class', $selectedClass);
        }

        if ($searchQ) {
            $studentsQuery->where('name', 'like', '%' . $searchQ . '%');
        }

        $students = $studentsQuery->orderBy('class')->orderBy('name')->paginate(15)->withQueryString();

        // Fetch teacher notes grouped by user
        $teacherNotes = TeacherNote::where('teacher_id', $user->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($n) => $n->user_id);

        return view('guru.dashboard', [
            'subjects' => $subjects,
            'modules' => $modules,
            'questions' => $questions,
            'studentProgress' => $studentProgress,
            'students' => $students,
            'classes' => $classes,
            'academicYears' => $academicYears,
            'selectedClass' => $selectedClass,
            'selectedAcademicYear' => $selectedAcademicYear,
            'searchQ' => $searchQ,
            'activeTab' => $activeTab,
            'submissions' => $submissions,
            'teacherNotes' => $teacherNotes,
            'totalSubjects' => $totalSubjects,
            'totalModules' => $totalModules,
            'totalQuestions' => $totalQuestions,
            'totalStudents' => $totalStudents,
        ]);
    }

    public function studentsAjaxList(Request $request)
    {
        $selectedAcademicYear = $request->query('academic_year');
        $selectedClass = $request->query('class');
        $searchQ = $request->query('q');
        $editable = $request->query('editable') ? true : false;

        $studentsQuery = User::where('role', 0);
        if ($selectedAcademicYear) {
            $studentsQuery->where('academic_year', $selectedAcademicYear);
        }
        if ($selectedClass) {
            $studentsQuery->where('class', $selectedClass);
        }
        if ($searchQ) {
            $studentsQuery->where('name', 'like', '%' . $searchQ . '%');
        }

        $students = $studentsQuery->orderBy('class')->orderBy('name')->paginate(15)->withQueryString();

        $classes = User::where('role', 0)
            ->when($selectedAcademicYear, fn($q) => $q->where('academic_year', $selectedAcademicYear))
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->select('class', \DB::raw('count(*) as total'))
            ->groupBy('class')
            ->get()
            ->map(fn($r) => (object)['name' => $r->class, 'count' => $r->total]);

        $academicYears = AcademicYear::orderBy('name')->get();

        return view('guru.students.partials.list', [
            'students' => $students,
            'classes' => $classes,
            'academicYears' => $academicYears,
            'selectedAcademicYear' => $selectedAcademicYear,
            'selectedClass' => $selectedClass,
            'searchQ' => $searchQ,
            'editable' => $editable,
        ]);
    }

    /**
     * Assign multiple students to classes for a specific academic year
     */
    public function assignStudents(Request $request, AcademicYear $academicYear)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $validated = $request->validate([
            'students' => 'required|array',
            'students.*.id' => 'required|integer|exists:users,id',
            'students.*.class' => 'nullable|string|max:50',
            'students.*.homeroom_teacher' => 'nullable|string|max:255',
        ]);

        $count = 0;
        foreach ($validated['students'] as $s) {
            $user = User::where('id', $s['id'])->where('academic_year', $academicYear->name)->first();
            if (!$user) continue;

            $oldClass = $user->class;
            $oldHomeroom = $user->homeroom_teacher;

            $user->class = $s['class'] ?? null;
            if (array_key_exists('homeroom_teacher', $s)) {
                $user->homeroom_teacher = $s['homeroom_teacher'];
            }
            $user->save();

            // record history only if changed
            if ($user->wasChanged(['class', 'homeroom_teacher'])) {
                \App\Models\StudentClassHistory::create([
                    'user_id' => $user->id,
                    'academic_year' => $academicYear->name,
                    'student_class' => $user->class,
                    'homeroom_teacher' => $user->homeroom_teacher,
                ]);
            }

            $count++;
        }

        return response()->json(['message' => "{$count} siswa berhasil diperbarui untuk tahun ajaran {$academicYear->name}"]); 
    }

    /**
     * Store a teacher note for a student (optional subject/module scoped)
     */
    public function storeNote(Request $request)
    {
        $teacher = Auth::user();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'module_id' => 'nullable|exists:modules,id',
            'note' => 'required|string|max:2000',
        ]);

        TeacherNote::create([
            'teacher_id' => $teacher->id,
            'user_id' => $validated['user_id'],
            'subject_id' => $validated['subject_id'] ?? null,
            'module_id' => $validated['module_id'] ?? null,
            'note' => $validated['note'],
        ]);

        return back()->with('success', 'Catatan berhasil disimpan untuk siswa');
    }

    /**
     * Store a new class name
     */
    public function storeClass(Request $request)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:academic_years,name',
        ], [
            'name.unique' => 'Tahun ajaran dengan nama ini sudah ada.'
        ]);

        AcademicYear::create(['name' => $validated['name']]);

        return response()->json([
            'message' => "Tahun ajaran '{$validated['name']}' berhasil dibuat!"
        ]);
    }

    /**
     * Update class name and reassign all students
     */
    public function updateClass(Request $request, AcademicYear $academicYear)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $newAcademicYearName = $validated['name'];

        if ($academicYear->name !== $newAcademicYearName && AcademicYear::where('name', $newAcademicYearName)->exists()) {
            return response()->json([
                'message' => "Tahun ajaran dengan nama '{$newAcademicYearName}' sudah ada!"
            ], 422);
        }

        $oldName = $academicYear->name;
        $academicYear->update(['name' => $newAcademicYearName]);
        $updated = User::where('academic_year', $oldName)->update(['academic_year' => $newAcademicYearName]);

        return response()->json([
            'message' => "Tahun ajaran berhasil diupdate! {$updated} siswa telah disesuaikan."
        ]);
    }

    /**
     * Delete a class and reassign all students to empty class
     */
    public function destroyClass(Request $request, AcademicYear $academicYear)
    {
        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $name = $academicYear->name;
        $academicYear->delete();
        $updated = User::where('academic_year', $name)->update(['academic_year' => null]);

        return response()->json([
            'message' => "Tahun ajaran '{$name}' berhasil dihapus! {$updated} siswa telah dikosongkan tahun ajarannya."
        ]);
    }
}

