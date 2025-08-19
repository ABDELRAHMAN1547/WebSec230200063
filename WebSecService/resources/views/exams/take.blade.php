@extends('layouts.app')

@section('title', $exam->title)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Exam Header -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-0">
                                <i class="fas fa-file-alt me-2"></i>
                                {{ $exam->title }}
                            </h4>
                            <small>{{ $exam->description }}</small>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex flex-column align-items-end">
                                <div class="badge bg-light text-dark mb-1">
                                    <i class="fas fa-clock me-1"></i>
                                    <span id="timer">--:--</span>
                                </div>
                                <div class="badge bg-light text-dark">
                                    <i class="fas fa-star me-1"></i>
                                    {{ $exam->total_points }} نقطة
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exam Form -->
            <form action="{{ route('exams.submit', $exam->id) }}" method="POST" id="examForm">
                @csrf
                <div class="card shadow">
                    <div class="card-body">
                        @foreach($exam->questions as $index => $question)
                            <div class="question-block mb-4 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="mb-0">
                                        <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                        {{ $question->question_text }}
                                    </h5>
                                    <span class="badge bg-secondary">
                                        {{ $question->points }} نقطة
                                    </span>
                                </div>
                                
                                <div class="options">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               id="q{{ $question->id }}_a" value="A" required>
                                        <label class="form-check-label" for="q{{ $question->id }}_a">
                                            <strong>A)</strong> {{ $question->option_a }}
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               id="q{{ $question->id }}_b" value="B" required>
                                        <label class="form-check-label" for="q{{ $question->id }}_b">
                                            <strong>B)</strong> {{ $question->option_b }}
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               id="q{{ $question->id }}_c" value="C" required>
                                        <label class="form-check-label" for="q{{ $question->id }}_c">
                                            <strong>C)</strong> {{ $question->option_c }}
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               id="q{{ $question->id }}_d" value="D" required>
                                        <label class="form-check-label" for="q{{ $question->id }}_d">
                                            <strong>D)</strong> {{ $question->option_d }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $question->category_text }} | 
                                        <i class="fas fa-signal me-1"></i>
                                        {{ $question->difficulty_text }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                تأكد من الإجابة على جميع الأسئلة قبل الإرسال
                            </div>
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                إرسال الامتحان
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Timer Script -->
<script>
let timeLeft = {{ $exam->duration_minutes * 60 }};
const timerElement = document.getElementById('timer');
const submitBtn = document.getElementById('submitBtn');
const examForm = document.getElementById('examForm');

function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    if (timeLeft <= 0) {
        alert('انتهى وقت الامتحان! سيتم إرسال الإجابات تلقائياً.');
        examForm.submit();
        return;
    }
    
    timeLeft--;
}

// Update timer every second
const timerInterval = setInterval(updateTimer, 1000);

// Initial timer update
updateTimer();

// Confirm before leaving page
window.addEventListener('beforeunload', function(e) {
    if (timeLeft > 0) {
        e.preventDefault();
        e.returnValue = 'هل أنت متأكد من مغادرة الصفحة؟ قد تفقد تقدمك في الامتحان.';
    }
});

// Confirm before form submission
submitBtn.addEventListener('click', function(e) {
    const unansweredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
    const totalQuestions = {{ $exam->questions->count() }};
    
    if (unansweredQuestions < totalQuestions) {
        if (!confirm(`لديك ${totalQuestions - unansweredQuestions} أسئلة لم تجب عليها. هل تريد المتابعة؟`)) {
            e.preventDefault();
            return;
        }
    }
    
    if (confirm('هل أنت متأكد من إرسال الامتحان؟ لا يمكنك التراجع بعد الإرسال.')) {
        clearInterval(timerInterval);
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الإرسال...';
    } else {
        e.preventDefault();
    }
});
</script>
@endsection
