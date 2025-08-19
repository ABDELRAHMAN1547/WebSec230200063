@extends('layouts.app')

@section('title', 'إدارة الأسئلة')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        إدارة الأسئلة
                    </h4>
                    <a href="{{ route('questions.create') }}" class="btn btn-light">
                        <i class="fas fa-plus me-2"></i>
                        إضافة سؤال جديد
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchInput" placeholder="البحث في الأسئلة...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="categoryFilter">
                                <option value="">جميع الفئات</option>
                                <option value="general">عام</option>
                                <option value="programming">برمجة</option>
                                <option value="database">قواعد البيانات</option>
                                <option value="networking">شبكات</option>
                                <option value="security">أمن المعلومات</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="difficultyFilter">
                                <option value="">جميع المستويات</option>
                                <option value="easy">سهل</option>
                                <option value="medium">متوسط</option>
                                <option value="hard">صعب</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary w-100" onclick="applyFilters()">
                                <i class="fas fa-filter me-1"></i>
                                تطبيق
                            </button>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary" onclick="clearFilters()">
                                    <i class="fas fa-times me-1"></i>
                                    مسح
                                </button>
                                <button class="btn btn-outline-success" onclick="exportQuestions()">
                                    <i class="fas fa-download me-1"></i>
                                    تصدير
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Questions Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>السؤال</th>
                                    <th>الفئة</th>
                                    <th>المستوى</th>
                                    <th>النقاط</th>
                                    <th>الإجابة الصحيحة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questions as $question)
                                    <tr>
                                        <td>{{ $question->id }}</td>
                                        <td>
                                            <div class="question-text">
                                                {{ Str::limit($question->question_text, 80) }}
                                            </div>
                                            <small class="text-muted">
                                                <strong>أ)</strong> {{ Str::limit($question->option_a, 30) }} |
                                                <strong>ب)</strong> {{ Str::limit($question->option_b, 30) }} |
                                                <strong>ج)</strong> {{ Str::limit($question->option_c, 30) }} |
                                                <strong>د)</strong> {{ Str::limit($question->option_d, 30) }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $question->category_text }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $question->difficulty === 'easy' ? 'success' : ($question->difficulty === 'medium' ? 'warning' : 'danger') }}">
                                                {{ $question->difficulty_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $question->points }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $question->correct_answer }}</span>
                                        </td>
                                        <td>{{ $question->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('questions.edit', $question->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteQuestion({{ $question->id }})"
                                                        title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">لا توجد أسئلة</p>
                                            <a href="{{ route('questions.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>
                                                إضافة أول سؤال
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($questions->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $questions->links() }}
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
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذا السؤال؟</p>
                <p class="text-muted small">لا يمكن التراجع عن هذا الإجراء.</p>
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

<script>
function deleteQuestion(id) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = `/questions/${id}`;
    modal.show();
}

function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const category = document.getElementById('categoryFilter').value;
    const difficulty = document.getElementById('difficultyFilter').value;
    
    let url = new URL(window.location);
    if (search) url.searchParams.set('search', search);
    if (category) url.searchParams.set('category_filter', category);
    if (difficulty) url.searchParams.set('difficulty_filter', difficulty);
    
    window.location.href = url.toString();
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('difficultyFilter').value = '';
    window.location.href = window.location.pathname;
}

function exportQuestions() {
    // Implementation for exporting questions
    alert('ميزة التصدير قيد التطوير');
}

// Auto-apply filters on Enter key
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});
</script>
@endsection
