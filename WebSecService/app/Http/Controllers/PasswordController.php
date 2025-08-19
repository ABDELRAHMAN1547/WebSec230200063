<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user->security_question) {
            return back()->withErrors(['email' => 'هذا الحساب لا يحتوي على سؤال أمني']);
        }

        // Store user info in session for security question step
        session([
            'email' => $user->email,
            'security_question' => $user->security_question,
            'reset_token' => Str::random(60)
        ]);

        return back()->with('success', 'تم العثور على الحساب. يرجى الإجابة على السؤال الأمني.');
    }

    public function verifySecurityQuestion(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'security_answer' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->security_question) {
            return back()->withErrors(['security_answer' => 'معلومات غير صحيحة']);
        }

        // Check if security answer matches (case-insensitive)
        if (strtolower(trim($user->security_answer)) !== strtolower(trim($request->security_answer))) {
            return back()->withErrors(['security_answer' => 'الإجابة غير صحيحة']);
        }

        // Store reset token in session
        session(['reset_token' => Str::random(60)]);

        return redirect()->route('password.reset')->with('success', 'تم التحقق من هويتك بنجاح');
    }

    public function showResetForm()
    {
        if (!session('email') || !session('reset_token')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'البريد الإلكتروني غير موجود']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Clear session
        session()->forget(['email', 'security_question', 'reset_token']);

        return redirect()->route('login')->with('success', 'تم تغيير كلمة المرور بنجاح. يمكنك الآن تسجيل الدخول بكلمة المرور الجديدة.');
    }

    public function changePassword(Request $request, $userId)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($userId);
        $currentUser = auth()->user();

        // Check if current user can change this user's password
        if (!$currentUser->canManageUsers() && $currentUser->id !== $user->id) {
            return back()->withErrors(['error' => 'ليس لديك صلاحية لتغيير كلمة مرور هذا المستخدم']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}
