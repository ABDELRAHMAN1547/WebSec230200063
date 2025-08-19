<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    /**
     * إعادة توجيه المستخدم إلى مزود OAuth
     */
    public function redirectToProvider($provider)
    {
        // التحقق من أن المزود مدعوم
        if (!in_array($provider, ['google', 'microsoft'])) {
            return redirect('/login')->with('error', 'مزود الخدمة غير مدعوم.');
        }

        // التحقق من وجود إعدادات OAuth
        if (!config("services.{$provider}.client_id") || !config("services.{$provider}.client_secret")) {
            $providerName = $provider === 'google' ? 'Google' : 'Microsoft';
            return redirect('/login')->with('error', "{$providerName} OAuth غير مُعد بشكل صحيح. يرجى التواصل مع المسؤول.");
        }

        try {
            // تحديد redirect URI بناءً على الطلب الحالي
            $baseUrl = request()->getSchemeAndHttpHost();
            $redirectUri = $baseUrl . '/oauth/' . $provider . '/callback';
            
            return Socialite::driver($provider)
                ->redirectUrl($redirectUri)
                ->redirect();
        } catch (\Exception $e) {
            $providerName = $provider === 'google' ? 'Google' : 'Microsoft';
            return redirect('/login')->with('error', "حدث خطأ أثناء الاتصال بـ {$providerName}: " . $e->getMessage());
        }
    }

    /**
     * معالجة الاستجابة من مزود OAuth
     */
    public function handleProviderCallback($provider)
    {
        // التحقق من أن المزود مدعوم
        if (!in_array($provider, ['google', 'microsoft'])) {
            return redirect('/login')->with('error', 'مزود الخدمة غير مدعوم.');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // البحث عن المستخدم في قاعدة البيانات
            $user = $this->findOrCreateUser($socialUser, $provider);
            
            // تسجيل دخول المستخدم
            Auth::login($user, true);

            $providerName = $provider === 'google' ? 'Google' : 'Microsoft';
            
            // إعادة التوجيه إلى صفحة الصلاحيات مباشرة للمستخدمين الجدد
            return redirect('/admin/permissions')->with('success', "تم تسجيل الدخول بنجاح باستخدام {$providerName}! مرحباً بك في نظام إدارة الصلاحيات.");

        } catch (\Exception $e) {
            $providerName = $provider === 'google' ? 'Google' : 'Microsoft';
            return redirect('/login')->with('error', "حدث خطأ أثناء تسجيل الدخول باستخدام {$providerName}: " . $e->getMessage());
        }
    }

    /**
     * البحث عن المستخدم أو إنشاء مستخدم جديد
     */
    private function findOrCreateUser($socialUser, $provider)
    {
        $providerIdField = $provider . '_id';
        
        // البحث عن المستخدم بواسطة معرف المزود
        $user = User::where($providerIdField, $socialUser->getId())->first();
        
        if ($user) {
            // تحديث بيانات المستخدم الموجود
            $user->update([
                'name' => $socialUser->getName(),
                'avatar' => $socialUser->getAvatar(),
                'last_login_at' => now(),
            ]);
            return $user;
        }

        // البحث عن المستخدم بواسطة البريد الإلكتروني
        $user = User::where('email', $socialUser->getEmail())->first();
        
        if ($user) {
            // ربط الحساب الموجود بمزود OAuth
            $user->update([
                'name' => $socialUser->getName(),
                $providerIdField => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'last_login_at' => now(),
            ]);
            return $user;
        }

        // إنشاء مستخدم جديد
        $user = User::create([
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'username' => $this->generateUsername($socialUser->getEmail()),
            $providerIdField => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
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

        return $user;
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
