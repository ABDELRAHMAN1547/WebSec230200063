<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تشخيص OAuth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            direction: rtl;
        }
        .debug-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        .info-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #007bff;
        }
        .test-btn {
            background: linear-gradient(45deg, #4285f4, #34a853);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .test-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(66, 133, 244, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="debug-card p-5">
                    <h2 class="text-center mb-4">🔍 تشخيص Google OAuth</h2>
                    
                    <div class="info-item">
                        <strong>🌐 URL الحالي:</strong>
                        <code>{{ request()->getSchemeAndHttpHost() }}</code>
                    </div>
                    
                    <div class="info-item">
                        <strong>🔗 Google Redirect URI المُعد:</strong>
                        <code>{{ config('services.google.redirect') }}</code>
                    </div>
                    
                    <div class="info-item">
                        <strong>🎯 Redirect URI الديناميكي:</strong>
                        <code>{{ request()->getSchemeAndHttpHost() }}/oauth/google/callback</code>
                    </div>
                    
                    <div class="info-item">
                        <strong>🔑 Google Client ID:</strong>
                        <code>{{ config('services.google.client_id') ? 'مُعد ✅' : 'غير مُعد ❌' }}</code>
                    </div>
                    
                    <div class="info-item">
                        <strong>🔐 Google Client Secret:</strong>
                        <code>{{ config('services.google.client_secret') ? 'مُعد ✅' : 'غير مُعد ❌' }}</code>
                    </div>
                    
                    <div class="text-center mt-4">
                        <h5>اختبار تسجيل الدخول:</h5>
                        <a href="{{ route('oauth.redirect', 'google') }}" class="btn test-btn">
                            🚀 اختبار Google OAuth
                        </a>
                    </div>
                    
                    <div class="mt-4 p-3 bg-warning rounded">
                        <h6>📋 تأكد من إضافة هذه الروابط في Google Console:</h6>
                        <ul class="mb-0">
                            <li><code>http://localhost:8000/oauth/google/callback</code></li>
                            <li><code>http://127.0.0.1:8000/oauth/google/callback</code></li>
                        </ul>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="btn btn-secondary">
                            ← العودة لصفحة تسجيل الدخول
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
