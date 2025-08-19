<?php

if (!function_exists('has_permission')) {
    /**
     * التحقق من وجود صلاحية معينة
     */
    function has_permission($permission)
    {
        if (auth()->check()) {
            return auth()->user()->hasPermission($permission);
        }
        return false;
    }
}

if (!function_exists('can')) {
    /**
     * التحقق من وجود صلاحية في وحدة معينة
     */
    function can($action, $module = null)
    {
        if (auth()->check()) {
            return auth()->user()->can($action, $module);
        }
        return false;
    }
}

if (!function_exists('has_role')) {
    /**
     * التحقق من وجود دور معين
     */
    function has_role($role)
    {
        if (auth()->check()) {
            return auth()->user()->hasRole($role);
        }
        return false;
    }
}

if (!function_exists('is_admin')) {
    /**
     * التحقق من أن المستخدم مدير
     */
    function is_admin()
    {
        if (auth()->check()) {
            return auth()->user()->isAdmin();
        }
        return false;
    }
}

if (!function_exists('is_teacher')) {
    /**
     * التحقق من أن المستخدم معلم
     */
    function is_teacher()
    {
        if (auth()->check()) {
            return auth()->user()->isTeacher();
        }
        return false;
    }
}

if (!function_exists('is_student')) {
    /**
     * التحقق من أن المستخدم طالب
     */
    function is_student()
    {
        if (auth()->check()) {
            return auth()->user()->isStudent();
        }
        return false;
    }
}

if (!function_exists('is_staff')) {
    /**
     * التحقق من أن المستخدم موظف
     */
    function is_staff()
    {
        if (auth()->check()) {
            return auth()->user()->isStaff();
        }
        return false;
    }
}

if (!function_exists('user_permissions')) {
    /**
     * الحصول على جميع صلاحيات المستخدم
     */
    function user_permissions()
    {
        if (auth()->check()) {
            return auth()->user()->getAllPermissions();
        }
        return collect();
    }
}

if (!function_exists('user_roles')) {
    /**
     * الحصول على جميع أدوار المستخدم
     */
    function user_roles()
    {
        if (auth()->check()) {
            return auth()->user()->roles;
        }
        return collect();
    }
}
