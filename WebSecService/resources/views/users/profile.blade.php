@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        الملف الشخصي - {{ $user->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">المعلومات الأساسية</h6>
                            <div class="mb-3">
                                <strong>الاسم الكامل:</strong>
                                <span class="ms-2">{{ $user->name }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>اسم المستخدم:</strong>
                                <span class="ms-2">{{ $user->username }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>البريد الإلكتروني:</strong>
                                <span class="ms-2">{{ $user->email }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>رقم الهاتف:</strong>
                                <span class="ms-2">{{ $user->phone ?: 'غير محدد' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">معلومات الحساب</h6>
                            <div class="mb-3">
                                <strong>الدور:</strong>
                                <span class="badge bg-info ms-2">{{ $user->role_text }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>الحالة:</strong>
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'inactive' ? 'warning' : 'danger') }} ms-2">
                                    {{ $user->status_text }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <strong>نوع الحساب:</strong>
                                <span class="badge bg-{{ $user->admin ? 'danger' : 'secondary' }} ms-2">
                                    {{ $user->admin_text }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <strong>تاريخ التسجيل:</strong>
                                <span class="ms-2">{{ $user->created_at->format('Y-m-d') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($user->address)
                    <div class="mt-3">
                        <h6 class="text-muted">العنوان</h6>
                        <p class="mb-0">{{ $user->address }}</p>
                    </div>
                    @endif

                    @if($user->security_question)
                    <div class="mt-3">
                        <h6 class="text-muted">السؤال الأمني</h6>
                        <p class="mb-0">{{ $user->security_question }}</p>
                    </div>
                    @endif

                    <div class="mt-4">
                        @if(auth()->user()->canManageUsers() || auth()->user()->id === $user->id)
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary me-2">
                                <i class="fas fa-edit me-1"></i>تعديل المعلومات
                            </a>
                        @endif
                        
                        @if(auth()->user()->canManageUsers())
                            <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="fas fa-key me-1"></i>تغيير كلمة المرور
                            </button>
                        @endif
                        
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        إحصائيات سريعة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="h4 text-primary mb-0">{{ $user->created_at->diffForHumans() }}</div>
                        <small class="text-muted">عضو منذ</small>
                    </div>
                    
                    @if($user->isAdmin())
                    <div class="alert alert-warning">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>مدير النظام</strong><br>
                        يمكنه إدارة جميع المستخدمين
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تغيير كلمة المرور -->
@if(auth()->user()->canManageUsers())
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تغيير كلمة المرور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.change-password', $user->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">تأكيد كلمة المرور</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تغيير كلمة المرور</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
