@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-cog me-2"></i>
                        اختبار إعدادات OAuth
                    </h4>
                </div>
                <div class="card-body">
                    <h5>حالة إعدادات OAuth:</h5>
                    
                    <!-- Google OAuth Status -->
                    <div class="mb-4">
                        <h6><i class="fab fa-google text-danger me-2"></i>Google OAuth:</h6>
                        @if(config('services.google.client_id') && config('services.google.client_secret'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                مُعد بشكل صحيح
                                <br><small>Redirect URI: {{ config('services.google.redirect') }}</small>
                            </div>
                            <a href="{{ route('oauth.redirect', 'google') }}" class="btn btn-outline-danger">
                                <i class="fab fa-google me-2"></i>
                                اختبار تسجيل الدخول بـ Google
                            </a>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                غير مُعد - يرجى إضافة GOOGLE_CLIENT_ID و GOOGLE_CLIENT_SECRET في ملف .env
                            </div>
                        @endif
                    </div>

                    <!-- Microsoft OAuth Status -->
                    <div class="mb-4">
                        <h6><i class="fab fa-microsoft text-primary me-2"></i>Microsoft OAuth:</h6>
                        @if(config('services.microsoft.client_id') && config('services.microsoft.client_secret'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                مُعد بشكل صحيح
                                <br><small>Redirect URI: {{ config('services.microsoft.redirect') }}</small>
                            </div>
                            <a href="{{ route('oauth.redirect', 'microsoft') }}" class="btn btn-outline-primary">
                                <i class="fab fa-microsoft me-2"></i>
                                اختبار تسجيل الدخول بـ Microsoft
                            </a>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                غير مُعد - يرجى إضافة MICROSOFT_CLIENT_ID و MICROSOFT_CLIENT_SECRET في ملف .env
                            </div>
                        @endif
                    </div>

                    <!-- Instructions -->
                    <div class="mt-4">
                        <h6>تعليمات الإعداد:</h6>
                        <div class="alert alert-info">
                            <h6>لإعداد Google OAuth:</h6>
                            <ol>
                                <li>اذهب إلى <a href="https://console.cloud.google.com" target="_blank">Google Cloud Console</a></li>
                                <li>أنشئ مشروعًا جديدًا أو اختر مشروعًا موجودًا</li>
                                <li>فعل Google+ API أو People API</li>
                                <li>أنشئ OAuth 2.0 credentials</li>
                                <li>أضف Redirect URI: <code>{{ url('/oauth/google/callback') }}</code></li>
                                <li>أضف Client ID و Client Secret إلى ملف .env</li>
                            </ol>
                        </div>

                        <div class="alert alert-primary">
                            <h6>لإعداد Microsoft OAuth:</h6>
                            <ol>
                                <li>اذهب إلى <a href="https://portal.azure.com" target="_blank">Azure Portal</a></li>
                                <li>انتقل إلى Azure Active Directory</li>
                                <li>اذهب إلى App registrations > New registration</li>
                                <li>أضف Redirect URI: <code>{{ url('/oauth/microsoft/callback') }}</code></li>
                                <li>أنشئ Client Secret</li>
                                <li>أضف Client ID و Client Secret إلى ملف .env</li>
                            </ol>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('login') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            العودة إلى تسجيل الدخول
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
