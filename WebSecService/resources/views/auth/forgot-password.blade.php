@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-key me-2"></i>
                        نسيان كلمة المرور
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

                    @if(!session('security_question'))
                        <!-- Step 1: Enter Email -->
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-search me-1"></i>
                                البحث عن الحساب
                            </button>
                        </form>
                    @else
                        <!-- Step 2: Answer Security Question -->
                        <form method="POST" action="{{ route('password.security') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('email') }}">
                            
                            <div class="alert alert-info">
                                <i class="fas fa-question-circle me-2"></i>
                                <strong>السؤال الأمني:</strong><br>
                                {{ session('security_question') }}
                            </div>

                            <div class="mb-3">
                                <label for="security_answer" class="form-label">الإجابة</label>
                                <input type="text" class="form-control @error('security_answer') is-invalid @enderror" 
                                       id="security_answer" name="security_answer" required>
                                @error('security_answer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-check me-1"></i>
                                التحقق من الإجابة
                            </button>
                        </form>
                    @endif

                    <div class="mt-3 text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>
                            العودة لصفحة تسجيل الدخول
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
