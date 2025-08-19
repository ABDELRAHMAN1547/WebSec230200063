<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_name',
        'course_code',
        'credit_hours',
        'term',
        'grade',
        'letter_grade',
        'gpa',
        'points',
        'notes',
    ];

    protected $casts = [
        'grade' => 'decimal:2',
        'gpa' => 'decimal:2',
        'points' => 'decimal:2',
        'credit_hours' => 'integer',
    ];

    // العلاقة مع الطالب
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Accessor للحصول على الدرجة الحرفية بالعربية
    public function getLetterGradeTextAttribute()
    {
        return match($this->letter_grade) {
            'A' => 'ممتاز',
            'A-' => 'ممتاز -',
            'B+' => 'جيد جداً +',
            'B' => 'جيد جداً',
            'B-' => 'جيد جداً -',
            'C+' => 'جيد +',
            'C' => 'جيد',
            'C-' => 'جيد -',
            'D+' => 'مقبول +',
            'D' => 'مقبول',
            'F' => 'راسب',
            default => 'غير محدد'
        };
    }

    // Accessor للحصول على لون الدرجة
    public function getGradeColorAttribute()
    {
        return match($this->letter_grade) {
            'A', 'A-' => 'success',
            'B+', 'B', 'B-' => 'info',
            'C+', 'C', 'C-' => 'warning',
            'D+', 'D' => 'secondary',
            'F' => 'danger',
            default => 'secondary'
        };
    }

    // Scope للبحث حسب الفصل الدراسي
    public function scopeByTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    // Scope للبحث حسب الطالب
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    // Scope للبحث حسب المادة
    public function scopeByCourse($query, $courseCode)
    {
        return $query->where('course_code', $courseCode);
    }

    // دالة لحساب النقاط
    public function calculatePoints()
    {
        $this->points = $this->grade * $this->credit_hours;
        $this->save();
        return $this->points;
    }

    // دالة لحساب GPA
    public function calculateGPA()
    {
        $this->gpa = $this->grade;
        $this->save();
        return $this->gpa;
    }

    // دالة لتحويل الدرجة إلى حرف
    public function gradeToLetter()
    {
        if ($this->grade >= 3.70) return 'A';
        if ($this->grade >= 3.30) return 'A-';
        if ($this->grade >= 3.00) return 'B+';
        if ($this->grade >= 2.70) return 'B';
        if ($this->grade >= 2.30) return 'B-';
        if ($this->grade >= 2.00) return 'C+';
        if ($this->grade >= 1.70) return 'C';
        if ($this->grade >= 1.30) return 'C-';
        if ($this->grade >= 1.00) return 'D+';
        if ($this->grade >= 0.70) return 'D';
        return 'F';
    }

    // دالة لتحويل الحرف إلى درجة
    public function letterToGrade($letter)
    {
        return match($letter) {
            'A' => 4.00,
            'A-' => 3.70,
            'B+' => 3.30,
            'B' => 3.00,
            'B-' => 2.70,
            'C+' => 2.30,
            'C' => 2.00,
            'C-' => 1.70,
            'D+' => 1.30,
            'D' => 1.00,
            'F' => 0.00,
            default => 0.00
        };
    }
}
