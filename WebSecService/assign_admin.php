<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;

// البحث عن المستخدم
$user = User::where('email', 'abdelrahmankool@gmail.com')->first();

if (!$user) {
    echo "User not found!\n";
    exit;
}

// البحث عن دور Admin
$adminRole = Role::where('name', 'admin')->first();

if (!$adminRole) {
    echo "Admin role not found!\n";
    exit;
}

// التحقق من وجود الدور مسبقاً
if ($user->roles()->where('role_id', $adminRole->id)->exists()) {
    echo "User already has admin role!\n";
} else {
    // إعطاء دور Admin
    $user->roles()->attach($adminRole->id);
    echo "Admin role assigned successfully to {$user->email}!\n";
}

// عرض أدوار المستخدم
echo "User roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
