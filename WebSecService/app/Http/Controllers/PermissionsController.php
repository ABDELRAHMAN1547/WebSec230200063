<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionsController extends Controller
{
    /**
     * عرض صفحة إدارة الصلاحيات
     */
    public function index()
    {
        // التحقق من صلاحية الوصول
        if (!$this->canAccessPermissions()) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        $user = Auth::user();
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy('module');
        $users = User::with('roles')->get();

        return view('admin.permissions.index', compact('roles', 'permissions', 'users', 'user'));
    }

    /**
     * إنشاء دور جديد
     */
    public function createRole(Request $request)
    {
        if (!$this->canManageRoles()) {
            return response()->json(['error' => 'ليس لديك صلاحية لإنشاء الأدوار'], 403);
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'display_name' => 'required|string',
            'description' => 'nullable|string',
            'permissions' => 'array'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'is_active' => true
            ]);

            if ($request->permissions) {
                $role->permissions()->sync($request->permissions);
            }

            DB::commit();
            return response()->json(['success' => 'تم إنشاء الدور بنجاح', 'role' => $role->load('permissions')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'حدث خطأ أثناء إنشاء الدور'], 500);
        }
    }

    /**
     * تحديث دور موجود
     */
    public function updateRole(Request $request, Role $role)
    {
        if (!$this->canManageRoles()) {
            return response()->json(['error' => 'ليس لديك صلاحية لتعديل الأدوار'], 403);
        }

        // منع تعديل دور السوبر أدمن من قبل الأدمن العادي
        if ($this->isSuperAdminRole($role) && !$this->isSuperAdmin()) {
            return response()->json(['error' => 'لا يمكن تعديل دور السوبر أدمن'], 403);
        }

        $request->validate([
            'display_name' => 'required|string',
            'description' => 'nullable|string',
            'permissions' => 'array'
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            DB::commit();
            return response()->json(['success' => 'تم تحديث الدور بنجاح', 'role' => $role->load('permissions')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'حدث خطأ أثناء تحديث الدور'], 500);
        }
    }

    /**
     * حذف دور
     */
    public function deleteRole(Role $role)
    {
        if (!$this->canManageRoles()) {
            return response()->json(['error' => 'ليس لديك صلاحية لحذف الأدوار'], 403);
        }

        // منع حذف دور السوبر أدمن
        if ($this->isSuperAdminRole($role)) {
            return response()->json(['error' => 'لا يمكن حذف دور السوبر أدمن'], 403);
        }

        // التحقق من عدم وجود مستخدمين مرتبطين بهذا الدور
        if ($role->users()->count() > 0) {
            return response()->json(['error' => 'لا يمكن حذف دور مرتبط بمستخدمين'], 400);
        }

        try {
            $role->delete();
            return response()->json(['success' => 'تم حذف الدور بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء حذف الدور'], 500);
        }
    }

    /**
     * تعيين دور لمستخدم
     */
    public function assignRole(Request $request)
    {
        if (!$this->canManageUsers()) {
            return response()->json(['error' => 'ليس لديك صلاحية لإدارة المستخدمين'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        $user = User::findOrFail($request->user_id);
        
        // منع تعديل السوبر أدمن من قبل الأدمن العادي
        if ($this->isSuperAdmin($user) && !$this->isSuperAdmin()) {
            return response()->json(['error' => 'لا يمكن تعديل صلاحيات السوبر أدمن'], 403);
        }

        // منع إزالة دور السوبر أدمن من السوبر أدمن
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($this->isSuperAdmin($user) && $superAdminRole && !in_array($superAdminRole->id, $request->role_ids)) {
            return response()->json(['error' => 'لا يمكن إزالة دور السوبر أدمن من السوبر أدمن'], 403);
        }

        try {
            $user->roles()->sync($request->role_ids);
            return response()->json(['success' => 'تم تحديث أدوار المستخدم بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء تحديث الأدوار'], 500);
        }
    }

    /**
     * إنشاء صلاحية جديدة
     */
    public function createPermission(Request $request)
    {
        if (!$this->canManagePermissions()) {
            return response()->json(['error' => 'ليس لديك صلاحية لإنشاء الصلاحيات'], 403);
        }

        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'display_name' => 'required|string',
            'description' => 'nullable|string',
            'module' => 'required|string',
            'action' => 'required|string'
        ]);

        try {
            $permission = Permission::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'module' => $request->module,
                'action' => $request->action,
                'is_active' => true
            ]);

            return response()->json(['success' => 'تم إنشاء الصلاحية بنجاح', 'permission' => $permission]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء إنشاء الصلاحية'], 500);
        }
    }

    /**
     * حذف صلاحية
     */
    public function deletePermission(Permission $permission)
    {
        if (!$this->canManagePermissions()) {
            return response()->json(['error' => 'ليس لديك صلاحية لحذف الصلاحيات'], 403);
        }

        try {
            $permission->delete();
            return response()->json(['success' => 'تم حذف الصلاحية بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء حذف الصلاحية'], 500);
        }
    }

    /**
     * الحصول على معلومات المستخدم مع أدواره
     */
    public function getUserRoles(User $user)
    {
        if (!$this->canViewUsers()) {
            return response()->json(['error' => 'ليس لديك صلاحية لعرض بيانات المستخدمين'], 403);
        }

        return response()->json([
            'user' => $user->load('roles.permissions'),
            'available_roles' => Role::all()
        ]);
    }

    /**
     * التحقق من إمكانية الوصول إلى صفحة الصلاحيات
     */
    private function canAccessPermissions()
    {
        $user = Auth::user();
        return $this->isSuperAdmin($user) || $user->hasPermission('permissions.view');
    }

    /**
     * التحقق من إمكانية إدارة الأدوار
     */
    private function canManageRoles()
    {
        $user = Auth::user();
        return $this->isSuperAdmin($user) || $user->hasPermission('roles.manage');
    }

    /**
     * التحقق من إمكانية إدارة الصلاحيات
     */
    private function canManagePermissions()
    {
        $user = Auth::user();
        return $this->isSuperAdmin($user);
    }

    /**
     * التحقق من إمكانية إدارة المستخدمين
     */
    private function canManageUsers()
    {
        $user = Auth::user();
        return $this->isSuperAdmin($user) || $user->hasPermission('users.manage');
    }

    /**
     * التحقق من إمكانية عرض المستخدمين
     */
    private function canViewUsers()
    {
        $user = Auth::user();
        return $this->isSuperAdmin($user) || $user->hasPermission('users.view');
    }

    /**
     * التحقق من كون المستخدم سوبر أدمن
     */
    private function isSuperAdmin($user = null)
    {
        $user = $user ?: Auth::user();
        return $user && $user->hasRole('super_admin');
    }

    /**
     * التحقق من كون الدور دور سوبر أدمن
     */
    private function isSuperAdminRole(Role $role)
    {
        return $role->name === 'super_admin';
    }

    /**
     * الحصول على دور معين
     */
    public function getRole(Role $role)
    {
        if (!$this->canAccessPermissions()) {
            return response()->json(['error' => 'ليس لديك صلاحية للوصول'], 403);
        }

        return response()->json($role->load('permissions'));
    }

    /**
     * الحصول على تفاصيل دور معين
     */
    public function getRoleDetails(Role $role)
    {
        if (!$this->canAccessPermissions()) {
            return response()->json(['error' => 'ليس لديك صلاحية للوصول'], 403);
        }

        return response()->json([
            'role' => $role->load('permissions', 'users')
        ]);
    }

    /**
     * الحصول على جميع الصلاحيات مجمعة حسب الوحدة
     */
    public function getAllPermissions()
    {
        if (!$this->canAccessPermissions()) {
            return response()->json(['error' => 'ليس لديك صلاحية للوصول'], 403);
        }

        $permissions = Permission::all()->groupBy('module');
        return response()->json($permissions);
    }
}
