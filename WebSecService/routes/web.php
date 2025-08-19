<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\OAuthController;

// الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
});

// 🏠 الداشبورد - Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// -------------------------
// 👨‍🎓 الطلاب - Student CRUD
// -------------------------
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

// -------------------------
// 👥 المستخدمين - Users CRUD
// -------------------------
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/{id}/profile', [UserController::class, 'profile'])->name('users.profile');

// 🔐 إدارة كلمات المرور - Password Management
Route::get('/forgot-password', [PasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/forgot-password/security', [PasswordController::class, 'verifySecurityQuestion'])->name('password.security');
Route::get('/reset-password', [PasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.reset');
Route::post('/users/{id}/change-password', [PasswordController::class, 'changePassword'])->name('users.change-password');

// 🔑 تسجيل الدخول - Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔐 OAuth Authentication (Google & Microsoft)
Route::get('/oauth/{provider}', [OAuthController::class, 'redirectToProvider'])->name('oauth.redirect');
Route::get('/oauth/{provider}/callback', [OAuthController::class, 'handleProviderCallback'])->name('oauth.callback');
Route::post('/oauth/logout', [OAuthController::class, 'logout'])->name('oauth.logout');

// 🔐 Alternative OAuth routes for Google Console compatibility
Route::get('/auth/{provider}', [OAuthController::class, 'redirectToProvider'])->name('auth.redirect');
Route::get('/auth/{provider}/callback', [OAuthController::class, 'handleProviderCallback'])->name('auth.callback');

// OAuth test page
Route::get('/oauth-test', function () {
    return view('oauth-test');
})->name('oauth.test');

// OAuth debug page
Route::get('/oauth-debug', function () {
    return view('oauth-debug');
})->name('oauth.debug');

// Direct Google OAuth test
Route::get('/test-google-oauth', function () {
    try {
        return Socialite::driver('google')
            ->redirectUrl('http://localhost:8000/oauth/google/callback')
            ->redirect();
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

// -------------------------
// 🔐 إدارة الصلاحيات - Permissions Management
// -------------------------
Route::prefix('admin/permissions')->middleware('auth')->group(function () {
    // صفحة إدارة الصلاحيات الرئيسية
    Route::get('/', [PermissionsController::class, 'index'])->name('permissions.index');
    
    // إدارة الأدوار
    Route::post('/roles', [PermissionsController::class, 'createRole'])->name('permissions.roles.create');
    Route::get('/roles/{role}', [PermissionsController::class, 'getRole'])->name('permissions.roles.show');
    Route::get('/roles/{role}/details', [PermissionsController::class, 'getRoleDetails'])->name('permissions.roles.details');
    Route::put('/roles/{role}', [PermissionsController::class, 'updateRole'])->name('permissions.roles.update');
    Route::delete('/roles/{role}', [PermissionsController::class, 'deleteRole'])->name('permissions.roles.delete');
    
    // إدارة الصلاحيات
    Route::get('/all', [PermissionsController::class, 'getAllPermissions'])->name('permissions.all');
    Route::post('/permissions', [PermissionsController::class, 'createPermission'])->name('permissions.permissions.create');
    Route::delete('/permissions/{permission}', [PermissionsController::class, 'deletePermission'])->name('permissions.permissions.delete');
    
    // إدارة أدوار المستخدمين
    Route::get('/users/{user}/roles', [PermissionsController::class, 'getUserRoles'])->name('permissions.users.roles');
    Route::post('/assign-role', [PermissionsController::class, 'assignRole'])->name('permissions.assign-role');
});

// -------------------------
// 📊 الدرجات - Grades CRUD
// -------------------------
Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
Route::get('/grades/create', [GradeController::class, 'create'])->name('grades.create');
Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
Route::get('/grades/{id}/edit', [GradeController::class, 'edit'])->name('grades.edit');
Route::put('/grades/{id}', [GradeController::class, 'update'])->name('grades.update');
Route::delete('/grades/{id}', [GradeController::class, 'destroy'])->name('grades.destroy');
Route::get('/grades/student/{studentId}', [GradeController::class, 'studentGrades'])->name('grades.student');

// -------------------------
// 🧾 MiniTest - فاتورة سوبر ماركت
// -------------------------
Route::get('/minitest', function () {
    $bill = [
        ['item' => 'Apples', 'qty' => 2, 'price' => 15.50],
        ['item' => 'Milk', 'qty' => 1, 'price' => 8.75],
        ['item' => 'Bread', 'qty' => 3, 'price' => 5.25],
        ['item' => 'Chicken', 'qty' => 1, 'price' => 45.00],
        ['item' => 'Rice', 'qty' => 2, 'price' => 12.50],
        ['item' => 'Tomatoes', 'qty' => 1, 'price' => 7.80],
        ['item' => 'Cheese', 'qty' => 1, 'price' => 18.90],
        ['item' => 'Eggs', 'qty' => 2, 'price' => 22.00]
    ];
    return view('minitest', compact('bill'));
})->name('minitest');

// -------------------------
// 🧑‍🎓 Transcript - كشف درجات
// -------------------------
Route::get('/transcript', function () {
    $transcript = [
        ['course' => 'Math', 'grade' => 'A'],
        ['course' => 'Physics', 'grade' => 'B+'],
        ['course' => 'CS', 'grade' => 'A-'],
        ['course' => 'English', 'grade' => 'B'],
        ['course' => 'Chemistry', 'grade' => 'A'],
        ['course' => 'History', 'grade' => 'C+'],
        ['course' => 'Programming', 'grade' => 'A'],
        ['course' => 'Statistics', 'grade' => 'B-']
    ];
    return view('transcript', compact('transcript'));
})->name('transcript');

// -------------------------
// 🛍️ Products - كتالوج منتجات
// -------------------------
Route::get('/products', function () {
    $products = collect([
        [
            'name' => 'لابتوب ديل إكس بي إس 13',
            'image' => 'https://via.placeholder.com/300x200/007bff/ffffff?text=Laptop',
            'price' => 4500.00,
            'desc' => 'لابتوب ديل XPS 13 بمعالج Intel Core i7 وذاكرة 16GB',
            'category' => 'electronics'
        ],
        [
            'name' => 'آيفون 15 برو',
            'image' => 'https://via.placeholder.com/300x200/28a745/ffffff?text=iPhone',
            'price' => 3800.00,
            'desc' => 'آيفون 15 برو بذاكرة 256GB وكاميرا ثلاثية',
            'category' => 'electronics'
        ],
        [
            'name' => 'تلفاز سامسونج 4K',
            'image' => 'https://via.placeholder.com/300x200/dc3545/ffffff?text=TV',
            'price' => 2800.00,
            'desc' => 'تلفاز سامسونج 4K مقاس 55 بوصة',
            'category' => 'electronics'
        ],
        [
            'name' => 'حذاء نايك أير ماكس',
            'image' => 'https://via.placeholder.com/300x200/ffc107/000000?text=Shoes',
            'price' => 450.00,
            'desc' => 'حذاء نايك أير ماكس رياضي مريح',
            'category' => 'clothing'
        ],
        [
            'name' => 'قميص أديداس رياضي',
            'image' => 'https://via.placeholder.com/300x200/17a2b8/ffffff?text=T-Shirt',
            'price' => 120.00,
            'desc' => 'قميص أديداس قطني 100% مريح للرياضة',
            'category' => 'clothing'
        ],
        [
            'name' => 'كتاب تعلم البرمجة',
            'image' => 'https://via.placeholder.com/300x200/6f42c1/ffffff?text=Book',
            'price' => 85.00,
            'desc' => 'كتاب تعلم البرمجة بلغة Python للمبتدئين',
            'category' => 'books'
        ],
        [
            'name' => 'سماعات لاسلكية',
            'image' => 'https://via.placeholder.com/300x200/fd7e14/ffffff?text=Headphones',
            'price' => 650.00,
            'desc' => 'سماعات لاسلكية مع إلغاء الضوضاء',
            'category' => 'electronics'
        ],
        [
            'name' => 'ماوس ألعاب',
            'image' => 'https://via.placeholder.com/300x200/e83e8c/ffffff?text=Mouse',
            'price' => 280.00,
            'desc' => 'ماوس للألعاب مع 6 أزرار قابلة للبرمجة',
            'category' => 'electronics'
        ]
    ]);
    
    return view('products', compact('products'));
})->name('products');

// -------------------------
// 🧮 Calculator - آلة حاسبة
// -------------------------
Route::view('/calculator', 'calculator')->name('calculator');

// -------------------------
// 📊 GPA Simulator
// -------------------------
Route::get('/gpa', function () {
    $courses = [
        ['code' => 'CS101', 'title' => 'مقدمة في علوم الحاسب', 'credit' => 3],
        ['code' => 'CS102', 'title' => 'البرمجة بلغة C++', 'credit' => 4],
        ['code' => 'CS201', 'title' => 'هياكل البيانات', 'credit' => 3],
        ['code' => 'CS202', 'title' => 'قواعد البيانات', 'credit' => 3],
        ['code' => 'CS301', 'title' => 'تطوير تطبيقات الويب', 'credit' => 4],
        ['code' => 'CS302', 'title' => 'أمن المعلومات', 'credit' => 3],
        ['code' => 'MATH101', 'title' => 'الرياضيات للهندسة', 'credit' => 4],
        ['code' => 'MATH201', 'title' => 'الإحصاء والاحتمالات', 'credit' => 3],
        ['code' => 'PHY101', 'title' => 'الفيزياء العامة', 'credit' => 4],
        ['code' => 'ENG101', 'title' => 'اللغة الإنجليزية التقنية', 'credit' => 3],
        ['code' => 'ENG201', 'title' => 'التواصل التقني', 'credit' => 2],
        ['code' => 'CHEM101', 'title' => 'الكيمياء العامة', 'credit' => 4],
        ['code' => 'ISL101', 'title' => 'الثقافة الإسلامية', 'credit' => 2],
        ['code' => 'ARB101', 'title' => 'اللغة العربية', 'credit' => 2],
        ['code' => 'CS401', 'title' => 'ذكاء اصطناعي', 'credit' => 3],
        ['code' => 'CS402', 'title' => 'شبكات الحاسب', 'credit' => 3]
    ];
    return view('gpa', compact('courses'));
})->name('gpa');

// -------------------------
// 📝 MCQ Exam System
// -------------------------
Route::get('/exams', [App\Http\Controllers\ExamController::class, 'index'])->name('exams.index');
Route::get('/exams/{id}/start', [App\Http\Controllers\ExamController::class, 'start'])->name('exams.start');
Route::post('/exams/{id}/submit', [App\Http\Controllers\ExamController::class, 'submit'])->name('exams.submit');
Route::get('/exams/result/{attemptId}', [App\Http\Controllers\ExamController::class, 'result'])->name('exams.result');

// Admin Exam Management
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/exams', [App\Http\Controllers\ExamController::class, 'adminIndex'])->name('admin.exams.index');
    Route::get('/admin/exams/create', [App\Http\Controllers\ExamController::class, 'adminCreate'])->name('admin.exams.create');
    Route::post('/admin/exams', [App\Http\Controllers\ExamController::class, 'adminStore'])->name('admin.exams.store');
    Route::get('/admin/exams/{id}/edit', [App\Http\Controllers\ExamController::class, 'adminEdit'])->name('admin.exams.edit');
    Route::put('/admin/exams/{id}', [App\Http\Controllers\ExamController::class, 'adminUpdate'])->name('admin.exams.update');
    Route::delete('/admin/exams/{id}', [App\Http\Controllers\ExamController::class, 'adminDestroy'])->name('admin.exams.destroy');
});

// Questions Management
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/questions', [App\Http\Controllers\QuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/create', [App\Http\Controllers\QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [App\Http\Controllers\QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/{id}/edit', [App\Http\Controllers\QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{id}', [App\Http\Controllers\QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{id}', [App\Http\Controllers\QuestionController::class, 'destroy'])->name('questions.destroy');
});
