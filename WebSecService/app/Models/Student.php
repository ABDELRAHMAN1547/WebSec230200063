<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'age', 
        'phone', 
        'address', 
        'gender', 
        'student_id', 
        'status', 
        'enrollment_date'
    ];

    protected $casts = [
        'age' => 'integer',
        'enrollment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessor للحصول على الاسم الكامل
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    // Accessor للحصول على الفئة العمرية
    public function getAgeGroupAttribute()
    {
        if ($this->age < 18) {
            return 'قاصر';
        } elseif ($this->age < 25) {
            return 'شاب';
        } elseif ($this->age < 35) {
            return 'بالغ';
        } else {
            return 'كبير السن';
        }
    }

    // Accessor للحصول على حالة الطالب بالعربية
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'graduated' => 'متخرج',
            default => 'غير محدد'
        };
    }

    // Accessor للحصول على الجنس بالعربية
    public function getGenderTextAttribute()
    {
        return match($this->gender) {
            'male' => 'ذكر',
            'female' => 'أنثى',
            'other' => 'آخر',
            default => 'غير محدد'
        };
    }

    // Scope للبحث عن الطلاب حسب العمر
    public function scopeByAge($query, $minAge, $maxAge = null)
    {
        $query->where('age', '>=', $minAge);
        if ($maxAge) {
            $query->where('age', '<=', $maxAge);
        }
        return $query;
    }

    // Scope للبحث عن الطلاب حسب الاسم
    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    // Scope للبحث عن الطلاب حسب البريد الإلكتروني
    public function scopeSearchByEmail($query, $email)
    {
        return $query->where('email', 'like', '%' . $email . '%');
    }

    // Scope للطلاب النشطين فقط
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope للطلاب المتخرجين
    public function scopeGraduated($query)
    {
        return $query->where('status', 'graduated');
    }

    // العلاقة مع الدرجات
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // دالة لحساب GPA التراكمي
    public function calculateCGPA()
    {
        $grades = $this->grades;
        if ($grades->isEmpty()) return 0.00;
        
        $totalPoints = $grades->sum('points');
        $totalHours = $grades->sum('credit_hours');
        
        return $totalHours > 0 ? round($totalPoints / $totalHours, 2) : 0.00;
    }

    // دالة لحساب الساعات المعتمدة التراكمية
    public function calculateCCH()
    {
        return $this->grades->sum('credit_hours');
    }

    // دالة لحساب GPA لفصل معين
    public function calculateTermGPA($term)
    {
        $grades = $this->grades()->where('term', $term)->get();
        if ($grades->isEmpty()) return 0.00;
        
        $totalPoints = $grades->sum('points');
        $totalHours = $grades->sum('credit_hours');
        
        return $totalHours > 0 ? round($totalPoints / $totalHours, 2) : 0.00;
    }

    // دالة لحساب الساعات المعتمدة لفصل معين
    public function calculateTermCH($term)
    {
        return $this->grades()->where('term', $term)->sum('credit_hours');
    }
}
