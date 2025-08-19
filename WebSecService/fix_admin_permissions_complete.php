<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use App\Models\Permission;

echo "🔧 Fixing Admin Role Permissions:\n";
echo "================================\n\n";

// البحث عن دور Admin
$adminRole = Role::where('name', 'admin')->first();

if (!$adminRole) {
    echo "❌ Admin role not found!\n";
    exit;
}

echo "👤 Found Admin Role: " . $adminRole->display_name . "\n";
echo "Current permissions count: " . $adminRole->permissions->count() . "\n\n";

// الصلاحيات المطلوبة لدور Admin
$requiredPermissions = [
    'permissions.view',
    'permissions.create', 
    'permissions.edit',
    'permissions.delete',
    'roles.view',
    'roles.create',
    'roles.edit', 
    'roles.manage',
    'users.view',
    'users.create',
    'users.edit',
    'users.manage'
];

echo "🔑 Adding missing permissions to Admin role:\n";

$addedCount = 0;
foreach ($requiredPermissions as $permissionName) {
    $permission = Permission::where('name', $permissionName)->first();
    
    if ($permission) {
        // التحقق من وجود الصلاحية مسبقاً
        if (!$adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
            $adminRole->permissions()->attach($permission->id);
            echo "✅ Added: " . $permissionName . " (" . $permission->display_name . ")\n";
            $addedCount++;
        } else {
            echo "⚪ Already has: " . $permissionName . "\n";
        }
    } else {
        echo "❌ Permission not found: " . $permissionName . "\n";
    }
}

echo "\n📊 Summary:\n";
echo "Permissions added: " . $addedCount . "\n";

// عرض جميع صلاحيات دور Admin النهائية
$adminRole->refresh();
echo "Total Admin permissions: " . $adminRole->permissions->count() . "\n\n";

echo "🎯 Admin Role Final Permissions:\n";
foreach ($adminRole->permissions->sortBy('name') as $permission) {
    echo "- " . $permission->name . " (" . $permission->display_name . ")\n";
}

echo "\n✅ Admin role permissions updated successfully!\n";
echo "🔄 Please refresh the permissions page to see the changes.\n";
