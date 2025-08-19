@extends('layouts.app')

@section('title', 'الامتحانات المتاحة')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        الامتحانات المتاحة
                    </h4>
                </div>
                <div class="card-body">
                    @if($exams->count() > 0)
                        <div class="row">
                            @foreach($exams as $exam)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-primary">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0 text-primary">{{ $exam->title }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $exam->description ?: 'لا يوجد وصف' }}</p>
                                            
                                            <div class="row text-muted small mb-3">
                                                <div class="col-6">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $exam->duration_minutes }} دقيقة
                                                </div>
                                                <div class="col-6">
                                                    <i class="fas fa-star me-1"></i>
                                                    {{ $exam->total_points }} نقطة
                                                </div>
                                            </div>
                                            
                                            <div class="row text-muted small mb-3">
                                                <div class="col-6">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    النجاح: {{ $exam->passing_score }}%
                                                </div>
                                                <div class="col-6">
                                                    <i class="fas fa-question-circle me-1"></i>
                                                    {{ $exam->questions->count() }} سؤال
                                                </div>
                                            </div>
                                            
                                            <div class="d-grid">
                                                <a href="{{ route('exams.start', $exam->id) }}" 
                                                   class="btn btn-primary">
                                                    <i class="fas fa-play me-2"></i>
                                                    بدء الامتحان
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-footer text-muted small">
                                            <div class="row">
                                                <div class="col-6">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    يبدأ: {{ $exam->start_time->format('Y-m-d H:i') }}
                                                </div>
                                                <div class="col-6">
                                                    <i class="fas fa-calendar-times me-1"></i>
                                                    ينتهي: {{ $exam->end_time->format('Y-m-d H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد امتحانات متاحة حالياً</h5>
                            <p class="text-muted">يرجى العودة لاحقاً أو التواصل مع الإدارة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
