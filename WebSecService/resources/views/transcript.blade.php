@extends('layouts.app')

@section('title', 'كشف الدرجات')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-certificate me-2"></i>كشف الدرجات الأكاديمي
                        </h4>
                        <div class="text-end">
                            <small>تاريخ الإصدار: {{ now()->format('Y-m-d') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- معلومات الطالب -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">معلومات الطالب</h6>
                            <p class="mb-1"><strong>الاسم:</strong> أحمد محمد علي</p>
                            <p class="mb-1"><strong>رقم الطالب:</strong> 2023001</p>
                            <p class="mb-1"><strong>القسم:</strong> علوم الحاسب</p>
                            <p class="mb-0"><strong>المستوى:</strong> السنة الثالثة</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">معلومات الجامعة</h6>
                            <p class="mb-1"><strong>اسم الجامعة:</strong> جامعة الملك سعود</p>
                            <p class="mb-1"><strong>الكلية:</strong> كلية علوم الحاسب</p>
                            <p class="mb-1"><strong>الفصل الدراسي:</strong> الفصل الأول 2024</p>
                            <p class="mb-0"><strong>المعدل التراكمي:</strong> <span class="badge bg-success">3.75</span></p>
                        </div>
                    </div>

                    <!-- جدول الدرجات -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>رمز المقرر</th>
                                    <th>اسم المقرر</th>
                                    <th>الوحدات</th>
                                    <th>الدرجة</th>
                                    <th>النقاط</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $totalCredits = 0;
                                    $totalPoints = 0;
                                    $gradePoints = [
                                        'A' => 4.0, 'A-' => 3.7, 'B+' => 3.3, 'B' => 3.0,
                                        'B-' => 2.7, 'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7,
                                        'D+' => 1.3, 'D' => 1.0, 'F' => 0.0
                                    ];
                                @endphp
                                @foreach($transcript as $index => $course)
                                    @php 
                                        $credits = 3; // افتراض أن كل مقرر 3 وحدات
                                        $points = $credits * ($gradePoints[$course['grade']] ?? 0);
                                        $totalCredits += $credits;
                                        $totalPoints += $points;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ 'CS' . (300 + $index) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="course-icon me-2">
                                                    @switch(strtolower($course['course']))
                                                        @case('math')
                                                            <i class="fas fa-square-root-alt text-primary"></i>
                                                            @break
                                                        @case('physics')
                                                            <i class="fas fa-atom text-info"></i>
                                                            @break
                                                        @case('cs')
                                                            <i class="fas fa-laptop-code text-success"></i>
                                                            @break
                                                        @default
                                                            <i class="fas fa-book text-warning"></i>
                                                    @endswitch
                                                </div>
                                                {{ $course['course'] }}
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $credits }}</td>
                                        <td class="text-center">
                                            @php
                                                $gradeClass = match($course['grade']) {
                                                    'A', 'A-' => 'bg-success',
                                                    'B+', 'B', 'B-' => 'bg-primary',
                                                    'C+', 'C', 'C-' => 'bg-warning',
                                                    'D+', 'D' => 'bg-danger',
                                                    'F' => 'bg-dark',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $gradeClass }}">{{ $course['grade'] }}</span>
                                        </td>
                                        <td class="text-center">{{ number_format($points, 1) }}</td>
                                        <td class="text-center">
                                            @if($course['grade'] == 'F')
                                                <span class="badge bg-danger">راسب</span>
                                            @else
                                                <span class="badge bg-success">ناجح</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>المجموع:</strong></td>
                                    <td class="text-center"><strong>{{ $totalCredits }}</strong></td>
                                    <td class="text-center"><strong>{{ number_format($totalPoints, 1) }}</strong></td>
                                    <td></td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="3" class="text-end"><strong>المعدل الفصلي:</strong></td>
                                    <td colspan="3" class="text-center">
                                        <strong>{{ $totalCredits > 0 ? number_format($totalPoints / $totalCredits, 2) : '0.00' }}</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- إحصائيات إضافية -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white text-center">
                                <div class="card-body">
                                    <h5 class="card-title">المقررات المكتملة</h5>
                                    <h3>{{ count($transcript) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white text-center">
                                <div class="card-body">
                                    <h5 class="card-title">المقررات الناجحة</h5>
                                    <h3>{{ count(array_filter($transcript, fn($c) => $c['grade'] != 'F')) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white text-center">
                                <div class="card-body">
                                    <h5 class="card-title">الوحدات المكتملة</h5>
                                    <h3>{{ $totalCredits }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white text-center">
                                <div class="card-body">
                                    <h5 class="card-title">المعدل التراكمي</h5>
                                    <h3>{{ $totalCredits > 0 ? number_format($totalPoints / $totalCredits, 2) : '0.00' }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ملاحظات -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">ملاحظات مهمة</h6>
                                    <ul class="mb-0">
                                        <li>المعدل التراكمي محسوب على أساس نظام النقاط 4.0</li>
                                        <li>الدرجة A تعادل 4.0 نقاط، والدرجة F تعادل 0.0 نقاط</li>
                                        <li>يجب الحصول على معدل تراكمي 2.0 على الأقل للتخرج</li>
                                        <li>يمكن إعادة دراسة المقررات الراسبة لتحسين المعدل</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-primary me-2" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>طباعة الكشف
                        </button>
                        <button class="btn btn-success me-2" onclick="downloadTranscript()">
                            <i class="fas fa-download me-1"></i>تحميل PDF
                        </button>
                        <a href="{{ url('/') }}" class="btn btn-secondary">
                            <i class="fas fa-home me-1"></i>العودة للرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, .footer {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}

.course-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 50%;
}
</style>

<script>
function downloadTranscript() {
    alert('سيتم إضافة ميزة تحميل PDF قريباً!');
}
</script>
@endsection
