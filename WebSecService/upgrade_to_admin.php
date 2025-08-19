<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;

// البحث عن المستخدم بالبريد الإلكتروني
$email = 'abdelrahmankool@gmail.com';
$user = User::where('email', $email)->first();

if (!$user) {
    echo "User not found with email: {$email}\n";
    exit;
}

echo "Found user: {$user->name} ({$user->email})\n";
echo "Current roles: " . $user->roles->pluck('name')->implode(', ') . "\n\n";

// البحث عن دور Admin
$adminRole = Role::where('name', 'admin')->first();

if (!$adminRole) {
    echo "Admin role not found!\n";
    exit;
}

// التحقق من وجود الدور مسبقاً
if ($user->roles()->where('role_id', $adminRole->id)->exists()) {
    echo "User already has Admin role!\n";
} else {
    // إضافة دور Admin للمستخدم
    $user->roles()->attach($adminRole->id);
    echo "✅ Admin role assigned successfully!\n";
}

// إزالة دور User العادي إذا كان موجوداً
$userRole = Role::where('name', 'user')->first();
if ($userRole && $user->roles()->where('role_id', $userRole->id)->exists()) {
    $user->roles()->detach($userRole->id);
    echo "✅ Regular user role removed!\n";
}

// تحديث حقل admin في جدول المستخدمين
$user->update(['admin' => true]);
echo "✅ Admin flag set to true!\n";

// عرض الأدوار النهائية
$user->refresh();
echo "\n🎉 Final user roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
echo "Admin status: " . ($user->admin ? 'Yes ✅' : 'No ❌') . "\n";

echo "\n🔄 Please refresh the permissions page to see the changes!\n";
