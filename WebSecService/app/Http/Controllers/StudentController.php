<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'age' => 'required|numeric|min:1',
        ]);

        Student::create($validated);
        return redirect('/students');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:students,email,' . $id,
            'age' => 'required|numeric|min:1',
        ]);

        $student->update($validated);
        return redirect('/students')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        Student::destroy($id);
        return redirect('/students')->with('success', 'Student deleted successfully.');
    }
}
