<?php

namespace App;

trait HasPermissions
{
    /**
     * التحقق من وجود صلاحية معينة
     */
    public function hasPermission($permissionName)
    {
        if (method_exists($this, 'roles')) {
            return $this->roles()
                ->whereHas('permissions', function($query) use ($permissionName) {
                    $query->where('name', $permissionName);
                })
                ->exists();
        }
        
        return false;
    }

    /**
     * التحقق من وجود صلاحية في وحدة معينة
     */
    public function can($action, $module = null)
    {
        if ($module) {
            return $this->roles()
                ->whereHas('permissions', function($query) use ($action, $module) {
                    $query->where('action', $action)
                          ->where('module', $module);
                })
                ->exists();
        }

        return $this->hasPermission($action);
    }

    /**
     * التحقق من وجود أي من الصلاحيات المحددة
     */
    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * التحقق من وجود جميع الصلاحيات المحددة
     */
    public function hasAllPermissions($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * الحصول على جميع الصلاحيات
     */
    public function getAllPermissions()
    {
        if (method_exists($this, 'roles')) {
            return $this->roles()
                ->with('permissions')
                ->get()
                ->flatMap(function($role) {
                    return $role->permissions;
                })
                ->unique('id');
        }
        
        return collect();
    }

    /**
     * التحقق من إمكانية إدارة المستخدمين
     */
    public function canManageUsers()
    {
        return $this->hasPermission('users.manage');
    }

    /**
     * التحقق من إمكانية إدارة الطلاب
     */
    public function canManageStudents()
    {
        return $this->hasPermission('students.manage');
    }

    /**
     * التحقق من إمكانية إدارة الامتحانات
     */
    public function canManageExams()
    {
        return $this->hasPermission('exams.manage');
    }

    /**
     * التحقق من إمكانية إدارة الدرجات
     */
    public function canManageGrades()
    {
        return $this->hasPermission('grades.manage');
    }

    /**
     * التحقق من إمكانية إدارة الأسئلة
     */
    public function canManageQuestions()
    {
        return $this->hasPermission('questions.manage');
    }

    /**
     * التحقق من إمكانية إدارة الأدوار
     */
    public function canManageRoles()
    {
        return $this->hasPermission('roles.manage');
    }

    /**
     * التحقق من إمكانية إدارة الصلاحيات
     */
    public function canManagePermissions()
    {
        return $this->hasPermission('permissions.manage');
    }
}
