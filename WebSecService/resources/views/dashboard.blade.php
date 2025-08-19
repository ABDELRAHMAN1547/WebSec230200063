@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <h1 class="display-4">
                        <i class="fas fa-tachometer-alt me-3"></i>
                        لوحة التحكم
                    </h1>
                    <p class="lead">نظرة شاملة على بيانات الموقع</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الطلاب
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Student::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                إجمالي المستخدمين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                إجمالي الدرجات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Grade::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                متوسط GPA
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format(\App\Models\Grade::avg('gpa'), 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Students by Age Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">توزيع الطلاب حسب العمر</h6>
                </div>
                <div class="card-body">
                    <canvas id="studentsAgeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Grades Distribution Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">توزيع الدرجات</h6>
                </div>
                <div class="card-body">
                    <canvas id="gradesDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- More Charts Row -->
    <div class="row mb-4">
        <!-- GPA by Term Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">متوسط GPA حسب الفصل الدراسي</h6>
                </div>
                <div class="card-body">
                    <canvas id="gpaByTermChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Students Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">أفضل 5 طلاب</h6>
                </div>
                <div class="card-body">
                    <canvas id="topStudentsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">النشاطات الأخيرة</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>الطالب</th>
                                    <th>المادة</th>
                                    <th>الدرجة</th>
                                    <th>الفصل الدراسي</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Grade::with('student')->latest()->take(10)->get() as $grade)
                                <tr>
                                    <td>{{ $grade->student->name }}</td>
                                    <td>{{ $grade->course_name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $grade->grade_color }}">
                                            {{ $grade->letter_grade }}
                                        </span>
                                    </td>
                                    <td>{{ $grade->term }}</td>
                                    <td>{{ $grade->created_at->format('Y-m-d') }}</td>
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

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df 10%, #224abe 100%);
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Students by Age Chart
    const studentsAgeCtx = document.getElementById('studentsAgeChart').getContext('2d');
    const studentsAgeData = @json(\App\Models\Student::selectRaw('age, COUNT(*) as count')->groupBy('age')->orderBy('age')->get());
    
    new Chart(studentsAgeCtx, {
        type: 'bar',
        data: {
            labels: studentsAgeData.map(item => item.age + ' سنة'),
            datasets: [{
                label: 'عدد الطلاب',
                data: studentsAgeData.map(item => item.count),
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Grades Distribution Chart
    const gradesDistributionCtx = document.getElementById('gradesDistributionChart').getContext('2d');
    const gradesData = @json(\App\Models\Grade::selectRaw('letter_grade, COUNT(*) as count')->groupBy('letter_grade')->get());
    
    new Chart(gradesDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: gradesData.map(item => item.letter_grade),
            datasets: [{
                data: gradesData.map(item => item.count),
                backgroundColor: [
                    '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // GPA by Term Chart
    const gpaByTermCtx = document.getElementById('gpaByTermChart').getContext('2d');
    const gpaByTermData = @json(\App\Models\Grade::selectRaw('term, AVG(gpa) as avg_gpa')->groupBy('term')->orderBy('term')->get());
    
    new Chart(gpaByTermCtx, {
        type: 'line',
        data: {
            labels: gpaByTermData.map(item => item.term),
            datasets: [{
                label: 'متوسط GPA',
                data: gpaByTermData.map(item => parseFloat(item.avg_gpa).toFixed(2)),
                borderColor: 'rgba(78, 115, 223, 1)',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 4
                }
            }
        }
    });

    // Top Students Chart
    const topStudentsCtx = document.getElementById('topStudentsChart').getContext('2d');
    const topStudentsData = @json(\App\Models\Grade::selectRaw('student_id, AVG(gpa) as avg_gpa')
        ->with('student')
        ->groupBy('student_id')
        ->orderBy('avg_gpa', 'desc')
        ->take(5)
        ->get());
    
    new Chart(topStudentsCtx, {
        type: 'horizontalBar',
        data: {
            labels: topStudentsData.map(item => item.student.name),
            datasets: [{
                label: 'متوسط GPA',
                data: topStudentsData.map(item => parseFloat(item.avg_gpa).toFixed(2)),
                backgroundColor: 'rgba(28, 200, 138, 0.8)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    max: 4
                }
            }
        }
    });
});
</script>
@endsection
