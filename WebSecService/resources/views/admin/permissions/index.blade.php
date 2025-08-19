@extends('layouts.app')

@section('title', 'إدارة الصلاحيات')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 text-primary">
                                <i class="fas fa-shield-alt me-2"></i>
                                إدارة الصلاحيات والأدوار
                            </h2>
                            <p class="text-muted mb-0">إدارة شاملة لصلاحيات النظام والمستخدمين</p>
                        </div>
                        @if($user->hasRole('super_admin') || $user->hasPermission('roles.manage'))
                        <div class="btn-group">
                            @if($user->hasRole('super_admin') || $user->hasPermission('roles.create'))
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                                <i class="fas fa-plus me-2"></i>إنشاء دور جديد
                            </button>
                            @endif
                            @if($user->hasRole('super_admin') || $user->hasPermission('permissions.create'))
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createPermissionModal">
                                <i class="fas fa-plus me-2"></i>إنشاء صلاحية جديدة
                            </button>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Levels Overview -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-danger shadow-sm h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-crown me-2"></i>سوبر أدمن</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>الوصول إلى جميع أجزاء النظام</li>
                        <li><i class="fas fa-check text-success me-2"></i>إدارة المستخدمين بالكامل</li>
                        <li><i class="fas fa-check text-success me-2"></i>إدارة الأدوار والصلاحيات</li>
                        <li><i class="fas fa-check text-success me-2"></i>عرض وتعديل إعدادات النظام</li>
                        <li><i class="fas fa-check text-success me-2"></i>الوصول إلى البيانات الحساسة</li>
                        <li><i class="fas fa-shield-alt text-danger me-2"></i>لا يمكن حذفه أو تعطيله</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>أدمن</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>إدارة بعض المستخدمين</li>
                        <li><i class="fas fa-check text-success me-2"></i>إدارة محتوى الموقع</li>
                        <li><i class="fas fa-check text-success me-2"></i>الوصول إلى لوحة التحكم</li>
                        <li><i class="fas fa-check text-success me-2"></i>تنفيذ المهام الإدارية</li>
                        <li><i class="fas fa-times text-danger me-2"></i>لا يمكن تعديل السوبر أدمن</li>
                        <li><i class="fas fa-times text-danger me-2"></i>لا يستطيع إنشاء صلاحيات جديدة</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>مستخدم عادي</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>إنشاء حساب وتسجيل الدخول</li>
                        <li><i class="fas fa-check text-success me-2"></i>تعديل البيانات الشخصية</li>
                        <li><i class="fas fa-check text-success me-2"></i>استخدام وظائف النظام العامة</li>
                        <li><i class="fas fa-check text-success me-2"></i>عرض البيانات المسموح بها</li>
                        <li><i class="fas fa-times text-danger me-2"></i>لا يملك صلاحيات إدارية</li>
                        <li><i class="fas fa-times text-danger me-2"></i>لا يستطيع التحكم في مستخدمين آخرين</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="permissionsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">
                <i class="fas fa-users-cog me-2"></i>الأدوار
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab">
                <i class="fas fa-key me-2"></i>الصلاحيات
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                <i class="fas fa-users me-2"></i>المستخدمين
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="permissionsTabsContent">
        <!-- Roles Tab -->
        <div class="tab-pane fade show active" id="roles" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">إدارة الأدوار</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>اسم الدور</th>
                                    <th>الوصف</th>
                                    <th>عدد الصلاحيات</th>
                                    <th>عدد المستخدمين</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($role->name === 'super_admin')
                                                <i class="fas fa-crown text-danger me-2"></i>
                                            @elseif($role->name === 'admin')
                                                <i class="fas fa-user-shield text-warning me-2"></i>
                                            @else
                                                <i class="fas fa-user text-info me-2"></i>
                                            @endif
                                            <strong>{{ $role->display_name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $role->description ?: 'لا يوجد وصف' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $role->permissions->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $role->users->count() }}</span>
                                    </td>
                                    <td>
                                        @if($role->is_active)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewRole({{ $role->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($user->hasRole('super_admin') || ($user->hasPermission('roles.manage') && $role->name !== 'super_admin'))
                                            <button class="btn btn-outline-warning" onclick="editRole({{ $role->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif
                                            @if($user->hasRole('super_admin') && $role->name !== 'super_admin' && $role->users->count() === 0)
                                            <button class="btn btn-outline-danger" onclick="deleteRole({{ $role->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Tab -->
        <div class="tab-pane fade" id="permissions" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">إدارة الصلاحيات</h5>
                </div>
                <div class="card-body">
                    @foreach($permissions as $module => $modulePermissions)
                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2">
                            <i class="fas fa-folder me-2"></i>{{ ucfirst($module) }}
                        </h6>
                        <div class="row">
                            @foreach($modulePermissions as $permission)
                            <div class="col-md-6 col-lg-4 mb-2">
                                <div class="card border-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $permission->display_name }}</h6>
                                                <small class="text-muted">{{ $permission->name }}</small>
                                            </div>
                                            @if($user->hasRole('super_admin'))
                                            <button class="btn btn-outline-danger btn-sm" onclick="deletePermission({{ $permission->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Users Tab -->
        <div class="tab-pane fade" id="users" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">إدارة أدوار المستخدمين</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>المستخدم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الأدوار الحالية</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $userItem)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ $userItem->initials }}
                                            </div>
                                            <div>
                                                <strong>{{ $userItem->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $userItem->username }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $userItem->email }}</td>
                                    <td>
                                        @foreach($userItem->roles as $role)
                                        <span class="badge 
                                            @if($role->name === 'super_admin') bg-danger
                                            @elseif($role->name === 'admin') bg-warning
                                            @else bg-info
                                            @endif me-1">
                                            {{ $role->display_name }}
                                        </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($userItem->status === 'active') bg-success
                                            @elseif($userItem->status === 'suspended') bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ $userItem->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->hasPermission('users.manage') && (!$userItem->hasRole('super_admin') || $user->hasRole('super_admin')))
                                        <button class="btn btn-outline-primary btn-sm" onclick="manageUserRoles({{ $userItem->id }})">
                                            <i class="fas fa-user-cog me-1"></i>إدارة الأدوار
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals will be included here -->
@include('admin.permissions.modals')

@endsection

@section('scripts')
<!-- jQuery (required for permissions.js) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Permissions Management JavaScript -->
<script src="{{ asset('js/permissions.js') }}"></script>
@endsection

@section('styles')
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
    font-weight: bold;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 2px solid transparent;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    background: none;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}
</style>
@endsection
