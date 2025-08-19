@extends('layouts.app')

@section('title', 'تعديل المستخدم')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>تعديل المستخدم: {{ $user->name }}
                    </h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/users/' . $user->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم الكامل *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">اسم المستخدم *</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username', $user->username) }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">كلمة المرور الجديدة (اتركها فارغة إذا لم ترد تغييرها)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">الدور *</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">اختر الدور</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>مدير</option>
                                    <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>معلم</option>
                                    <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>طالب</option>
                                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>موظف</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">الحالة</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status">
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>معلق</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="security_question" class="form-label">السؤال الأمني</label>
                            <select class="form-select @error('security_question') is-invalid @enderror" 
                                    id="security_question" name="security_question" required>
                                <option value="">اختر سؤالاً أمنياً</option>
                                <option value="ما هو اسم أول مدرسة التحقت بها؟" {{ old('security_question', $user->security_question) == 'ما هو اسم أول مدرسة التحقت بها؟' ? 'selected' : '' }}>
                                    ما هو اسم أول مدرسة التحقت بها؟
                                </option>
                                <option value="ما هو اسم أول حي سكنت فيه؟" {{ old('security_question', $user->security_question) == 'ما هو اسم أول حي سكنت فيه؟' ? 'selected' : '' }}>
                                    ما هو اسم أول حي سكنت فيه؟
                                </option>
                                <option value="ما هو اسم أول حيوان أليف امتلكته؟" {{ old('security_question', $user->security_question) == 'ما هو اسم أول حيوان أليف امتلكته؟' ? 'selected' : '' }}>
                                    ما هو اسم أول حيوان أليف امتلكته؟
                                </option>
                                <option value="ما هو اسم أول مدينة زرتها؟" {{ old('security_question', $user->security_question) == 'ما هو اسم أول مدينة زرتها؟' ? 'selected' : '' }}>
                                    ما هو اسم أول مدينة زرتها؟
                                </option>
                                <option value="ما هو اسم أول كتاب قرأته؟" {{ old('security_question', $user->security_question) == 'ما هو اسم أول كتاب قرأته؟' ? 'selected' : '' }}>
                                    ما هو اسم أول كتاب قرأته؟
                                </option>
                            </select>
                            @error('security_question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="security_answer" class="form-label">الإجابة على السؤال الأمني</label>
                            <input type="text" class="form-control @error('security_answer') is-invalid @enderror" 
                                   id="security_answer" name="security_answer" value="{{ old('security_answer', $user->security_answer) }}" required>
                            @error('security_answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(auth()->user()->canManageUsers())
                        <div class="mb-3">
                            <label for="admin" class="form-label">نوع الحساب</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="admin" name="admin" value="1" 
                                       {{ old('admin', $user->admin) ? 'checked' : '' }}>
                                <label class="form-check-label" for="admin">
                                    حساب مدير النظام
                                </label>
                            </div>
                            <div class="form-text">المديرون يمكنهم إدارة جميع المستخدمين</div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/users') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-1"></i>عودة
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 