<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // تصفية حسب الحالة
        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        // تصفية حسب العمر
        if ($request->filled('age_filter')) {
            $ageRange = $request->age_filter;
            switch ($ageRange) {
                case '16-20':
                    $query->where('age', '>=', 16)->where('age', '<=', 20);
                    break;
                case '21-25':
                    $query->where('age', '>=', 21)->where('age', '<=', 25);
                    break;
                case '26-30':
                    $query->where('age', '>=', 26)->where('age', '<=', 30);
                    break;
                case '30+':
                    $query->where('age', '>=', 30);
                    break;
            }
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(5);
        
        // إضافة البيانات للتصفية
        $students->appends($request->query());
        
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|email|unique:students,email|max:255',
            'age' => 'required|integer|min:16|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:male,female,other',
            'student_id' => 'nullable|string|max:50|unique:students,student_id',
            'status' => 'nullable|in:active,inactive,graduated',
            'enrollment_date' => 'nullable|date',
        ], [
            'name.required' => 'اسم الطالب مطلوب',
            'name.min' => 'اسم الطالب يجب أن يكون على الأقل حرفين',
            'name.max' => 'اسم الطالب يجب أن يكون أقل من 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'email.max' => 'البريد الإلكتروني يجب أن يكون أقل من 255 حرف',
            'age.required' => 'العمر مطلوب',
            'age.integer' => 'العمر يجب أن يكون رقم صحيح',
            'age.min' => 'العمر يجب أن يكون 16 سنة على الأقل',
            'age.max' => 'العمر يجب أن يكون أقل من 100 سنة',
            'phone.max' => 'رقم الهاتف يجب أن يكون أقل من 20 حرف',
            'address.max' => 'العنوان يجب أن يكون أقل من 500 حرف',
            'gender.in' => 'الجنس يجب أن يكون ذكر أو أنثى أو آخر',
            'student_id.unique' => 'رقم الطالب مستخدم بالفعل',
            'student_id.max' => 'رقم الطالب يجب أن يكون أقل من 50 حرف',
            'status.in' => 'الحالة غير صحيحة',
            'enrollment_date.date' => 'تاريخ التسجيل غير صحيح',
        ]);

        try {
            Student::create($validated);
            return redirect('/students')->with('success', 'تم إضافة الطالب بنجاح');
        } catch (\Exception $e) {
            Log::error('Error creating student: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إضافة الطالب')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $student = Student::findOrFail($id);
            return view('students.edit', compact('student'));
        } catch (\Exception $e) {
            return redirect('/students')->with('error', 'الطالب غير موجود');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $student = Student::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255|min:2',
                'email' => 'required|email|unique:students,email,' . $id . '|max:255',
                'age' => 'required|integer|min:16|max:100',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'gender' => 'nullable|in:male,female,other',
                'student_id' => 'nullable|string|max:50|unique:students,student_id,' . $id,
                'status' => 'nullable|in:active,inactive,graduated',
                'enrollment_date' => 'nullable|date',
            ], [
                'name.required' => 'اسم الطالب مطلوب',
                'name.min' => 'اسم الطالب يجب أن يكون على الأقل حرفين',
                'name.max' => 'اسم الطالب يجب أن يكون أقل من 255 حرف',
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'البريد الإلكتروني غير صحيح',
                'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
                'email.max' => 'البريد الإلكتروني يجب أن يكون أقل من 255 حرف',
                'age.required' => 'العمر مطلوب',
                'age.integer' => 'العمر يجب أن يكون رقم صحيح',
                'age.min' => 'العمر يجب أن يكون 16 سنة على الأقل',
                'age.max' => 'العمر يجب أن يكون أقل من 100 سنة',
                'phone.max' => 'رقم الهاتف يجب أن يكون أقل من 20 حرف',
                'address.max' => 'العنوان يجب أن يكون أقل من 500 حرف',
                'gender.in' => 'الجنس يجب أن يكون ذكر أو أنثى أو آخر',
                'student_id.unique' => 'رقم الطالب مستخدم بالفعل',
                'student_id.max' => 'رقم الطالب يجب أن يكون أقل من 50 حرف',
                'status.in' => 'الحالة غير صحيحة',
                'enrollment_date.date' => 'تاريخ التسجيل غير صحيح',
            ]);

            $student->update($validated);
            return redirect('/students')->with('success', 'تم تحديث بيانات الطالب بنجاح');
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحديث بيانات الطالب')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();
            return redirect('/students')->with('success', 'تم حذف الطالب بنجاح');
        } catch (\Exception $e) {
            Log::error('Error deleting student: ' . $e->getMessage());
            return redirect('/students')->with('error', 'حدث خطأ أثناء حذف الطالب');
        }
    }
}
