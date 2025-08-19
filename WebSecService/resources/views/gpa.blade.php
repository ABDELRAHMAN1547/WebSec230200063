@extends('layouts.app')

@section('title', 'حاسبة المعدل التراكمي')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>حاسبة المعدل التراكمي (GPA)
                    </h4>
                </div>
                <div class="card-body">
                    <!-- معلومات الطالب -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">معلومات الطالب</h6>
                            <div class="mb-3">
                                <label class="form-label">اسم الطالب</label>
                                <input type="text" class="form-control" id="studentName" placeholder="أدخل اسم الطالب">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">رقم الطالب</label>
                                <input type="text" class="form-control" id="studentId" placeholder="أدخل رقم الطالب">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">إعدادات الحساب</h6>
                            <div class="mb-3">
                                <label class="form-label">نظام الدرجات</label>
                                <select class="form-select" id="gradeSystem">
                                    <option value="4.0">نظام 4.0 (A=4.0, B=3.0, ...)</option>
                                    <option value="5.0">نظام 5.0 (A=5.0, B=4.0, ...)</option>
                                    <option value="100">نظام 100 (A=90-100, B=80-89, ...)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الفصل الدراسي</label>
                                <select class="form-select" id="semester">
                                    <option value="fall">الفصل الأول (الخريف)</option>
                                    <option value="spring">الفصل الثاني (الربيع)</option>
                                    <option value="summer">الفصل الصيفي</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- كتالوج المقررات -->
                    <div class="mb-4">
                        <h6 class="text-muted">كتالوج المقررات المتاحة</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>رمز المقرر</th>
                                        <th>اسم المقرر</th>
                                        <th>الوحدات</th>
                                        <th>الإجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($courses as $course)
                                        <tr>
                                            <td><span class="badge bg-secondary">{{ $course['code'] }}</span></td>
                                            <td>{{ $course['title'] }}</td>
                                            <td class="text-center">{{ $course['credit'] }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="addCourse('{{ $course['code'] }}', '{{ $course['title'] }}', {{ $course['credit'] }})">
                                                    <i class="fas fa-plus me-1"></i>إضافة
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- المقررات المختارة -->
                    <div class="mb-4">
                        <h6 class="text-muted">المقررات المختارة</h6>
                        <div id="selectedCourses">
                            <p class="text-muted text-center">لم يتم اختيار أي مقررات بعد</p>
                        </div>
                    </div>

                    <!-- أزرار التحكم -->
                    <div class="d-flex gap-2 mb-4">
                        <button class="btn btn-primary" onclick="calculateGPA()">
                            <i class="fas fa-calculator me-1"></i>حساب المعدل
                        </button>
                        <button class="btn btn-secondary" onclick="clearAll()">
                            <i class="fas fa-trash me-1"></i>مسح الكل
                        </button>
                        <button class="btn btn-success" onclick="saveTranscript()">
                            <i class="fas fa-save me-1"></i>حفظ النتيجة
                        </button>
                        <button class="btn btn-info" onclick="loadSample()">
                            <i class="fas fa-download me-1"></i>تحميل مثال
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- النتائج والإحصائيات -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>النتائج والإحصائيات
                    </h5>
                </div>
                <div class="card-body">
                    <!-- المعدل التراكمي -->
                    <div class="text-center mb-4">
                        <h6 class="text-muted">المعدل التراكمي</h6>
                        <div class="gpa-display">
                            <span id="gpaResult">0.00</span>
                        </div>
                        <div class="gpa-status mt-2">
                            <span id="gpaStatus" class="badge bg-secondary">غير محدد</span>
                        </div>
                    </div>

                    <!-- إحصائيات سريعة -->
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-title">إجمالي الوحدات</h6>
                                    <h4 id="totalCredits">0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h6 class="card-title">إجمالي النقاط</h6>
                                    <h4 id="totalPoints">0.00</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h6 class="card-title">عدد المقررات</h6>
                                    <h4 id="courseCount">0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h6 class="card-title">المقررات الراسبة</h6>
                                    <h4 id="failedCourses">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- توزيع الدرجات -->
                    <div class="mt-4">
                        <h6 class="text-muted">توزيع الدرجات</h6>
                        <div id="gradeDistribution">
                            <div class="grade-bar mb-2">
                                <span class="grade-label">A (ممتاز)</span>
                                <div class="progress">
                                    <div class="progress-bar bg-success" id="gradeA" style="width: 0%">0</div>
                                </div>
                            </div>
                            <div class="grade-bar mb-2">
                                <span class="grade-label">B (جيد جداً)</span>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" id="gradeB" style="width: 0%">0</div>
                                </div>
                            </div>
                            <div class="grade-bar mb-2">
                                <span class="grade-label">C (جيد)</span>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" id="gradeC" style="width: 0%">0</div>
                                </div>
                            </div>
                            <div class="grade-bar mb-2">
                                <span class="grade-label">D (مقبول)</span>
                                <div class="progress">
                                    <div class="progress-bar bg-info" id="gradeD" style="width: 0%">0</div>
                                </div>
                            </div>
                            <div class="grade-bar mb-2">
                                <span class="grade-label">F (راسب)</span>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" id="gradeF" style="width: 0%">0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- نصائح وتحذيرات -->
                    <div class="mt-4">
                        <h6 class="text-muted">نصائح وتحذيرات</h6>
                        <div id="advice" class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            أضف المقررات واختر الدرجات لحساب المعدل التراكمي
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.gpa-display {
    font-size: 48px;
    font-weight: bold;
    color: #007bff;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.grade-bar {
    display: flex;
    align-items: center;
    gap: 10px;
}

.grade-label {
    min-width: 80px;
    font-size: 12px;
}

.progress {
    flex: 1;
    height: 20px;
}

.course-item {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 10px;
    background: #f8f9fa;
}

.course-item:hover {
    background: #e9ecef;
}

.grade-select {
    min-width: 80px;
}

.credit-badge {
    font-size: 10px;
}

@media (max-width: 768px) {
    .gpa-display {
        font-size: 36px;
    }
}
</style>

<script>
let selectedCourses = [];
let gradeSystem = 4.0;

// إضافة مقرر
function addCourse(code, title, credit) {
    const course = {
        id: Date.now(),
        code: code,
        title: title,
        credit: credit,
        grade: ''
    };
    
    selectedCourses.push(course);
    updateCoursesDisplay();
    updateStats();
}

// إزالة مقرر
function removeCourse(id) {
    selectedCourses = selectedCourses.filter(course => course.id !== id);
    updateCoursesDisplay();
    updateStats();
}

// تحديث درجة مقرر
function updateGrade(id, grade) {
    const course = selectedCourses.find(c => c.id === id);
    if (course) {
        course.grade = grade;
        updateStats();
    }
}

// تحديث عرض المقررات
function updateCoursesDisplay() {
    const container = document.getElementById('selectedCourses');
    
    if (selectedCourses.length === 0) {
        container.innerHTML = '<p class="text-muted text-center">لم يتم اختيار أي مقررات بعد</p>';
        return;
    }
    
    container.innerHTML = selectedCourses.map(course => `
        <div class="course-item">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>${course.code}</strong> - ${course.title}
                    <span class="badge bg-secondary credit-badge ms-2">${course.credit} وحدات</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <select class="form-select grade-select" onchange="updateGrade(${course.id}, this.value)">
                        <option value="">اختر الدرجة</option>
                        <option value="A" ${course.grade === 'A' ? 'selected' : ''}>A (ممتاز)</option>
                        <option value="A-" ${course.grade === 'A-' ? 'selected' : ''}>A- (ممتاز)</option>
                        <option value="B+" ${course.grade === 'B+' ? 'selected' : ''}>B+ (جيد جداً)</option>
                        <option value="B" ${course.grade === 'B' ? 'selected' : ''}>B (جيد جداً)</option>
                        <option value="B-" ${course.grade === 'B-' ? 'selected' : ''}>B- (جيد جداً)</option>
                        <option value="C+" ${course.grade === 'C+' ? 'selected' : ''}>C+ (جيد)</option>
                        <option value="C" ${course.grade === 'C' ? 'selected' : ''}>C (جيد)</option>
                        <option value="C-" ${course.grade === 'C-' ? 'selected' : ''}>C- (جيد)</option>
                        <option value="D+" ${course.grade === 'D+' ? 'selected' : ''}>D+ (مقبول)</option>
                        <option value="D" ${course.grade === 'D' ? 'selected' : ''}>D (مقبول)</option>
                        <option value="F" ${course.grade === 'F' ? 'selected' : ''}>F (راسب)</option>
                    </select>
                    <button class="btn btn-sm btn-outline-danger" onclick="removeCourse(${course.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// حساب المعدل التراكمي
function calculateGPA() {
    if (selectedCourses.length === 0) {
        showAlert('يرجى إضافة مقررات أولاً', 'warning');
        return;
    }
    
    const coursesWithGrades = selectedCourses.filter(course => course.grade !== '');
    
    if (coursesWithGrades.length === 0) {
        showAlert('يرجى اختيار درجات لجميع المقررات', 'warning');
        return;
    }
    
    const gradePoints = getGradePoints();
    let totalPoints = 0;
    let totalCredits = 0;
    let gradeCounts = { A: 0, B: 0, C: 0, D: 0, F: 0 };
    
    coursesWithGrades.forEach(course => {
        const points = gradePoints[course.grade] || 0;
        totalPoints += points * course.credit;
        totalCredits += course.credit;
        
        // حساب توزيع الدرجات
        const baseGrade = course.grade.charAt(0);
        gradeCounts[baseGrade]++;
    });
    
    const gpa = totalCredits > 0 ? totalPoints / totalCredits : 0;
    
    // تحديث النتائج
    document.getElementById('gpaResult').textContent = gpa.toFixed(2);
    document.getElementById('totalCredits').textContent = totalCredits;
    document.getElementById('totalPoints').textContent = totalPoints.toFixed(2);
    document.getElementById('courseCount').textContent = coursesWithGrades.length;
    
    // تحديث حالة المعدل
    updateGPAStatus(gpa);
    
    // تحديث توزيع الدرجات
    updateGradeDistribution(gradeCounts, coursesWithGrades.length);
    
    // تحديث النصائح
    updateAdvice(gpa, gradeCounts);
    
    showAlert(`تم حساب المعدل التراكمي: ${gpa.toFixed(2)}`, 'success');
}

// الحصول على نقاط الدرجات
function getGradePoints() {
    if (gradeSystem == 4.0) {
        return {
            'A': 4.0, 'A-': 3.7, 'B+': 3.3, 'B': 3.0, 'B-': 2.7,
            'C+': 2.3, 'C': 2.0, 'C-': 1.7, 'D+': 1.3, 'D': 1.0, 'F': 0.0
        };
    } else if (gradeSystem == 5.0) {
        return {
            'A': 5.0, 'A-': 4.7, 'B+': 4.3, 'B': 4.0, 'B-': 3.7,
            'C+': 3.3, 'C': 3.0, 'C-': 2.7, 'D+': 2.3, 'D': 2.0, 'F': 0.0
        };
    }
    return {};
}

// تحديث حالة المعدل
function updateGPAStatus(gpa) {
    const statusElement = document.getElementById('gpaStatus');
    let status, color;
    
    if (gpa >= 3.5) {
        status = 'ممتاز';
        color = 'bg-success';
    } else if (gpa >= 3.0) {
        status = 'جيد جداً';
        color = 'bg-primary';
    } else if (gpa >= 2.0) {
        status = 'جيد';
        color = 'bg-warning';
    } else {
        status = 'ضعيف';
        color = 'bg-danger';
    }
    
    statusElement.textContent = status;
    statusElement.className = `badge ${color}`;
}

// تحديث توزيع الدرجات
function updateGradeDistribution(gradeCounts, totalCourses) {
    const grades = ['A', 'B', 'C', 'D', 'F'];
    
    grades.forEach(grade => {
        const count = gradeCounts[grade] || 0;
        const percentage = totalCourses > 0 ? (count / totalCourses) * 100 : 0;
        const bar = document.getElementById(`grade${grade}`);
        bar.style.width = `${percentage}%`;
        bar.textContent = count;
    });
}

// تحديث النصائح
function updateAdvice(gpa, gradeCounts) {
    const adviceElement = document.getElementById('advice');
    let advice = '';
    let alertClass = 'alert-info';
    
    if (gpa >= 3.5) {
        advice = 'معدل ممتاز! يمكنك التقدم للدراسات العليا.';
        alertClass = 'alert-success';
    } else if (gpa >= 3.0) {
        advice = 'معدل جيد جداً. استمر في العمل الجاد.';
        alertClass = 'alert-primary';
    } else if (gpa >= 2.0) {
        advice = 'معدل مقبول. ركز على تحسين أدائك.';
        alertClass = 'alert-warning';
    } else {
        advice = 'معدل ضعيف. تحتاج لتحسين كبير في الأداء.';
        alertClass = 'alert-danger';
    }
    
    if (gradeCounts.F > 0) {
        advice += ' لديك مقررات راسبة تحتاج لإعادة دراسة.';
    }
    
    adviceElement.className = `alert ${alertClass}`;
    adviceElement.innerHTML = `<i class="fas fa-info-circle me-2"></i>${advice}`;
}

// تحديث الإحصائيات
function updateStats() {
    const coursesWithGrades = selectedCourses.filter(course => course.grade !== '');
    const failedCourses = coursesWithGrades.filter(course => course.grade === 'F').length;
    
    document.getElementById('courseCount').textContent = selectedCourses.length;
    document.getElementById('failedCourses').textContent = failedCourses;
}

// مسح الكل
function clearAll() {
    selectedCourses = [];
    updateCoursesDisplay();
    updateStats();
    document.getElementById('gpaResult').textContent = '0.00';
    document.getElementById('totalCredits').textContent = '0';
    document.getElementById('totalPoints').textContent = '0.00';
    document.getElementById('gpaStatus').textContent = 'غير محدد';
    document.getElementById('gpaStatus').className = 'badge bg-secondary';
    
    // إعادة تعيين توزيع الدرجات
    ['A', 'B', 'C', 'D', 'F'].forEach(grade => {
        const bar = document.getElementById(`grade${grade}`);
        bar.style.width = '0%';
        bar.textContent = '0';
    });
    
    document.getElementById('advice').className = 'alert alert-info';
    document.getElementById('advice').innerHTML = '<i class="fas fa-info-circle me-2"></i>أضف المقررات واختر الدرجات لحساب المعدل التراكمي';
}

// تحميل مثال
function loadSample() {
    selectedCourses = [
        { id: 1, code: 'CS101', title: 'مقدمة في البرمجة', credit: 3, grade: 'A' },
        { id: 2, code: 'MATH101', title: 'الرياضيات', credit: 4, grade: 'B+' },
        { id: 3, code: 'ENG101', title: 'اللغة الإنجليزية', credit: 3, grade: 'A-' },
        { id: 4, code: 'PHY101', title: 'الفيزياء', credit: 3, grade: 'B' }
    ];
    
    updateCoursesDisplay();
    calculateGPA();
    showAlert('تم تحميل مثال بنجاح', 'success');
}

// حفظ النتيجة
function saveTranscript() {
    const studentName = document.getElementById('studentName').value || 'طالب';
    const studentId = document.getElementById('studentId').value || 'غير محدد';
    const gpa = document.getElementById('gpaResult').textContent;
    
    const data = {
        studentName: studentName,
        studentId: studentId,
        gpa: gpa,
        courses: selectedCourses,
        date: new Date().toLocaleDateString('ar-SA')
    };
    
    // حفظ في localStorage
    localStorage.setItem('gpaTranscript', JSON.stringify(data));
    showAlert('تم حفظ النتيجة بنجاح', 'success');
}

// إظهار تنبيه
function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'warning' ? 'alert-warning' : 'alert-danger';
    
    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}

// مراقبة تغيير نظام الدرجات
document.getElementById('gradeSystem').addEventListener('change', function() {
    gradeSystem = parseFloat(this.value);
    if (selectedCourses.length > 0) {
        calculateGPA();
    }
});

// تهيئة الصفحة
updateCoursesDisplay();
updateStats();
</script>
@endsection
