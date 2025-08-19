<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الصلاحيات الأساسية
        $permissions = [
            // صلاحيات المستخدمين
            ['name' => 'users.view', 'display_name' => 'عرض المستخدمين', 'module' => 'users', 'action' => 'view'],
            ['name' => 'users.create', 'display_name' => 'إنشاء مستخدمين', 'module' => 'users', 'action' => 'create'],
            ['name' => 'users.edit', 'display_name' => 'تعديل المستخدمين', 'module' => 'users', 'action' => 'edit'],
            ['name' => 'users.delete', 'display_name' => 'حذف المستخدمين', 'module' => 'users', 'action' => 'delete'],
            ['name' => 'users.manage', 'display_name' => 'إدارة المستخدمين', 'module' => 'users', 'action' => 'manage'],

            // صلاحيات الطلاب
            ['name' => 'students.view', 'display_name' => 'عرض الطلاب', 'module' => 'students', 'action' => 'view'],
            ['name' => 'students.create', 'display_name' => 'إنشاء طلاب', 'module' => 'students', 'action' => 'create'],
            ['name' => 'students.edit', 'display_name' => 'تعديل الطلاب', 'module' => 'students', 'action' => 'edit'],
            ['name' => 'students.delete', 'display_name' => 'حذف الطلاب', 'module' => 'students', 'action' => 'delete'],
            ['name' => 'students.manage', 'display_name' => 'إدارة الطلاب', 'module' => 'students', 'action' => 'manage'],

            // صلاحيات الدرجات
            ['name' => 'grades.view', 'display_name' => 'عرض الدرجات', 'module' => 'grades', 'action' => 'view'],
            ['name' => 'grades.create', 'display_name' => 'إنشاء درجات', 'module' => 'grades', 'action' => 'create'],
            ['name' => 'grades.edit', 'display_name' => 'تعديل الدرجات', 'module' => 'grades', 'action' => 'edit'],
            ['name' => 'grades.delete', 'display_name' => 'حذف الدرجات', 'module' => 'grades', 'action' => 'delete'],
            ['name' => 'grades.manage', 'display_name' => 'إدارة الدرجات', 'module' => 'grades', 'action' => 'manage'],

            // صلاحيات الامتحانات
            ['name' => 'exams.view', 'display_name' => 'عرض الامتحانات', 'module' => 'exams', 'action' => 'view'],
            ['name' => 'exams.create', 'display_name' => 'إنشاء امتحانات', 'module' => 'exams', 'action' => 'create'],
            ['name' => 'exams.edit', 'display_name' => 'تعديل الامتحانات', 'module' => 'exams', 'action' => 'edit'],
            ['name' => 'exams.delete', 'display_name' => 'حذف الامتحانات', 'module' => 'exams', 'action' => 'delete'],
            ['name' => 'exams.manage', 'display_name' => 'إدارة الامتحانات', 'module' => 'exams', 'action' => 'manage'],

            // صلاحيات الأسئلة
            ['name' => 'questions.view', 'display_name' => 'عرض الأسئلة', 'module' => 'questions', 'action' => 'view'],
            ['name' => 'questions.create', 'display_name' => 'إنشاء أسئلة', 'module' => 'questions', 'action' => 'create'],
            ['name' => 'questions.edit', 'display_name' => 'تعديل الأسئلة', 'module' => 'questions', 'action' => 'edit'],
            ['name' => 'questions.delete', 'display_name' => 'حذف الأسئلة', 'module' => 'questions', 'action' => 'delete'],
            ['name' => 'questions.manage', 'display_name' => 'إدارة الأسئلة', 'module' => 'questions', 'action' => 'manage'],

            // صلاحيات الأدوار
            ['name' => 'roles.view', 'display_name' => 'عرض الأدوار', 'module' => 'roles', 'action' => 'view'],
            ['name' => 'roles.create', 'display_name' => 'إنشاء أدوار', 'module' => 'roles', 'action' => 'create'],
            ['name' => 'roles.edit', 'display_name' => 'تعديل الأدوار', 'module' => 'roles', 'action' => 'edit'],
            ['name' => 'roles.delete', 'display_name' => 'حذف الأدوار', 'module' => 'roles', 'action' => 'delete'],
            ['name' => 'roles.manage', 'display_name' => 'إدارة الأدوار', 'module' => 'roles', 'action' => 'manage'],

            // صلاحيات الصلاحيات
            ['name' => 'permissions.view', 'display_name' => 'عرض الصلاحيات', 'module' => 'permissions', 'action' => 'view'],
            ['name' => 'permissions.create', 'display_name' => 'إنشاء صلاحيات', 'module' => 'permissions', 'action' => 'create'],
            ['name' => 'permissions.edit', 'display_name' => 'تعديل الصلاحيات', 'module' => 'permissions', 'action' => 'edit'],
            ['name' => 'permissions.delete', 'display_name' => 'حذف الصلاحيات', 'module' => 'permissions', 'action' => 'delete'],
            ['name' => 'permissions.manage', 'display_name' => 'إدارة الصلاحيات', 'module' => 'permissions', 'action' => 'manage'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData + ['is_active' => true]
            );
        }

        // إنشاء الأدوار الأساسية
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'مدير عام',
                'description' => 'مدير النظام مع جميع الصلاحيات'
            ],
            [
                'name' => 'admin',
                'display_name' => 'مدير',
                'description' => 'مدير مع صلاحيات محدودة'
            ],
            [
                'name' => 'teacher',
                'display_name' => 'معلم',
                'description' => 'معلم مع صلاحيات إدارة الطلاب والامتحانات والدرجات'
            ],
            [
                'name' => 'student',
                'display_name' => 'طالب',
                'description' => 'طالب مع صلاحيات محدودة للعرض فقط'
            ],
            [
                'name' => 'staff',
                'display_name' => 'موظف',
                'description' => 'موظف إداري مع صلاحيات محدودة'
            ]
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData + ['is_active' => true]
            );
        }

        // ربط الصلاحيات بالأدوار
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $studentRole = Role::where('name', 'student')->first();
        $staffRole = Role::where('name', 'staff')->first();

        // المدير العام - جميع الصلاحيات
        if ($superAdminRole) {
            $superAdminRole->permissions()->sync(Permission::all()->pluck('id'));
        }

        // المدير - معظم الصلاحيات
        if ($adminRole) {
            $adminPermissions = Permission::whereNotIn('name', [
                'roles.manage', 'permissions.manage'
            ])->get();
            $adminRole->permissions()->sync($adminPermissions->pluck('id'));
        }

        // المعلم - صلاحيات إدارة الطلاب والامتحانات والدرجات
        if ($teacherRole) {
            $teacherPermissions = Permission::whereIn('module', ['students', 'exams', 'grades', 'questions'])
                ->get();
            $teacherRole->permissions()->sync($teacherPermissions->pluck('id'));
        }

        // الطالب - صلاحيات العرض فقط
        if ($studentRole) {
            $studentPermissions = Permission::where('action', 'view')
                ->whereIn('module', ['exams', 'grades'])
                ->get();
            $studentRole->permissions()->sync($studentPermissions->pluck('id'));
        }

        // الموظف - صلاحيات محدودة
        if ($staffRole) {
            $staffPermissions = Permission::whereIn('name', [
                'students.view', 'grades.view', 'exams.view'
            ])->get();
            $staffRole->permissions()->sync($staffPermissions->pluck('id'));
        }

        // ربط المستخدمين الموجودين بالأدوار المناسبة
        $users = User::all();
        foreach ($users as $user) {
            if ($user->admin) {
                $user->roles()->sync([$superAdminRole->id]);
            } elseif ($user->role === 'teacher') {
                $user->roles()->sync([$teacherRole->id]);
            } elseif ($user->role === 'student') {
                $user->roles()->sync([$studentRole->id]);
            } elseif ($user->role === 'staff') {
                $user->roles()->sync([$staffRole->id]);
            } else {
                $user->roles()->sync([$adminRole->id]);
            }
        }

        $this->command->info('تم إنشاء الأدوار والصلاحيات بنجاح!');
    }
}
