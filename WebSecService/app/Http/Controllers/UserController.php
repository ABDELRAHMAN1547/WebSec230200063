<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%');
            });
        }

        // تصفية حسب الدور
        if ($request->filled('role_filter')) {
            $query->where('role', $request->role_filter);
        }

        // تصفية حسب الحالة
        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        $users->appends($request->query());
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,teacher,student',
            'status' => 'required|in:active,inactive,suspended',
            'admin' => 'boolean',
            'security_question' => 'required|string',
            'security_answer' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => $request->role,
                'status' => $request->status,
                'admin' => $request->has('admin'),
                'security_question' => $request->security_question,
                'security_answer' => $request->security_answer,
                'password' => bcrypt($request->password),
            ]);

            \Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);
            return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error creating user', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء المستخدم']);
        }
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('users.edit', compact('user'));
        } catch (\Exception $e) {
            return redirect('/users')->with('error', 'المستخدم غير موجود');
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,teacher,student',
            'status' => 'required|in:active,inactive,suspended',
            'admin' => 'boolean',
            'security_question' => 'required|string',
            'security_answer' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => $request->role,
                'status' => $request->status,
                'admin' => $request->has('admin'),
                'security_question' => $request->security_question,
                'security_answer' => $request->security_answer,
            ];

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $user->update($data);

            \Log::info('User updated successfully', ['user_id' => $user->id, 'email' => $user->email]);
            return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error updating user', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث المستخدم']);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect('/users')->with('success', 'تم حذف المستخدم بنجاح');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect('/users')->with('error', 'حدث خطأ أثناء حذف المستخدم');
        }
    }

    public function profile($id)
    {
        $user = User::findOrFail($id);
        $currentUser = auth()->user();

        // Check if current user can view this profile
        if (!$currentUser->canManageUsers() && $currentUser->id !== $user->id) {
            return back()->withErrors(['error' => 'ليس لديك صلاحية لعرض هذا الملف الشخصي']);
        }

        return view('users.profile', compact('user'));
    }
}
