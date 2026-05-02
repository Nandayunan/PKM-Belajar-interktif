<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Module;
use App\Models\Question;
use App\Models\StudentProgress;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function index($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        $modules = $subject->modules()->get();
        $user = Auth::user();

        // Initialize progress if not exists
        $progress = StudentProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'subject_id' => $subject->id,
            ],
            [
                'percentage' => 0,
                'status' => 'not_started',
                'total_questions' => 0,
                'correct_answers' => 0,
                'points_earned' => 0,
            ]
        );

        return view('siswa.modules.index', [
            'subject' => $subject,
            'modules' => $modules,
        ]);
    }

    public function show($subjectId, $moduleId)
    {
        $subject = Subject::findOrFail($subjectId);
        $module = Module::where('id', $moduleId)
            ->where('subject_id', $subjectId)
            ->firstOrFail();

        $questions = $module->questions()->get();
        $user = Auth::user();

        // Initialize module progress if not exists
        $moduleProgress = StudentProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'module_id' => $module->id,
            ],
            [
                'subject_id' => $subject->id,
                'percentage' => 0,
                'status' => 'not_started',
                'total_questions' => $questions->count(),
                'correct_answers' => 0,
                'points_earned' => 0,
            ]
        );

        // Convert YouTube URL to embed format
        $embedVideoUrl = null;
        if ($module->video_url) {
            $embedVideoUrl = $this->convertYouTubeToEmbed($module->video_url);
        }

        $totalModules = $subject->modules()->count();
        $currentQuestionNumber = 1;
        // Check if the student already has saved answers for this module
        $existingAnswers = \App\Models\QuestionAnswer::whereIn('question_id', $questions->pluck('id')->toArray())
            ->where('user_id', $user->id)
            ->get();

        $hasAnswers = $existingAnswers->isNotEmpty();

        // compute earned points and possible points as an estimate
        $earnedPoints = $existingAnswers->sum('points_earned');
        $pointsPossible = $questions->sum('points');

        return view('siswa.modules.show', [
            'subject' => $subject,
            'module' => $module,
            'questions' => $questions,
            'embedVideoUrl' => $embedVideoUrl,
            'totalModules' => $totalModules,
            'currentQuestionNumber' => $currentQuestionNumber,
            'hasAnswers' => $hasAnswers,
            'earnedPoints' => $earnedPoints,
            'pointsPossible' => $pointsPossible,
        ]);
    }

    public function submitAnswer(Request $request, $subjectId, $moduleId)
    {
        $subject = Subject::findOrFail($subjectId);
        $module = Module::where('id', $moduleId)
            ->where('subject_id', $subjectId)
            ->firstOrFail();

        $questions = $module->questions()->get();
        $user = Auth::user();

        $answers = $request->input('answers', []);
        $totalCorrect = 0;
        $totalPoints = 0;
        $totalPointsPossible = 0;

        foreach ($questions as $question) {
            $answer = $answers[$question->id] ?? null;
            $totalPointsPossible += $question->points;

            // Check if answer is correct
            $isCorrect = false;
            $pointsEarned = 0;

            if ($question->type === 'essay') {
                // Essay answers need manual checking — store as not correct (requires manual grading)
                $isCorrect = false;
                $pointsEarned = 0;
            } elseif ($question->type === 'true_false') {
                $correctAnswer = $question->correct_answer;
                $isCorrect = strtolower($answer) === strtolower($correctAnswer);
                if ($isCorrect) {
                    $pointsEarned = $question->points;
                    $totalCorrect++;
                }
            } else { // multiple choice
                $correctAnswer = $question->correct_answer;
                $isCorrect = $answer === $correctAnswer;
                if ($isCorrect) {
                    $pointsEarned = $question->points;
                    $totalCorrect++;
                }
            }

            // Save or update answer
            QuestionAnswer::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'question_id' => $question->id,
                ],
                [
                    'answer' => $answer,
                    'is_correct' => $isCorrect,
                    'points_earned' => $pointsEarned,
                ]
            );

            $totalPoints += $pointsEarned;
        }

        // Update module progress
        $percentage = $questions->count() > 0 ? ($totalCorrect / $questions->count()) * 100 : 0;

        $moduleProgress = StudentProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'module_id' => $module->id,
            ],
            [
                'subject_id' => $subject->id,
                'percentage' => $percentage,
                'status' => $percentage >= 70 ? 'completed' : 'in_progress',
                'total_questions' => $questions->count(),
                'correct_answers' => $totalCorrect,
                'points_earned' => $totalPoints,
            ]
        );

        // Update subject progress
        $moduleProgresses = StudentProgress::where('user_id', $user->id)
            ->where('subject_id', $subject->id)
            ->whereNotNull('module_id')
            ->get();

        if ($moduleProgresses->count() > 0) {
            $avgPercentage = $moduleProgresses->avg('percentage');
            $subjectStatus = $avgPercentage >= 70 ? 'completed' : 'in_progress';
            $totalCorrect = $moduleProgresses->sum('correct_answers');
            $totalQuestions = $moduleProgresses->sum('total_questions');
            $totalPoints = $moduleProgresses->sum('points_earned');

            StudentProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'subject_id' => $subject->id,
                ],
                [
                    'percentage' => $avgPercentage,
                    'status' => $subjectStatus,
                    'total_questions' => $totalQuestions,
                    'correct_answers' => $totalCorrect,
                    'points_earned' => $totalPoints,
                ]
            );
        }

        // Redirect to feedback form if no feedback submitted yet
        if ($moduleProgress->feedback_submitted_at === null) {
            return redirect()->route('siswa.feedback.create', $moduleProgress->id)
                ->with('success', "Jawaban Anda berhasil disimpan! Anda mendapat {$totalPoints} dari {$totalPointsPossible} poin.");
        }

        return redirect()->route('siswa.modules.show', [$subjectId, $moduleId])
            ->with('success', "Jawaban Anda berhasil disimpan! Anda mendapat {$totalPoints} dari {$totalPointsPossible} poin.");
    }

    /**
     * Show a review page for a completed module where student can see which answers were correct.
     */
    public function review($subjectId, $moduleId)
    {
        $subject = Subject::findOrFail($subjectId);
        $module = Module::where('id', $moduleId)
            ->where('subject_id', $subjectId)
            ->firstOrFail();

        $questions = $module->questions()->get();
        $user = Auth::user();

        // Load student answers for these questions
        $answers = \App\Models\QuestionAnswer::whereIn('question_id', $questions->pluck('id')->toArray())
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('question_id');

        // Get teacher settings to decide which details to show
        $teacherSettings = $module->teacher?->teacherSettings ?? null;

        return view('siswa.modules.review', [
            'subject' => $subject,
            'module' => $module,
            'questions' => $questions,
            'answers' => $answers,
            'teacherSettings' => $teacherSettings,
        ]);
    }

    private function convertYouTubeToEmbed($url)
    {
        // Normalize and extract video ID robustly to handle many YouTube URL formats
        if (empty($url)) {
            return null;
        }

        // Ensure scheme present
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        $host = parse_url($url, PHP_URL_HOST) ?: '';
        $path = parse_url($url, PHP_URL_PATH) ?: '';
        $query = parse_url($url, PHP_URL_QUERY) ?: '';

        $videoId = null;

        // 1) youtu.be short links -> path contains the id
        if (str_contains($host, 'youtu.be')) {
            $videoId = trim($path, '/');
        }

        // 2) youtube.com/watch?v=... or other query variations
        if (!$videoId && str_contains($host, 'youtube.com')) {
            parse_str($query, $params);
            if (!empty($params['v'])) {
                $videoId = $params['v'];
            }

            // support embed path like /embed/VIDEOID
            if (!$videoId && preg_match('#/embed/([a-zA-Z0-9_-]+)#', $path, $m)) {
                $videoId = $m[1];
            }

            // support /v/VIDEOID legacy path
            if (!$videoId && preg_match('#/v/([a-zA-Z0-9_-]+)#', $path, $m2)) {
                $videoId = $m2[1];
            }
        }

        // 3) If still not found, try to extract last path segment as fallback
        if (!$videoId) {
            if (preg_match('#([a-zA-Z0-9_-]{6,})$#', $path, $m3)) {
                $videoId = $m3[1];
            }
        }

        if (!$videoId) {
            return null;
        }

        // Clean video id (strip params if any)
        $videoId = preg_replace('/[^a-zA-Z0-9_-]/', '', $videoId);

        // Use privacy-enhanced domain and disable related videos (rel=0)
        $embedUrl = "https://www.youtube-nocookie.com/embed/{$videoId}?rel=0"
            . "&modestbranding=1";

        // Return iframe markup with necessary allow attributes for playback and picture-in-picture
        return '<iframe width="100%" height="100%" src="' . e($embedUrl) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
    }
}
