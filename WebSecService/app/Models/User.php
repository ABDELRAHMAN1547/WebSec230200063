<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'role',
        'email',
        'phone',
        'address',
        'status',
        'admin',
        'security_question',
        'security_answer',
        'password',
        'microsoft_id',
        'google_id',
        'avatar',
        'last_login_at',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'security_answer',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'admin' => 'boolean',
        ];
    }

    // Accessor للحصول على الدور بالعربية
    public function getRoleTextAttribute()
    {
        return match($this->role) {
            'admin' => 'مدير',
            'teacher' => 'معلم',
            'student' => 'طالب',
            'staff' => 'موظف',
            default => 'غير محدد'
        };
    }

    // Accessor للحصول على الحالة بالعربية
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'suspended' => 'معلق',
            'banned' => 'محظور',
            default => 'غير محدد'
        };
    }

    public function getAdminTextAttribute()
    {
        return $this->admin ? 'مدير' : 'مستخدم عادي';
    }

    public function getSecurityQuestionTextAttribute()
    {
        return $this->security_question ?: 'لم يتم تعيين سؤال أمني';
    }

    // Accessor للحصول على الاسم الكامل
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    // Accessor للحصول على الحروف الأولى من الاسم
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= mb_substr($word, 0, 1, 'UTF-8');
            }
        }
        return mb_strtoupper($initials, 'UTF-8');
    }

    public function isAdmin()
    {
        return $this->admin === true;
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    // العلاقة مع الأدوار
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // العلاقة مع الطلاب (إذا كان المستخدم معلم)
    public function students()
    {
        return $this->hasMany(Student::class, 'teacher_id');
    }

    // العلاقة مع الامتحانات (إذا كان المستخدم معلم)
    public function exams()
    {
        return $this->hasMany(Exam::class, 'teacher_id');
    }

    // العلاقة مع محاولات الامتحان (إذا كان المستخدم طالب)
    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class, 'student_id');
    }

    // العلاقة مع الدرجات (إذا كان المستخدم طالب)
    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    // العلاقة مع الأسئلة (إذا كان المستخدم معلم)
    public function questions()
    {
        return $this->hasMany(Question::class, 'teacher_id');
    }

    // دالة للتحقق من وجود دور معين
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    // دالة للتحقق من وجود صلاحية معينة
    public function hasPermission($permissionName)
    {
        return $this->roles()
            ->whereHas('permissions', function($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })
            ->exists();
    }

    // دالة للتحقق من وجود صلاحية في وحدة معينة
    public function can($action, $module = null)
    {
        if ($module) {
            return $this->roles()
                ->whereHas('permissions', function($query) use ($action, $module) {
                    $query->where('action', $action)
                          ->where('module', $module);
                })
                ->exists();
        }

        return $this->hasPermission($action);
    }

    // دالة للتحقق من إمكانية إدارة المستخدمين
    public function canManageUsers()
    {
        return $this->isAdmin() || $this->hasPermission('users.manage');
    }

    // دالة للتحقق من إمكانية إدارة الأدوار
    public function canManageRoles()
    {
        return $this->isAdmin() || $this->hasPermission('roles.manage');
    }

    // دالة للتحقق من إمكانية إدارة الصلاحيات
    public function canManagePermissions()
    {
        return $this->isAdmin() || $this->hasPermission('permissions.manage');
    }

    // دالة للتحقق من إمكانية إدارة الطلاب
    public function canManageStudents()
    {
        return $this->isAdmin() || $this->isTeacher() || $this->hasPermission('students.manage');
    }

    // دالة للتحقق من إمكانية إدارة الامتحانات
    public function canManageExams()
    {
        return $this->isAdmin() || $this->isTeacher() || $this->hasPermission('exams.manage');
    }

    // دالة للتحقق من إمكانية إدارة الدرجات
    public function canManageGrades()
    {
        return $this->isAdmin() || $this->isTeacher() || $this->hasPermission('grades.manage');
    }

    // دالة للحصول على جميع الصلاحيات
    public function getAllPermissions()
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->flatMap(function($role) {
                return $role->permissions;
            })
            ->unique('id');
    }

    // دالة لتحديث آخر تسجيل دخول
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    // دالة للتحقق من أن المستخدم نشط
    public function isActive()
    {
        return $this->status === 'active';
    }

    // دالة للتحقق من أن المستخدم معلق
    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    // دالة للتحقق من أن المستخدم محظور
    public function isBanned()
    {
        return $this->status === 'banned';
    }

    // دالة لحساب GPA (إذا كان المستخدم طالب)
    public function calculateGPA()
    {
        if (!$this->isStudent()) {
            return null;
        }

        $grades = $this->grades;
        if ($grades->isEmpty()) return 0.00;
        
        $totalPoints = $grades->sum('points');
        $totalHours = $grades->sum('credit_hours');
        
        return $totalHours > 0 ? round($totalPoints / $totalHours, 2) : 0.00;
    }

    // دالة لحساب الساعات المعتمدة (إذا كان المستخدم طالب)
    public function calculateCreditHours()
    {
        if (!$this->isStudent()) {
            return 0;
        }

        return $this->grades->sum('credit_hours');
    }

    // Scope للمستخدمين النشطين فقط
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope للمستخدمين المعلقين
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    // Scope للمستخدمين المحظورين
    public function scopeBanned($query)
    {
        return $query->where('status', 'banned');
    }

    // Scope للمدرسين فقط
    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    // Scope للطلاب فقط
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    // Scope للموظفين فقط
    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }

    // Scope للمديرين فقط
    public function scopeAdmins($query)
    {
        return $query->where('admin', true);
    }

    // Scope للبحث عن المستخدمين حسب الاسم
    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    // Scope للبحث عن المستخدمين حسب البريد الإلكتروني
    public function scopeSearchByEmail($query, $email)
    {
        return $query->where('email', 'like', '%' . $email . '%');
    }

    // Scope للبحث عن المستخدمين حسب اسم المستخدم
    public function scopeSearchByUsername($query, $username)
    {
        return $query->where('username', 'like', '%' . $username . '%');
    }

    // Scope للبحث عن المستخدمين حسب الدور
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Scope للبحث عن المستخدمين حسب الحالة
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope للمستخدمين الذين لم يسجلوا دخول منذ فترة
    public function scopeInactive($query, $days = 30)
    {
        return $query->where('last_login_at', '<', now()->subDays($days))
                    ->orWhereNull('last_login_at');
    }

    // دالة للحصول على الإحصائيات
    public static function getStats()
    {
        return [
            'total' => self::count(),
            'active' => self::active()->count(),
            'suspended' => self::suspended()->count(),
            'banned' => self::banned()->count(),
            'teachers' => self::teachers()->count(),
            'students' => self::students()->count(),
            'staff' => self::staff()->count(),
            'admins' => self::admins()->count(),
        ];
    }

    // دالة للتحقق من صحة كلمة المرور
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }

    // دالة لتغيير كلمة المرور
    public function changePassword($newPassword)
    {
        $this->update(['password' => bcrypt($newPassword)]);
    }

    // دالة للتحقق من السؤال الأمني
    public function validateSecurityAnswer($answer)
    {
        return strtolower(trim($this->security_answer)) === strtolower(trim($answer));
    }

    // دالة لإعادة تعيين كلمة المرور
    public function resetPassword($newPassword)
    {
        $this->update([
            'password' => bcrypt($newPassword),
            'remember_token' => null
        ]);
    }

    // دالة لحذف المستخدم (Soft Delete)
    public function softDelete()
    {
        $this->update(['status' => 'deleted']);
        $this->delete();
    }

    // دالة لاستعادة المستخدم المحذوف
    public function restoreUser()
    {
        $this->restore();
        $this->update(['status' => 'active']);
    }

    // دالة لحظر المستخدم
    public function ban()
    {
        $this->update(['status' => 'banned']);
    }

    // دالة لإلغاء حظر المستخدم
    public function unban()
    {
        $this->update(['status' => 'active']);
    }

    // دالة لتعليق المستخدم
    public function suspend()
    {
        $this->update(['status' => 'suspended']);
    }

    // دالة لإلغاء تعليق المستخدم
    public function unsuspend()
    {
        $this->update(['status' => 'active']);
    }
}
