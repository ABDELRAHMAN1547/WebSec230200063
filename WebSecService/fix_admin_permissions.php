<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use App\Models\Permission;

// البحث عن دور Admin
$adminRole = Role::where('name', 'admin')->first();

if (!$adminRole) {
    echo "Admin role not found!\n";
    exit;
}

// البحث عن صلاحية permissions.view
$permissionsViewPermission = Permission::where('name', 'permissions.view')->first();

if (!$permissionsViewPermission) {
    echo "permissions.view permission not found!\n";
    exit;
}

// التحقق من وجود الصلاحية مسبقاً
if ($adminRole->permissions()->where('permission_id', $permissionsViewPermission->id)->exists()) {
    echo "Admin role already has permissions.view permission!\n";
} else {
    // إعطاء صلاحية permissions.view لدور Admin
    $adminRole->permissions()->attach($permissionsViewPermission->id);
    echo "permissions.view permission assigned successfully to admin role!\n";
}

// إعطاء صلاحيات إضافية لدور Admin
$additionalPermissions = [
    'users.view',
    'users.manage', 
    'roles.manage'
];

foreach ($additionalPermissions as $permissionName) {
    $permission = Permission::where('name', $permissionName)->first();
    if ($permission && !$adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
        $adminRole->permissions()->attach($permission->id);
        echo "Permission {$permissionName} assigned to admin role!\n";
    }
}

// عرض صلاحيات دور Admin
echo "\nAdmin role permissions: " . $adminRole->permissions->pluck('name')->implode(', ') . "\n";
