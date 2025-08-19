@extends('layouts.app')

@section('title', 'قائمة الدرجات')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>قائمة الدرجات
                        </h4>
                        <a href="{{ url('/grades/create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-1"></i>إضافة درجة جديدة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <form method="GET" action="{{ url('/grades') }}" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="البحث بالطالب أو المادة..." 
                                       value="{{ request('search') }}">
                                <input type="hidden" name="term_filter" value="{{ request('term_filter') }}">
                                <input type="hidden" name="student_filter" value="{{ request('student_filter') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('search') || request('term_filter') || request('student_filter'))
                                    <a href="{{ url('/grades') }}" class="btn btn-outline-secondary ms-2">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                        <div class="col-md-8">
                            <form method="GET" action="{{ url('/grades') }}" class="d-flex gap-2">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="student_filter" value="{{ request('student_filter') }}">
                                <select name="term_filter" class="form-select" onchange="this.form.submit()">
                                    <option value="">جميع الفصول الدراسية</option>
                                    @foreach($terms as $term)
                                        <option value="{{ $term }}" {{ request('term_filter') == $term ? 'selected' : '' }}>
                                            {{ $term }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="term_filter" value="{{ request('term_filter') }}">
                                <select name="student_filter" class="form-select" onchange="this.form.submit()">
                                    <option value="">جميع الطلاب</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ request('student_filter') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">إجمالي الدرجات</h5>
                                    <h3>{{ \App\Models\Grade::count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">متوسط GPA</h5>
                                    <h3>{{ number_format(\App\Models\Grade::avg('gpa'), 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">إجمالي الساعات</h5>
                                    <h3>{{ \App\Models\Grade::sum('credit_hours') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">الطلاب النشطين</h5>
                                    <h3>{{ \App\Models\Grade::select('student_id')->distinct()->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grades Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>الطالب</th>
                                    <th>المادة</th>
                                    <th>كود المادة</th>
                                    <th>الساعات المعتمدة</th>
                                    <th>الفصل الدراسي</th>
                                    <th>الدرجة</th>
                                    <th>الدرجة الحرفية</th>
                                    <th>النقاط</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grades as $grade)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ strtoupper(substr($grade->student->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $grade->student->name }}</strong>
                                                    <br><small class="text-muted">{{ $grade->student->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $grade->course_name }}</td>
                                        <td><code>{{ $grade->course_code }}</code></td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $grade->credit_hours }} ساعة</span>
                                        </td>
                                        <td>{{ $grade->term }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ number_format($grade->grade, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $grade->grade_color }}">
                                                {{ $grade->letter_grade }} ({{ $grade->letter_grade_text }})
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ number_format($grade->points, 2) }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ url('/grades/' . $grade->id . '/edit') }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteGrade({{ $grade->id }}, '{{ $grade->course_name }}')"
                                                        title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">لا توجد درجات</h5>
                                            <p class="text-muted">قم بإضافة درجة جديدة للبدء</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($grades->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $grades->appends(request()->query())->links() }}
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
                <p>هل أنت متأكد من حذف الدرجة: <strong id="gradeName"></strong>؟</p>
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
function deleteGrade(id, name) {
    document.getElementById('gradeName').textContent = name;
    document.getElementById('deleteForm').action = `/grades/${id}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection 