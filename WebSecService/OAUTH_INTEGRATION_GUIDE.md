# دليل تكامل OAuth - Google و Microsoft

## نظرة عامة
يوفر هذا الدليل إعداد شامل لتسجيل الدخول باستخدام Google و Microsoft OAuth في تطبيق Laravel الخاص بك، مما يتيح للمستخدمين تسجيل الدخول باستخدام حساباتهم على Google أو Microsoft.

## المتطلبات المسبقة
- حزمة Laravel Socialite (مثبتة بالفعل)
- حساب Google Cloud Platform
- حساب Microsoft Azure
- الوصول إلى Google Cloud Console و Azure Active Directory

---

## الجزء الأول: إعداد Google OAuth

### 1.1 الوصول إلى Google Cloud Console
1. اذهب إلى [Google Cloud Console](https://console.cloud.google.com)
2. سجل الدخول باستخدام حساب Google الخاص بك
3. أنشئ مشروعًا جديدًا أو اختر مشروعًا موجودًا

### 1.2 تمكين Google+ API
1. في Google Cloud Console، اذهب إلى **APIs & Services** > **Library**
2. ابحث عن "Google+ API" أو "People API"
3. انقر على **Enable**

### 1.3 إنشاء OAuth 2.0 Credentials
1. اذهب إلى **APIs & Services** > **Credentials**
2. انقر على **Create Credentials** > **OAuth client ID**
3. اختر **Web application**
4. املأ التفاصيل:
   - **Name**: WebSecService Google OAuth
   - **Authorized redirect URIs**: 
     - للتطوير: `http://127.0.0.1:8000/oauth/google/callback`
     - للإنتاج: `https://yourdomain.com/oauth/google/callback`

### 1.4 الحصول على بيانات الاعتماد
1. بعد الإنشاء، انسخ **Client ID**
2. انسخ **Client Secret**

---

## الجزء الثاني: إعداد Microsoft OAuth

### 2.1 الوصول إلى Azure Portal
1. اذهب إلى [Azure Portal](https://portal.azure.com)
2. سجل الدخول باستخدام حساب Microsoft الخاص بك
3. انتقل إلى **Azure Active Directory**

### 2.2 تسجيل تطبيق جديد
1. في Azure AD، اذهب إلى **App registrations**
2. انقر على **New registration**
3. املأ تفاصيل التطبيق:
   - **Name**: WebSecService
   - **Supported account types**: "Accounts in any organizational directory and personal Microsoft accounts"
   - **Redirect URI**: 
     - Type: Web
     - URI: `http://127.0.0.1:8000/oauth/microsoft/callback` (للتطوير)

### 2.3 الحصول على بيانات الاعتماد
1. بعد التسجيل، اذهب إلى تبويب **Overview**
2. انسخ **Application (client) ID**
3. اذهب إلى تبويب **Certificates & secrets**
4. انقر على **New client secret**
5. أضف وصفًا وحدد انتهاء الصلاحية
6. انسخ **Value** (client secret) - **مهم**: احفظ هذا فورًا لأنه لن يظهر مرة أخرى

---

## الجزء الثالث: إعداد تطبيق Laravel

### 3.1 متغيرات البيئة
أضف التالي إلى ملف `.env` الخاص بك:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/oauth/google/callback

# Microsoft OAuth Configuration
MICROSOFT_CLIENT_ID=your_microsoft_client_id_here
MICROSOFT_CLIENT_SECRET=your_microsoft_client_secret_here
MICROSOFT_REDIRECT_URI=http://127.0.0.1:8000/oauth/microsoft/callback
```

**للإنتاج:**
```env
GOOGLE_REDIRECT_URI=https://yourdomain.com/oauth/google/callback
MICROSOFT_REDIRECT_URI=https://yourdomain.com/oauth/microsoft/callback
```

### 3.2 تحديث URIs في الخدمات الخارجية
إذا كنت تنشر في الإنتاج، أضف callback URLs الإنتاج:

**Google Cloud Console:**
1. اذهب إلى **APIs & Services** > **Credentials**
2. اختر OAuth client ID الخاص بك
3. أضف: `https://yourdomain.com/oauth/google/callback`

**Azure Portal:**
1. اذهب إلى **App registrations** > تطبيقك
2. اذهب إلى **Authentication**
3. أضف: `https://yourdomain.com/oauth/microsoft/callback`

---

## الميزات المُنفذة

### 4.1 تدفق المصادقة الموحد
- **مستخدمون جدد**: إنشاء حساب تلقائي مع بيانات الملف الشخصي
- **مستخدمون موجودون**: تحديث معلومات الملف الشخصي وتسجيل الدخول
- **ربط الحسابات**: ربط حسابات OAuth بحسابات موجودة عبر البريد الإلكتروني
- **تعيين الأدوار**: المستخدمون الجدد يحصلون على دور "مستخدم" تلقائيًا

### 4.2 ميزات الأمان
- **إنشاء أسماء مستخدمين فريدة**: إنشاء أسماء مستخدمين فريدة تلقائيًا
- **التحقق من البريد الإلكتروني**: المستخدمون المصادق عليهم عبر OAuth يتم التحقق منهم تلقائيًا
- **كلمات مرور عشوائية**: تعيين كلمات مرور آمنة عشوائية لمستخدمي OAuth
- **تتبع آخر تسجيل دخول**: تحديث طابع زمني لآخر تسجيل دخول

### 4.3 واجهة المستخدم
- **أزرار تسجيل دخول حديثة**: أزرار نظيفة مع علامات تجارية لـ Google و Microsoft
- **دعم ثنائي اللغة**: واجهة عربية مع تدفق OAuth إنجليزي
- **معالجة الأخطاء**: رسائل خطأ شاملة ونسخ احتياطية

---

## مخطط قاعدة البيانات

تمت إضافة الحقول التالية إلى جدول `users`:
- `google_id` (string, nullable): معرف مستخدم Google
- `microsoft_id` (string, nullable): معرف مستخدم Microsoft
- `avatar` (string, nullable): رابط صورة الملف الشخصي

---

## المسارات المضافة

```php
// OAuth Authentication (Google & Microsoft)
Route::get('/oauth/{provider}', [OAuthController::class, 'redirectToProvider'])->name('oauth.redirect');
Route::get('/oauth/{provider}/callback', [OAuthController::class, 'handleProviderCallback'])->name('oauth.callback');
Route::post('/oauth/logout', [OAuthController::class, 'logout'])->name('oauth.logout');
```

---

## الملفات المُنشأة/المُعدلة

### ملفات جديدة:
- `app/Http/Controllers/OAuthController.php` - كنترولر OAuth موحد
- `database/migrations/2025_08_11_001619_add_microsoft_oauth_fields_to_users_table.php` - ترحيل Microsoft
- `database/migrations/2025_08_11_004025_add_google_oauth_fields_to_users_table.php` - ترحيل Google
- `OAUTH_INTEGRATION_GUIDE.md` - هذه الوثائق

### ملفات معدلة:
- `config/services.php` - إضافة إعدادات Google و Microsoft OAuth
- `app/Models/User.php` - إضافة حقول OAuth إلى fillable
- `resources/views/auth/login.blade.php` - إضافة أزرار Google و Microsoft
- `routes/web.php` - إضافة مسارات OAuth موحدة
- `.env.example` - إضافة متغيرات بيئة OAuth

---

## اختبار التكامل

### 5.1 بدء خادم التطوير
```bash
php artisan serve
```

### 5.2 اختبار تسجيل الدخول
1. انتقل إلى `http://127.0.0.1:8000/login`
2. انقر على "تسجيل الدخول باستخدام Google" أو "تسجيل الدخول باستخدام Microsoft"
3. يجب إعادة توجيهك إلى صفحة تسجيل دخول Google/Microsoft
4. بعد المصادقة الناجحة، ستتم إعادة توجيهك إلى تطبيقك

---

## استكشاف الأخطاء وإصلاحها

### مشاكل شائعة:

1. **"Invalid redirect URI"**
   - تأكد من أن redirect URI في Google/Azure يطابق تمامًا رابط تطبيقك
   - تحقق من الشرطات المائلة النهائية و HTTP مقابل HTTPS

2. **"Client secret expired"**
   - أنشئ client secret جديد في Azure Portal
   - حدث `MICROSOFT_CLIENT_SECRET` في ملف `.env`

3. **"Application not found"**
   - تحقق من أن `CLIENT_ID` صحيح
   - تأكد من أن التطبيق مسجل بشكل صحيح

4. **"OAuth provider not configured"**
   - تحقق من أن متغيرات البيئة محددة في `.env`
   - امسح cache التكوين: `php artisan config:clear`

### التصحيح:
- تحقق من سجلات Laravel: `storage/logs/laravel.log`
- فعل وضع التصحيح: `APP_DEBUG=true` في `.env`
- استخدم `dd()` أو `Log::info()` لتصحيح تدفق OAuth

---

## اعتبارات الأمان

1. **متغيرات البيئة**: لا تلتزم بملف `.env` في نظام التحكم في الإصدار
2. **HTTPS في الإنتاج**: استخدم دائمًا HTTPS لـ OAuth callbacks في الإنتاج
3. **Client Secret**: احفظ client secret بأمان ودوره بانتظام
4. **أذونات المستخدم**: راجع وعين الأدوار المناسبة لمستخدمي OAuth

---

## الخطوات التالية

1. **إعداد Google Cloud Project** مع بيانات الاعتماد الخاصة بك
2. **إعداد Azure App Registration** مع بيانات الاعتماد الخاصة بك
3. **إضافة بيانات الاعتماد** إلى ملف `.env`
4. **اختبار التكامل** في بيئة التطوير
5. **النشر في الإنتاج** مع إعداد HTTPS المناسب
6. **تعيين الأدوار** لمستخدمي OAuth حسب الحاجة

---

## الدعم

للمشاكل المتعلقة بـ:
- **إعداد Google**: تحقق من وثائق Google Cloud Platform
- **إعداد Azure**: تحقق من وثائق Microsoft Azure
- **Laravel Socialite**: تحقق من وثائق Laravel Socialite
- **مشاكل التطبيق**: تحقق من سجلات التطبيق ورسائل الخطأ

تكامل Google و Microsoft OAuth جاهز الآن للاستخدام بالكامل!
