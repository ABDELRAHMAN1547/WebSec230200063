<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // إنشاء الصلاحيات الأساسية
        $permissions = $this->createPermissions();
        
        // إنشاء الأدوار
        $roles = $this->createRoles();
        
        // ربط الصلاحيات بالأدوار
        $this->assignPermissionsToRoles($roles, $permissions);
        
        // إنشاء السوبر أدمن الافتراضي
        $this->createSuperAdmin($roles['super_admin']);
    }

    /**
     * إنشاء الصلاحيات الأساسية
     */
    private function createPermissions()
    {
        $permissionsData = [
            // إدارة المستخدمين
            ['name' => 'users.view', 'display_name' => 'عرض المستخدمين', 'module' => 'users', 'action' => 'view'],
            ['name' => 'users.create', 'display_name' => 'إنشاء مستخدم جديد', 'module' => 'users', 'action' => 'create'],
            ['name' => 'users.edit', 'display_name' => 'تعديل المستخدمين', 'module' => 'users', 'action' => 'edit'],
            ['name' => 'users.delete', 'display_name' => 'حذف المستخدمين', 'module' => 'users', 'action' => 'delete'],
            ['name' => 'users.manage', 'display_name' => 'إدارة المستخدمين', 'module' => 'users', 'action' => 'manage'],

            // إدارة الطلاب
            ['name' => 'students.view', 'display_name' => 'عرض الطلاب', 'module' => 'students', 'action' => 'view'],
            ['name' => 'students.create', 'display_name' => 'إنشاء طالب جديد', 'module' => 'students', 'action' => 'create'],
            ['name' => 'students.edit', 'display_name' => 'تعديل الطلاب', 'module' => 'students', 'action' => 'edit'],
            ['name' => 'students.delete', 'display_name' => 'حذف الطلاب', 'module' => 'students', 'action' => 'delete'],
            ['name' => 'students.manage', 'display_name' => 'إدارة الطلاب', 'module' => 'students', 'action' => 'manage'],

            // إدارة الامتحانات
            ['name' => 'exams.view', 'display_name' => 'عرض الامتحانات', 'module' => 'exams', 'action' => 'view'],
            ['name' => 'exams.create', 'display_name' => 'إنشاء امتحان جديد', 'module' => 'exams', 'action' => 'create'],
            ['name' => 'exams.edit', 'display_name' => 'تعديل الامتحانات', 'module' => 'exams', 'action' => 'edit'],
            ['name' => 'exams.delete', 'display_name' => 'حذف الامتحانات', 'module' => 'exams', 'action' => 'delete'],
            ['name' => 'exams.manage', 'display_name' => 'إدارة الامتحانات', 'module' => 'exams', 'action' => 'manage'],

            // إدارة الأسئلة
            ['name' => 'questions.view', 'display_name' => 'عرض الأسئلة', 'module' => 'questions', 'action' => 'view'],
            ['name' => 'questions.create', 'display_name' => 'إنشاء سؤال جديد', 'module' => 'questions', 'action' => 'create'],
            ['name' => 'questions.edit', 'display_name' => 'تعديل الأسئلة', 'module' => 'questions', 'action' => 'edit'],
            ['name' => 'questions.delete', 'display_name' => 'حذف الأسئلة', 'module' => 'questions', 'action' => 'delete'],
            ['name' => 'questions.manage', 'display_name' => 'إدارة الأسئلة', 'module' => 'questions', 'action' => 'manage'],

            // إدارة الدرجات
            ['name' => 'grades.view', 'display_name' => 'عرض الدرجات', 'module' => 'grades', 'action' => 'view'],
            ['name' => 'grades.create', 'display_name' => 'إنشاء درجة جديدة', 'module' => 'grades', 'action' => 'create'],
            ['name' => 'grades.edit', 'display_name' => 'تعديل الدرجات', 'module' => 'grades', 'action' => 'edit'],
            ['name' => 'grades.delete', 'display_name' => 'حذف الدرجات', 'module' => 'grades', 'action' => 'delete'],
            ['name' => 'grades.manage', 'display_name' => 'إدارة الدرجات', 'module' => 'grades', 'action' => 'manage'],

            // إدارة الأدوار
            ['name' => 'roles.view', 'display_name' => 'عرض الأدوار', 'module' => 'roles', 'action' => 'view'],
            ['name' => 'roles.create', 'display_name' => 'إنشاء دور جديد', 'module' => 'roles', 'action' => 'create'],
            ['name' => 'roles.edit', 'display_name' => 'تعديل الأدوار', 'module' => 'roles', 'action' => 'edit'],
            ['name' => 'roles.delete', 'display_name' => 'حذف الأدوار', 'module' => 'roles', 'action' => 'delete'],
            ['name' => 'roles.manage', 'display_name' => 'إدارة الأدوار', 'module' => 'roles', 'action' => 'manage'],

            // إدارة الصلاحيات
            ['name' => 'permissions.view', 'display_name' => 'عرض الصلاحيات', 'module' => 'permissions', 'action' => 'view'],
            ['name' => 'permissions.create', 'display_name' => 'إنشاء صلاحية جديدة', 'module' => 'permissions', 'action' => 'create'],
            ['name' => 'permissions.edit', 'display_name' => 'تعديل الصلاحيات', 'module' => 'permissions', 'action' => 'edit'],
            ['name' => 'permissions.delete', 'display_name' => 'حذف الصلاحيات', 'module' => 'permissions', 'action' => 'delete'],
            ['name' => 'permissions.manage', 'display_name' => 'إدارة الصلاحيات', 'module' => 'permissions', 'action' => 'manage'],

            // إدارة النظام
            ['name' => 'system.view', 'display_name' => 'عرض إعدادات النظام', 'module' => 'system', 'action' => 'view'],
            ['name' => 'system.edit', 'display_name' => 'تعديل إعدادات النظام', 'module' => 'system', 'action' => 'edit'],
            ['name' => 'system.logs', 'display_name' => 'عرض سجلات النظام', 'module' => 'system', 'action' => 'logs'],
            ['name' => 'system.backup', 'display_name' => 'النسخ الاحتياطي', 'module' => 'system', 'action' => 'backup'],
            ['name' => 'system.manage', 'display_name' => 'إدارة النظام', 'module' => 'system', 'action' => 'manage'],

            // لوحة التحكم
            ['name' => 'dashboard.view', 'display_name' => 'عرض لوحة التحكم', 'module' => 'dashboard', 'action' => 'view'],
            ['name' => 'dashboard.analytics', 'display_name' => 'عرض التحليلات', 'module' => 'dashboard', 'action' => 'analytics'],
        ];

        $permissions = [];
        foreach ($permissionsData as $permData) {
            $permissions[$permData['name']] = Permission::firstOrCreate(
                ['name' => $permData['name']],
                [
                    'display_name' => $permData['display_name'],
                    'module' => $permData['module'],
                    'action' => $permData['action'],
                    'is_active' => true,
                ]
            );
        }

        return $permissions;
    }

    /**
     * إنشاء الأدوار الأساسية
     */
    private function createRoles()
    {
        $rolesData = [
            'super_admin' => [
                'name' => 'super_admin',
                'display_name' => 'سوبر أدمن',
                'description' => 'الوصول الكامل إلى جميع أجزاء النظام بدون استثناء. لا يمكن حذفه أو تعطيله.',
                'is_active' => true,
            ],
            'admin' => [
                'name' => 'admin',
                'display_name' => 'أدمن',
                'description' => 'إدارة بعض أو كل المستخدمين وإدارة محتوى الموقع. لا يمكنه تعديل أو حذف السوبر أدمن.',
                'is_active' => true,
            ],
            'user' => [
                'name' => 'user',
                'display_name' => 'مستخدم عادي',
                'description' => 'استخدام وظائف النظام العامة وتعديل البيانات الشخصية. لا يملك أي صلاحيات إدارية.',
                'is_active' => true,
            ],
        ];

        $roles = [];
        foreach ($rolesData as $key => $roleData) {
            $roles[$key] = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        return $roles;
    }

    /**
     * ربط الصلاحيات بالأدوار
     */
    private function assignPermissionsToRoles($roles, $permissions)
    {
        // السوبر أدمن - جميع الصلاحيات
        $roles['super_admin']->permissions()->sync(array_values(array_map(fn($p) => $p->id, $permissions)));

        // الأدمن - صلاحيات محدودة
        $adminPermissions = [
            // إدارة المستخدمين (محدودة)
            'users.view', 'users.create', 'users.edit',
            
            // إدارة الطلاب
            'students.view', 'students.create', 'students.edit', 'students.delete', 'students.manage',
            
            // إدارة الامتحانات
            'exams.view', 'exams.create', 'exams.edit', 'exams.delete', 'exams.manage',
            
            // إدارة الأسئلة
            'questions.view', 'questions.create', 'questions.edit', 'questions.delete', 'questions.manage',
            
            // إدارة الدرجات
            'grades.view', 'grades.create', 'grades.edit', 'grades.delete', 'grades.manage',
            
            // عرض الأدوار (بدون إنشاء أو حذف)
            'roles.view',
            
            // لوحة التحكم
            'dashboard.view', 'dashboard.analytics',
        ];

        $adminPermissionIds = [];
        foreach ($adminPermissions as $permName) {
            if (isset($permissions[$permName])) {
                $adminPermissionIds[] = $permissions[$permName]->id;
            }
        }
        $roles['admin']->permissions()->sync($adminPermissionIds);

        // المستخدم العادي - صلاحيات أساسية فقط
        $userPermissions = [
            // عرض البيانات الأساسية فقط
            'dashboard.view',
        ];

        $userPermissionIds = [];
        foreach ($userPermissions as $permName) {
            if (isset($permissions[$permName])) {
                $userPermissionIds[] = $permissions[$permName]->id;
            }
        }
        $roles['user']->permissions()->sync($userPermissionIds);
    }

    /**
     * إنشاء السوبر أدمن الافتراضي
     */
    private function createSuperAdmin($superAdminRole)
    {
        // التحقق من وجود سوبر أدمن
        $existingSuperAdmin = User::whereHas('roles', function($query) {
            $query->where('name', 'super_admin');
        })->first();

        if (!$existingSuperAdmin) {
            $superAdmin = User::create([
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email' => 'admin@websec.com',
                'password' => bcrypt('Admin@123'),
                'role' => 'admin',
                'status' => 'active',
                'admin' => true,
                'email_verified_at' => now(),
            ]);

            // ربط السوبر أدمن بدوره
            $superAdmin->roles()->attach($superAdminRole->id);

            $this->command->info('تم إنشاء السوبر أدمن الافتراضي:');
            $this->command->info('البريد الإلكتروني: admin@websec.com');
            $this->command->info('كلمة المرور: Admin@123');
        } else {
            $this->command->info('السوبر أدمن موجود بالفعل.');
        }
    }
}
