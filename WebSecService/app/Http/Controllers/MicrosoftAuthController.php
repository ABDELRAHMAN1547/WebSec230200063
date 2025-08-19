<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class MicrosoftAuthController extends Controller
{
    /**
     * إعادة توجيه المستخدم إلى Microsoft OAuth
     */
    public function redirectToMicrosoft()
    {
        // التحقق من وجود إعدادات Microsoft OAuth
        if (!config('services.microsoft.client_id') || !config('services.microsoft.client_secret')) {
            return redirect('/login')->with('error', 'Microsoft OAuth غير مُعد بشكل صحيح. يرجى التواصل مع المسؤول.');
        }

        try {
            return Socialite::driver('microsoft')->redirect();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'حدث خطأ أثناء الاتصال بـ Microsoft: ' . $e->getMessage());
        }
    }

    /**
     * معالجة الاستجابة من Microsoft OAuth
     */
    public function handleMicrosoftCallback()
    {
        try {
            $microsoftUser = Socialite::driver('microsoft')->user();
            
            // البحث عن المستخدم في قاعدة البيانات
            $user = User::where('email', $microsoftUser->getEmail())->first();
            
            if ($user) {
                // تحديث بيانات المستخدم الموجود
                $user->update([
                    'name' => $microsoftUser->getName(),
                    'microsoft_id' => $microsoftUser->getId(),
                    'avatar' => $microsoftUser->getAvatar(),
                    'last_login_at' => now(),
                ]);
            } else {
                // إنشاء مستخدم جديد
                $user = User::create([
                    'name' => $microsoftUser->getName(),
                    'email' => $microsoftUser->getEmail(),
                    'username' => $this->generateUsername($microsoftUser->getEmail()),
                    'microsoft_id' => $microsoftUser->getId(),
                    'avatar' => $microsoftUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)), // كلمة مرور عشوائية
                    'role' => 'user', // دور افتراضي
                    'status' => 'active',
                    'admin' => false,
                    'email_verified_at' => now(),
                    'last_login_at' => now(),
                ]);

                // ربط المستخدم الجديد بدور المستخدم العادي
                $userRole = Role::where('name', 'user')->first();
                if ($userRole) {
                    $user->roles()->attach($userRole->id);
                }
            }

            // تسجيل دخول المستخدم
            Auth::login($user, true);

            return redirect()->intended('/dashboard')->with('success', 'تم تسجيل الدخول بنجاح باستخدام Microsoft!');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'حدث خطأ أثناء تسجيل الدخول باستخدام Microsoft: ' . $e->getMessage());
        }
    }

    /**
     * إنشاء اسم مستخدم فريد من البريد الإلكتروني
     */
    private function generateUsername($email)
    {
        $baseUsername = explode('@', $email)[0];
        $username = $baseUsername;
        $counter = 1;

        // التأكد من أن اسم المستخدم فريد
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * تسجيل خروج المستخدم
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
