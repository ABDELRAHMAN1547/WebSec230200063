<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // العلاقة مع المستخدمين
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // العلاقة مع الصلاحيات
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    // دالة للتحقق من وجود صلاحية معينة
    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    // دالة للتحقق من وجود صلاحية في وحدة معينة
    public function hasPermissionInModule($module, $action)
    {
        return $this->permissions()
            ->where('module', $module)
            ->where('action', $action)
            ->exists();
    }

    // Scope للأدوار النشطة فقط
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor للحصول على الاسم المعروض
    public function getDisplayNameAttribute($value)
    {
        return $value ?: ucfirst(str_replace('_', ' ', $this->name));
    }
}
