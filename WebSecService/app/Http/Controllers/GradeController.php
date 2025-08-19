<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $query = Grade::with(['student']);

        // البحث حسب اسم الطالب أو كود المادة
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($studentQuery) use ($search) {
                    $studentQuery->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('course_code', 'like', '%' . $search . '%')
                ->orWhere('course_name', 'like', '%' . $search . '%');
            });
        }

        // تصفية حسب الفصل الدراسي
        if ($request->filled('term_filter')) {
            $query->where('term', $request->term_filter);
        }

        // تصفية حسب الطالب
        if ($request->filled('student_filter')) {
            $query->where('student_id', $request->student_filter);
        }

        $grades = $query->orderBy('created_at', 'desc')->paginate(10);
        $grades->appends($request->query());

        // الحصول على قائمة الطلاب للتصفية
        $students = Student::orderBy('name')->get();
        
        // الحصول على قائمة الفصول الدراسية
        $terms = Grade::select('term')->distinct()->pluck('term')->sort()->values();
        
        return view('grades.index', compact('grades', 'students', 'terms'));
    }

    public function create()
    {
        $students = Student::orderBy('name')->get();
        return view('grades.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50',
            'credit_hours' => 'required|integer|min:1|max:6',
            'term' => 'required|string|max:100',
            'grade' => 'required|numeric|min:0|max:4',
            'letter_grade' => 'required|in:A,A-,B+,B,B-,C+,C,C-,D+,D,F',
            'notes' => 'nullable|string|max:1000',
        ], [
            'student_id.required' => 'الطالب مطلوب',
            'student_id.exists' => 'الطالب غير موجود',
            'course_name.required' => 'اسم المادة مطلوب',
            'course_code.required' => 'كود المادة مطلوب',
            'course_code.max' => 'كود المادة يجب أن يكون أقل من 50 حرف',
            'credit_hours.required' => 'الساعات المعتمدة مطلوبة',
            'credit_hours.integer' => 'الساعات المعتمدة يجب أن تكون رقم صحيح',
            'credit_hours.min' => 'الساعات المعتمدة يجب أن تكون 1 على الأقل',
            'credit_hours.max' => 'الساعات المعتمدة يجب أن تكون 6 على الأكثر',
            'term.required' => 'الفصل الدراسي مطلوب',
            'grade.required' => 'الدرجة مطلوبة',
            'grade.numeric' => 'الدرجة يجب أن تكون رقم',
            'grade.min' => 'الدرجة يجب أن تكون 0 على الأقل',
            'grade.max' => 'الدرجة يجب أن تكون 4 على الأكثر',
            'letter_grade.required' => 'الدرجة الحرفية مطلوبة',
            'letter_grade.in' => 'الدرجة الحرفية غير صحيحة',
            'notes.max' => 'الملاحظات يجب أن تكون أقل من 1000 حرف',
        ]);

        try {
            // حساب النقاط
            $validated['points'] = $validated['grade'] * $validated['credit_hours'];
            $validated['gpa'] = $validated['grade'];

            Grade::create($validated);
            return redirect('/grades')->with('success', 'تم إضافة الدرجة بنجاح');
        } catch (\Exception $e) {
            Log::error('Error creating grade: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إضافة الدرجة')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $grade = Grade::findOrFail($id);
            $students = Student::orderBy('name')->get();
            return view('grades.edit', compact('grade', 'students'));
        } catch (\Exception $e) {
            return redirect('/grades')->with('error', 'الدرجة غير موجودة');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $grade = Grade::findOrFail($id);

            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'course_name' => 'required|string|max:255',
                'course_code' => 'required|string|max:50',
                'credit_hours' => 'required|integer|min:1|max:6',
                'term' => 'required|string|max:100',
                'grade' => 'required|numeric|min:0|max:4',
                'letter_grade' => 'required|in:A,A-,B+,B,B-,C+,C,C-,D+,D,F',
                'notes' => 'nullable|string|max:1000',
            ], [
                'student_id.required' => 'الطالب مطلوب',
                'student_id.exists' => 'الطالب غير موجود',
                'course_name.required' => 'اسم المادة مطلوب',
                'course_code.required' => 'كود المادة مطلوب',
                'course_code.max' => 'كود المادة يجب أن يكون أقل من 50 حرف',
                'credit_hours.required' => 'الساعات المعتمدة مطلوبة',
                'credit_hours.integer' => 'الساعات المعتمدة يجب أن تكون رقم صحيح',
                'credit_hours.min' => 'الساعات المعتمدة يجب أن تكون 1 على الأقل',
                'credit_hours.max' => 'الساعات المعتمدة يجب أن تكون 6 على الأكثر',
                'term.required' => 'الفصل الدراسي مطلوب',
                'grade.required' => 'الدرجة مطلوبة',
                'grade.numeric' => 'الدرجة يجب أن تكون رقم',
                'grade.min' => 'الدرجة يجب أن تكون 0 على الأقل',
                'grade.max' => 'الدرجة يجب أن تكون 4 على الأكثر',
                'letter_grade.required' => 'الدرجة الحرفية مطلوبة',
                'letter_grade.in' => 'الدرجة الحرفية غير صحيحة',
                'notes.max' => 'الملاحظات يجب أن تكون أقل من 1000 حرف',
            ]);

            // حساب النقاط
            $validated['points'] = $validated['grade'] * $validated['credit_hours'];
            $validated['gpa'] = $validated['grade'];

            $grade->update($validated);
            return redirect('/grades')->with('success', 'تم تحديث الدرجة بنجاح');
        } catch (\Exception $e) {
            Log::error('Error updating grade: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحديث الدرجة')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $grade = Grade::findOrFail($id);
            $grade->delete();
            return redirect('/grades')->with('success', 'تم حذف الدرجة بنجاح');
        } catch (\Exception $e) {
            Log::error('Error deleting grade: ' . $e->getMessage());
            return redirect('/grades')->with('error', 'حدث خطأ أثناء حذف الدرجة');
        }
    }

    // عرض درجات طالب معين
    public function studentGrades($studentId)
    {
        $student = Student::findOrFail($studentId);
        $grades = $student->grades()->orderBy('term')->orderBy('course_name')->get();
        
        // تجميع الدرجات حسب الفصل الدراسي
        $gradesByTerm = $grades->groupBy('term');
        
        return view('grades.student', compact('student', 'gradesByTerm'));
    }
}
