@extends('layouts.app')

@section('title', 'قائمة الطلاب')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-users me-2"></i>قائمة الطلاب
                        </h4>
                        <a href="{{ url('/students/create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-1"></i>إضافة طالب جديد
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="{{ url('/students') }}" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="البحث بالاسم أو البريد الإلكتروني..." 
                                       value="{{ request('search') }}">
                                <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">
                                <input type="hidden" name="age_filter" value="{{ request('age_filter') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('search') || request('status_filter') || request('age_filter'))
                                    <a href="{{ url('/students') }}" class="btn btn-outline-secondary ms-2">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ url('/students') }}" class="d-flex gap-2">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="age_filter" value="{{ request('age_filter') }}">
                                <select name="status_filter" class="form-select" onchange="this.form.submit()">
                                    <option value="">جميع الحالات</option>
                                    <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    <option value="graduated" {{ request('status_filter') == 'graduated' ? 'selected' : '' }}>متخرج</option>
                                </select>
                                <select name="age_filter" class="form-select" onchange="this.form.submit()">
                                    <option value="">جميع الأعمار</option>
                                    <option value="16-20" {{ request('age_filter') == '16-20' ? 'selected' : '' }}>16-20 سنة</option>
                                    <option value="21-25" {{ request('age_filter') == '21-25' ? 'selected' : '' }}>21-25 سنة</option>
                                    <option value="26-30" {{ request('age_filter') == '26-30' ? 'selected' : '' }}>26-30 سنة</option>
                                    <option value="30+" {{ request('age_filter') == '30+' ? 'selected' : '' }}>أكثر من 30 سنة</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">إجمالي الطلاب</h5>
                                    <h3>{{ $students->total() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">طلاب نشطين</h5>
                                    <h3>{{ \App\Models\Student::where('status', 'active')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">متوسط العمر</h5>
                                    <h3>{{ round(\App\Models\Student::avg('age'), 1) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">طلاب متخرجين</h5>
                                    <h3>{{ \App\Models\Student::where('status', 'graduated')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>العمر</th>
                                    <th>الجنس</th>
                                    <th>الحالة</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $student->name }}</strong>
                                                    @if($student->student_id)
                                                        <br><small class="text-muted">ID: {{ $student->student_id }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $student->email }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $student->age }} سنة</span>
                                            <br><small class="text-muted">{{ $student->age_group }}</small>
                                        </td>
                                        <td>
                                            @if($student->gender)
                                                <span class="badge bg-info">{{ $student->gender_text }}</span>
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($student->status == 'active')
                                                <span class="badge bg-success">{{ $student->status_text }}</span>
                                            @elseif($student->status == 'inactive')
                                                <span class="badge bg-warning">{{ $student->status_text }}</span>
                                            @else
                                                <span class="badge bg-primary">{{ $student->status_text }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($student->enrollment_date)
                                                {{ $student->enrollment_date->format('Y-m-d') }}
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ url('/students/' . $student->id . '/edit') }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteStudent({{ $student->id }}, '{{ $student->name }}')"
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
                                            <h5 class="text-muted">لا يوجد طلاب</h5>
                                            <p class="text-muted">قم بإضافة طالب جديد للبدء</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($students->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $students->appends(request()->query())->links() }}
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
                <p>هل أنت متأكد من حذف الطالب: <strong id="studentName"></strong>؟</p>
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
function deleteStudent(id, name) {
    document.getElementById('studentName').textContent = name;
    document.getElementById('deleteForm').action = `/students/${id}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
