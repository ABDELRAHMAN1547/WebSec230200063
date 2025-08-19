@extends('layouts.app')

@section('title', 'قائمة المستخدمين')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-users me-2"></i>قائمة المستخدمين
                        </h4>
                        <a href="{{ url('/users/create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-1"></i>إضافة مستخدم جديد
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="{{ url('/users') }}" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="البحث بالاسم أو البريد الإلكتروني أو اسم المستخدم..." 
                                       value="{{ request('search') }}">
                                <input type="hidden" name="role_filter" value="{{ request('role_filter') }}">
                                <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('search') || request('role_filter') || request('status_filter'))
                                    <a href="{{ url('/users') }}" class="btn btn-outline-secondary ms-2">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ url('/users') }}" class="d-flex gap-2">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">
                                <select name="role_filter" class="form-select" onchange="this.form.submit()">
                                    <option value="">جميع الأدوار</option>
                                    <option value="admin" {{ request('role_filter') == 'admin' ? 'selected' : '' }}>مدير</option>
                                    <option value="teacher" {{ request('role_filter') == 'teacher' ? 'selected' : '' }}>معلم</option>
                                    <option value="student" {{ request('role_filter') == 'student' ? 'selected' : '' }}>طالب</option>
                                    <option value="staff" {{ request('role_filter') == 'staff' ? 'selected' : '' }}>موظف</option>
                                </select>
                                <input type="hidden" name="role_filter" value="{{ request('role_filter') }}">
                                <select name="status_filter" class="form-select" onchange="this.form.submit()">
                                    <option value="">جميع الحالات</option>
                                    <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    <option value="suspended" {{ request('status_filter') == 'suspended' ? 'selected' : '' }}>معلق</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">إجمالي المستخدمين</h5>
                                    <h3>{{ \App\Models\User::count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">مستخدمين نشطين</h5>
                                    <h3>{{ \App\Models\User::where('status', 'active')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">المدرسين</h5>
                                    <h3>{{ \App\Models\User::where('role', 'teacher')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">الطلاب</h5>
                                    <h3>{{ \App\Models\User::where('role', 'student')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>الاسم</th>
                                    <th>اسم المستخدم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الدور</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                                                        @if(auth()->check() && (auth()->user()->canManageUsers() || auth()->user()->id === $user->id))
                                        <a href="{{ route('users.profile', $user->id) }}" class="text-decoration-none">
                                            <strong>{{ $user->name }}</strong>
                                        </a>
                                    @else
                                        <strong>{{ $user->name }}</strong>
                                    @endif
                                                    @if($user->phone)
                                                        <br><small class="text-muted">{{ $user->phone }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $user->username }}</code>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'teacher' ? 'warning' : 'info') }}">
                                                {{ $user->role_text }}
                                            </span>
                                            @if($user->admin)
                                                <span class="badge bg-dark ms-1">مدير</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->status == 'active')
                                                <span class="badge bg-success">{{ $user->status_text }}</span>
                                            @elseif($user->status == 'inactive')
                                                <span class="badge bg-warning">{{ $user->status_text }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $user->status_text }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $user->created_at->format('Y-m-d') }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ url('/users/' . $user->id . '/edit') }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                                                        title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">لا يوجد مستخدمين</h5>
                                            <p class="text-muted">قم بإضافة مستخدم جديد للبدء</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف المستخدم: <strong id="userName"></strong>؟</p>
                <p class="text-danger"><small>لا يمكن التراجع عن هذا الإجراء.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 35px;
    height: 35px;
    font-size: 14px;
    font-weight: bold;
}
</style>

<script>
function deleteUser(id, name) {
    document.getElementById('userName').textContent = name;
    document.getElementById('deleteForm').action = `/users/${id}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection 