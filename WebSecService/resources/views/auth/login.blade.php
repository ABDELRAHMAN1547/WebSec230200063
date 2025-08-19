@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        تسجيل الدخول
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                تذكرني
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            تسجيل الدخول
                        </button>
                    </form>

                    <!-- OAuth Login Options -->
                    <div class="mt-3">
                        <div class="text-center mb-3">
                            <small class="text-muted">أو تسجيل الدخول باستخدام</small>
                        </div>
                        
                        <!-- Google OAuth -->
                        <a href="{{ route('oauth.redirect', 'google') }}" class="btn btn-outline-danger w-100 mb-2">
                            <i class="fab fa-google me-2"></i>
                            تسجيل الدخول باستخدام Google
                        </a>
                        
                        <!-- Microsoft OAuth -->
                        <a href="{{ route('oauth.redirect', 'microsoft') }}" class="btn btn-outline-primary w-100">
                            <i class="fab fa-microsoft me-2"></i>
                            تسجيل الدخول باستخدام Microsoft
                        </a>
                    </div>

                    <div class="mt-3 text-center">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            <i class="fas fa-key me-1"></i>
                            نسيت كلمة المرور؟
                        </a>
                    </div>

                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            لا تملك حساباً؟ 
                            <a href="{{ route('users.create') }}" class="text-decoration-none">إنشاء حساب جديد</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
