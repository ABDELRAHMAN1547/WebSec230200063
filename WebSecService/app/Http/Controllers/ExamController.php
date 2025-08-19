<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::where('is_active', true)
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now())
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('exams.index', compact('exams'));
    }

    public function start($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);
        
        // التحقق من أن الامتحان متاح
        if (!$exam->isAvailable()) {
            return redirect()->route('exams.index')->with('error', 'هذا الامتحان غير متاح حالياً');
        }

        // التحقق من عدم وجود محاولة سابقة
        $existingAttempt = ExamAttempt::where('user_id', Auth::id())
                                    ->where('exam_id', $id)
                                    ->whereNotNull('completed_at')
                                    ->first();
        
        if ($existingAttempt) {
            return redirect()->route('exams.index')->with('error', 'لقد قمت بهذا الامتحان من قبل');
        }

        // إنشاء محاولة جديدة أو استئناف محاولة موجودة
        $attempt = ExamAttempt::firstOrCreate([
            'user_id' => Auth::id(),
            'exam_id' => $id,
        ], [
            'started_at' => now(),
        ]);

        if (!$attempt->started_at) {
            $attempt->update(['started_at' => now()]);
        }

        return view('exams.take', compact('exam', 'attempt'));
    }

    public function submit(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);
        $attempt = ExamAttempt::where('user_id', Auth::id())
                            ->where('exam_id', $id)
                            ->firstOrFail();

        // حساب النتيجة
        $answers = $request->input('answers', []);
        $score = 0;
        $totalPoints = 0;

        foreach ($exam->questions as $question) {
            $totalPoints += $question->points;
            if (isset($answers[$question->id]) && $answers[$question->id] === $question->correct_answer) {
                $score += $question->points;
            }
        }

        // تحديث المحاولة
        $attempt->update([
            'completed_at' => now(),
            'score' => $score,
            'answers' => $answers,
            'is_passed' => $score >= $exam->passing_score,
        ]);

        return redirect()->route('exams.result', $attempt->id)->with('success', 'تم إرسال الامتحان بنجاح');
    }

    public function result($attemptId)
    {
        $attempt = ExamAttempt::with(['exam', 'exam.questions'])->findOrFail($attemptId);
        
        // التحقق من أن المستخدم يمكنه رؤية هذه النتيجة
        if ($attempt->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('exams.index')->with('error', 'ليس لديك صلاحية لعرض هذه النتيجة');
        }

        return view('exams.result', compact('attempt'));
    }

    public function adminIndex()
    {
        $exams = Exam::withCount('questions')->orderBy('created_at', 'desc')->paginate(10);
        return view('exams.admin.index', compact('exams'));
    }

    public function adminCreate()
    {
        $questions = Question::orderBy('category')->orderBy('difficulty')->get();
        return view('exams.admin.create', compact('questions'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:1|max:100',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'questions' => 'required|array|min:1',
            'questions.*' => 'exists:questions,id',
        ]);

        try {
            $exam = Exam::create([
                'title' => $request->title,
                'description' => $request->description,
                'duration_minutes' => $request->duration_minutes,
                'passing_score' => $request->passing_score,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_active' => true,
            ]);

            // إضافة الأسئلة للامتحان
            $questionData = [];
            foreach ($request->questions as $index => $questionId) {
                $questionData[$questionId] = ['order' => $index + 1];
            }
            $exam->questions()->attach($questionData);

            // حساب مجموع النقاط
            $totalPoints = $exam->questions()->sum('points');
            $exam->update(['total_points' => $totalPoints]);

            return redirect()->route('admin.exams.index')->with('success', 'تم إنشاء الامتحان بنجاح');
        } catch (\Exception $e) {
            Log::error('Error creating exam: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء الامتحان']);
        }
    }

    public function adminEdit($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);
        $questions = Question::orderBy('category')->orderBy('difficulty')->get();
        return view('exams.admin.edit', compact('exam', 'questions'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:1|max:100',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'questions' => 'required|array|min:1',
            'questions.*' => 'exists:questions,id',
        ]);

        try {
            $exam->update([
                'title' => $request->title,
                'description' => $request->description,
                'duration_minutes' => $request->duration_minutes,
                'passing_score' => $request->passing_score,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);

            // تحديث الأسئلة
            $exam->questions()->detach();
            $questionData = [];
            foreach ($request->questions as $index => $questionId) {
                $questionData[$questionId] = ['order' => $index + 1];
            }
            $exam->questions()->attach($questionData);

            // تحديث مجموع النقاط
            $totalPoints = $exam->questions()->sum('points');
            $exam->update(['total_points' => $totalPoints]);

            return redirect()->route('admin.exams.index')->with('success', 'تم تحديث الامتحان بنجاح');
        } catch (\Exception $e) {
            Log::error('Error updating exam: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث الامتحان']);
        }
    }

    public function adminDestroy($id)
    {
        try {
            $exam = Exam::findOrFail($id);
            $exam->delete();
            return redirect()->route('admin.exams.index')->with('success', 'تم حذف الامتحان بنجاح');
        } catch (\Exception $e) {
            Log::error('Error deleting exam: ' . $e->getMessage());
            return redirect()->route('admin.exams.index')->with('error', 'حدث خطأ أثناء حذف الامتحان');
        }
    }
}
