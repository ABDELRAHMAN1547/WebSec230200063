# WebSec Service - نظام إدارة الطلاب والامتحانات

## نظرة عامة
نظام متكامل لإدارة الطلاب والامتحانات والدرجات مع نظام صلاحيات متقدم يعتمد على الأدوار والصلاحيات.

## المميزات الرئيسية

### 🔐 نظام الصلاحيات والأدوار
- **نظام أدوار متقدم**: مدير عام، مدير، معلم، طالب، موظف
- **صلاحيات مفصلة**: عرض، إنشاء، تعديل، حذف، إدارة لكل وحدة
- **middleware للصلاحيات**: حماية المسارات حسب الصلاحيات
- **trait للصلاحيات**: سهولة استخدام في النماذج

### 👥 إدارة المستخدمين
- تسجيل دخول آمن مع أسئلة أمنية
- إدارة الحسابات (نشط، معلق، محظور)
- تتبع آخر تسجيل دخول
- حذف ناعم (Soft Delete)

### 🎓 إدارة الطلاب
- معلومات شاملة عن الطلاب
- حساب GPA تلقائي
- إدارة الدرجات والساعات المعتمدة
- تقارير مفصلة

### 📝 إدارة الامتحانات
- إنشاء وإدارة الامتحانات
- أسئلة متعددة الخيارات
- توقيت محدد للامتحانات
- تتبع محاولات الامتحان

### 📊 إدارة الدرجات
- تسجيل الدرجات تلقائي
- حساب النقاط والساعات المعتمدة
- تقارير أداء الطلاب

## التثبيت والإعداد

### المتطلبات
- PHP 8.2+
- Laravel 12.0+
- SQLite/MySQL/PostgreSQL

### خطوات التثبيت
```bash
# استنساخ المشروع
git clone [repository-url]
cd WebSecService

# تثبيت التبعيات
composer install
npm install

# نسخ ملف البيئة
cp .env.example .env

# إنشاء مفتاح التطبيق
php artisan key:generate

# تشغيل الـ migrations
php artisan migrate

# تشغيل الـ seeders
php artisan db:seed --class=RolesAndPermissionsSeeder

# بناء الأصول
npm run build

# تشغيل الخادم
php artisan serve
```

## هيكل النظام

### النماذج (Models)
- **User**: المستخدمون مع نظام الصلاحيات
- **Role**: الأدوار في النظام
- **Permission**: الصلاحيات المتاحة
- **Student**: معلومات الطلاب
- **Exam**: الامتحانات
- **Question**: الأسئلة
- **Grade**: الدرجات

### Middleware
- **SecurityHeaders**: رؤوس الأمان
- **UpdateLastLogin**: تحديث آخر تسجيل دخول
- **CheckPermission**: التحقق من الصلاحيات
- **CheckRole**: التحقق من الأدوار

### Traits
- **HasPermissions**: trait للصلاحيات

### Helper Functions
- `has_permission($permission)`: التحقق من صلاحية
- `can($action, $module)`: التحقق من صلاحية في وحدة
- `has_role($role)`: التحقق من دور
- `is_admin()`, `is_teacher()`, `is_student()`, `is_staff()`: التحقق من نوع المستخدم

## استخدام نظام الصلاحيات

### في Controllers
```php
public function index()
{
    if (!auth()->user()->can('view', 'students')) {
        abort(403, 'غير مصرح لك بالوصول');
    }
    
    $students = Student::all();
    return view('students.index', compact('students'));
}
```

### في Routes
```php
Route::middleware(['auth', 'permission:students.manage'])->group(function () {
    Route::resource('students', StudentController::class);
});
```

### في Blade Views
```php
@if(has_permission('students.create'))
    <a href="{{ route('students.create') }}" class="btn btn-primary">إضافة طالب</a>
@endif

@if(can('edit', 'students'))
    <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">تعديل</a>
@endif
```

### في Middleware
```php
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard']);
});
```

## الأدوار والصلاحيات

### المدير العام (Super Admin)
- جميع الصلاحيات في النظام
- إدارة الأدوار والصلاحيات
- إدارة جميع المستخدمين

### المدير (Admin)
- إدارة المستخدمين (عدا الأدوار والصلاحيات)
- إدارة الطلاب والامتحانات والدرجات
- إدارة الأسئلة

### المعلم (Teacher)
- إدارة الطلاب المسندين إليه
- إنشاء وإدارة الامتحانات
- تسجيل الدرجات
- إدارة الأسئلة

### الطالب (Student)
- عرض الامتحانات المتاحة
- عرض درجاته
- لا يمكنه التعديل أو الحذف

### الموظف (Staff)
- عرض الطلاب والدرجات والامتحانات
- صلاحيات محدودة للقراءة فقط

## الأمان

### ميزات الأمان
- تشفير كلمات المرور
- أسئلة أمنية لاستعادة الحساب
- middleware لحماية المسارات
- تحقق من الصلاحيات في كل مستوى
- رؤوس أمان متقدمة

### أفضل الممارسات
- استخدام middleware للصلاحيات
- التحقق من الصلاحيات في Controllers
- استخدام helper functions في Views
- عدم عرض أزرار غير مصرح بها

## التطوير

### إضافة صلاحية جديدة
1. إضافة الصلاحية في `RolesAndPermissionsSeeder`
2. تشغيل الـ seeder
3. استخدام الصلاحية في الكود

### إضافة دور جديد
1. إضافة الدور في `RolesAndPermissionsSeeder`
2. ربط الصلاحيات المناسبة
3. تشغيل الـ seeder

### إضافة middleware جديد
1. إنشاء middleware جديد
2. إضافته في `bootstrap/app.php`
3. استخدامه في المسارات

## الاختبار

```bash
# تشغيل الاختبارات
php artisan test

# تشغيل اختبارات محددة
php artisan test --filter=UserTest
```

## المساهمة

1. Fork المشروع
2. إنشاء branch للميزة الجديدة
3. Commit التغييرات
4. Push إلى الـ branch
5. إنشاء Pull Request

## الترخيص

هذا المشروع مرخص تحت رخصة MIT.

## الدعم

للمساعدة والدعم، يرجى فتح issue في GitHub أو التواصل مع فريق التطوير.
