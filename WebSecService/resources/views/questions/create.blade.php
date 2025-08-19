@extends('layouts.app')

@section('title', 'إضافة سؤال جديد')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>
                        إضافة سؤال جديد
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

                    <form action="{{ route('questions.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Question Text -->
                            <div class="col-12 mb-3">
                                <label for="question_text" class="form-label">
                                    <i class="fas fa-question-circle me-1"></i>
                                    نص السؤال <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('question_text') is-invalid @enderror" 
                                          id="question_text" 
                                          name="question_text" 
                                          rows="4" 
                                          placeholder="اكتب نص السؤال هنا..."
                                          required>{{ old('question_text') }}</textarea>
                                @error('question_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Options -->
                            <div class="col-md-6 mb-3">
                                <label for="option_a" class="form-label">
                                    <i class="fas fa-circle me-1"></i>
                                    الخيار أ <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('option_a') is-invalid @enderror" 
                                       id="option_a" 
                                       name="option_a" 
                                       value="{{ old('option_a') }}"
                                       placeholder="الخيار الأول"
                                       required>
                                @error('option_a')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="option_b" class="form-label">
                                    <i class="fas fa-circle me-1"></i>
                                    الخيار ب <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('option_b') is-invalid @enderror" 
                                       id="option_b" 
                                       name="option_b" 
                                       value="{{ old('option_b') }}"
                                       placeholder="الخيار الثاني"
                                       required>
                                @error('option_b')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="option_c" class="form-label">
                                    <i class="fas fa-circle me-1"></i>
                                    الخيار ج <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('option_c') is-invalid @enderror" 
                                       id="option_c" 
                                       name="option_c" 
                                       value="{{ old('option_c') }}"
                                       placeholder="الخيار الثالث"
                                       required>
                                @error('option_c')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="option_d" class="form-label">
                                    <i class="fas fa-circle me-1"></i>
                                    الخيار د <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('option_d') is-invalid @enderror" 
                                       id="option_d" 
                                       name="option_d" 
                                       value="{{ old('option_d') }}"
                                       placeholder="الخيار الرابع"
                                       required>
                                @error('option_d')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Correct Answer -->
                            <div class="col-md-6 mb-3">
                                <label for="correct_answer" class="form-label">
                                    <i class="fas fa-check-circle me-1"></i>
                                    الإجابة الصحيحة <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('correct_answer') is-invalid @enderror" 
                                        id="correct_answer" 
                                        name="correct_answer" 
                                        required>
                                    <option value="">اختر الإجابة الصحيحة</option>
                                    <option value="A" {{ old('correct_answer') == 'A' ? 'selected' : '' }}>أ</option>
                                    <option value="B" {{ old('correct_answer') == 'B' ? 'selected' : '' }}>ب</option>
                                    <option value="C" {{ old('correct_answer') == 'C' ? 'selected' : '' }}>ج</option>
                                    <option value="D" {{ old('correct_answer') == 'D' ? 'selected' : '' }}>د</option>
                                </select>
                                @error('correct_answer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">
                                    <i class="fas fa-tag me-1"></i>
                                    الفئة <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" 
                                        name="category" 
                                        required>
                                    <option value="">اختر الفئة</option>
                                    <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>عام</option>
                                    <option value="programming" {{ old('category') == 'programming' ? 'selected' : '' }}>برمجة</option>
                                    <option value="database" {{ old('category') == 'database' ? 'selected' : '' }}>قواعد البيانات</option>
                                    <option value="networking" {{ old('category') == 'networking' ? 'selected' : '' }}>شبكات</option>
                                    <option value="security" {{ old('category') == 'security' ? 'selected' : '' }}>أمن المعلومات</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Difficulty -->
                            <div class="col-md-6 mb-3">
                                <label for="difficulty" class="form-label">
                                    <i class="fas fa-signal me-1"></i>
                                    مستوى الصعوبة <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('difficulty') is-invalid @enderror" 
                                        id="difficulty" 
                                        name="difficulty" 
                                        required>
                                    <option value="">اختر المستوى</option>
                                    <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>سهل</option>
                                    <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>متوسط</option>
                                    <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>صعب</option>
                                </select>
                                @error('difficulty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Points -->
                            <div class="col-md-6 mb-3">
                                <label for="points" class="form-label">
                                    <i class="fas fa-star me-1"></i>
                                    النقاط <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('points') is-invalid @enderror" 
                                       id="points" 
                                       name="points" 
                                       value="{{ old('points', 1) }}"
                                       min="1" 
                                       max="10" 
                                       required>
                                @error('points')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">اختر من 1 إلى 10 نقاط</div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="mt-4">
                            <h5><i class="fas fa-eye me-2"></i>معاينة السؤال</h5>
                            <div class="border rounded p-3 bg-light" id="questionPreview">
                                <p class="text-muted">ستظهر معاينة السؤال هنا بعد ملء الحقول</p>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('questions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                العودة للقائمة
                            </a>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-info" onclick="previewQuestion()">
                                    <i class="fas fa-eye me-2"></i>
                                    معاينة
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ السؤال
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewQuestion() {
    const questionText = document.getElementById('question_text').value;
    const optionA = document.getElementById('option_a').value;
    const optionB = document.getElementById('option_b').value;
    const optionC = document.getElementById('option_c').value;
    const optionD = document.getElementById('option_d').value;
    const correctAnswer = document.getElementById('correct_answer').value;
    const category = document.getElementById('category').value;
    const difficulty = document.getElementById('difficulty').value;
    const points = document.getElementById('points').value;

    if (!questionText || !optionA || !optionB || !optionC || !optionD || !correctAnswer) {
        alert('يرجى ملء جميع الحقول المطلوبة أولاً');
        return;
    }

    const preview = document.getElementById('questionPreview');
    preview.innerHTML = `
        <div class="mb-3">
            <h6 class="fw-bold">${questionText}</h6>
            <div class="ms-3">
                <div class="mb-2">
                    <span class="badge bg-${correctAnswer === 'A' ? 'success' : 'secondary'} me-2">أ</span>
                    ${optionA}
                    ${correctAnswer === 'A' ? '<i class="fas fa-check-circle text-success ms-2"></i>' : ''}
                </div>
                <div class="mb-2">
                    <span class="badge bg-${correctAnswer === 'B' ? 'success' : 'secondary'} me-2">ب</span>
                    ${optionB}
                    ${correctAnswer === 'B' ? '<i class="fas fa-check-circle text-success ms-2"></i>' : ''}
                </div>
                <div class="mb-2">
                    <span class="badge bg-${correctAnswer === 'C' ? 'success' : 'secondary'} me-2">ج</span>
                    ${optionC}
                    ${correctAnswer === 'C' ? '<i class="fas fa-check-circle text-success ms-2"></i>' : ''}
                </div>
                <div class="mb-2">
                    <span class="badge bg-${correctAnswer === 'D' ? 'success' : 'secondary'} me-2">د</span>
                    ${optionD}
                    ${correctAnswer === 'D' ? '<i class="fas fa-check-circle text-success ms-2"></i>' : ''}
                </div>
            </div>
            <div class="mt-3">
                <span class="badge bg-info me-2">${getCategoryText(category)}</span>
                <span class="badge bg-${getDifficultyColor(difficulty)} me-2">${getDifficultyText(difficulty)}</span>
                <span class="badge bg-secondary">${points} نقطة</span>
            </div>
        </div>
    `;
}

function getCategoryText(category) {
    const categories = {
        'general': 'عام',
        'programming': 'برمجة',
        'database': 'قواعد البيانات',
        'networking': 'شبكات',
        'security': 'أمن المعلومات'
    };
    return categories[category] || category;
}

function getDifficultyText(difficulty) {
    const difficulties = {
        'easy': 'سهل',
        'medium': 'متوسط',
        'hard': 'صعب'
    };
    return difficulties[difficulty] || difficulty;
}

function getDifficultyColor(difficulty) {
    const colors = {
        'easy': 'success',
        'medium': 'warning',
        'hard': 'danger'
    };
    return colors[difficulty] || 'secondary';
}

// Auto-preview when correct answer changes
document.getElementById('correct_answer').addEventListener('change', function() {
    if (document.getElementById('question_text').value) {
        previewQuestion();
    }
});
</script>
@endsection
