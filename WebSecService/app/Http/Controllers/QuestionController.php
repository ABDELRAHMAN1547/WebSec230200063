<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::query();

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('question_text', 'like', '%' . $search . '%');
        }

        // تصفية حسب الفئة
        if ($request->filled('category_filter')) {
            $query->where('category', $request->category_filter);
        }

        // تصفية حسب الصعوبة
        if ($request->filled('difficulty_filter')) {
            $query->where('difficulty', $request->difficulty_filter);
        }

        $questions = $query->orderBy('created_at', 'desc')->paginate(10);
        $questions->appends($request->query());
        
        return view('questions.index', compact('questions'));
    }

    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_answer' => 'required|in:A,B,C,D',
            'category' => 'required|in:general,programming,database,networking,security',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|integer|min:1|max:10',
        ]);

        try {
            Question::create($request->all());
            return redirect()->route('questions.index')->with('success', 'تم إنشاء السؤال بنجاح');
        } catch (\Exception $e) {
            Log::error('Error creating question: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء السؤال']);
        }
    }

    public function edit($id)
    {
        try {
            $question = Question::findOrFail($id);
            return view('questions.edit', compact('question'));
        } catch (\Exception $e) {
            return redirect()->route('questions.index')->with('error', 'السؤال غير موجود');
        }
    }

    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_answer' => 'required|in:A,B,C,D',
            'category' => 'required|in:general,programming,database,networking,security',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|integer|min:1|max:10',
        ]);

        try {
            $question->update($request->all());
            return redirect()->route('questions.index')->with('success', 'تم تحديث السؤال بنجاح');
        } catch (\Exception $e) {
            Log::error('Error updating question: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث السؤال']);
        }
    }

    public function destroy($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->delete();
            return redirect()->route('questions.index')->with('success', 'تم حذف السؤال بنجاح');
        } catch (\Exception $e) {
            Log::error('Error deleting question: ' . $e->getMessage());
            return redirect()->route('questions.index')->with('error', 'حدث خطأ أثناء حذف السؤال');
        }
    }
}
