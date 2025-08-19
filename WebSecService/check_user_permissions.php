<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

// البحث عن المستخدم
$user = User::where('email', 'abdelrahmankool@gmail.com')->first();

if (!$user) {
    echo "User not found!\n";
    exit;
}

echo "🔍 User Permissions Analysis:\n";
echo "============================\n\n";

echo "👤 User Info:\n";
echo "Name: " . $user->name . "\n";
echo "Email: " . $user->email . "\n";
echo "Admin Flag: " . ($user->admin ? 'Yes ✅' : 'No ❌') . "\n\n";

echo "🎭 User Roles:\n";
$roles = $user->roles;
if ($roles->count() > 0) {
    foreach ($roles as $role) {
        echo "- " . $role->name . " (" . $role->display_name . ")\n";
    }
} else {
    echo "No roles assigned ❌\n";
}

echo "\n🔑 Permission Checks:\n";
echo "Has super_admin role: " . ($user->hasRole('super_admin') ? 'Yes ✅' : 'No ❌') . "\n";
echo "Has permissions.view: " . ($user->hasPermission('permissions.view') ? 'Yes ✅' : 'No ❌') . "\n";
echo "Has permissions.create: " . ($user->hasPermission('permissions.create') ? 'Yes ✅' : 'No ❌') . "\n";
echo "Has permissions.edit: " . ($user->hasPermission('permissions.edit') ? 'Yes ✅' : 'No ❌') . "\n";
echo "Has roles.manage: " . ($user->hasPermission('roles.manage') ? 'Yes ✅' : 'No ❌') . "\n";
echo "Has users.manage: " . ($user->hasPermission('users.manage') ? 'Yes ✅' : 'No ❌') . "\n";

echo "\n📋 All User Permissions:\n";
$allPermissions = collect();
foreach ($user->roles as $role) {
    $rolePermissions = $role->permissions;
    $allPermissions = $allPermissions->merge($rolePermissions);
}
$uniquePermissions = $allPermissions->unique('id');

if ($uniquePermissions->count() > 0) {
    foreach ($uniquePermissions as $permission) {
        echo "- " . $permission->name . " (" . $permission->display_name . ")\n";
    }
} else {
    echo "No permissions found ❌\n";
}

echo "\n🎯 Recommendations:\n";
if (!$user->canAccessPermissions()) {
    echo "❌ User cannot access permissions page\n";
    echo "💡 Need to assign Admin role or permissions.view permission\n";
} else {
    echo "✅ User can access permissions page\n";
    if (!$user->hasPermission('permissions.edit')) {
        echo "⚠️  User can view but cannot edit permissions\n";
        echo "💡 Need permissions.edit permission for editing\n";
    } else {
        echo "✅ User has full permissions access\n";
    }
}
