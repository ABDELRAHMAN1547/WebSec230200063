<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'action',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // العلاقة مع الأدوار
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // دالة للحصول على الاسم الكامل للصلاحية
    public function getFullNameAttribute()
    {
        return "{$this->module}.{$this->action}";
    }

    // دالة للحصول على الاسم المعروض
    public function getDisplayNameAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $actionNames = [
            'view' => 'عرض',
            'create' => 'إنشاء',
            'edit' => 'تعديل',
            'delete' => 'حذف',
            'manage' => 'إدارة'
        ];

        $moduleNames = [
            'users' => 'المستخدمين',
            'grades' => 'الدرجات',
            'exams' => 'الامتحانات',
            'questions' => 'الأسئلة',
            'students' => 'الطلاب',
            'roles' => 'الأدوار',
            'permissions' => 'الصلاحيات'
        ];

        $action = $actionNames[$this->action] ?? $this->action;
        $module = $moduleNames[$this->module] ?? $this->module;

        return "{$action} {$module}";
    }

    // Scope للصلاحيات النشطة فقط
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope للصلاحيات في وحدة معينة
    public function scopeInModule($query, $module)
    {
        return $query->where('module', $module);
    }

    // Scope للصلاحيات من نوع معين
    public function scopeOfAction($query, $action)
    {
        return $query->where('action', $action);
    }
}
