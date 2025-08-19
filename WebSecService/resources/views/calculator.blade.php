@extends('layouts.app')

@section('title', 'الآلة الحاسبة')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>الآلة الحاسبة المتقدمة
                    </h4>
                </div>
                <div class="card-body">
                    <!-- شاشة العرض -->
                    <div class="calculator-display mb-3">
                        <div class="form-control text-end" id="display" readonly>0</div>
                        <div class="form-control text-end text-muted small" id="history" readonly></div>
                    </div>

                    <!-- أزرار الآلة الحاسبة -->
                    <div class="calculator-buttons">
                        <div class="row g-2">
                            <!-- الصف الأول -->
                            <div class="col-3">
                                <button class="btn btn-outline-danger w-100" onclick="clearAll()">C</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-warning w-100" onclick="clearEntry()">CE</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-secondary w-100" onclick="backspace()">⌫</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary w-100" onclick="setOperation('/')">÷</button>
                            </div>

                            <!-- الصف الثاني -->
                            <div class="col-3">
                                <button class="btn btn-light w-100" onclick="appendNumber('7')">7</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-light w-100" onclick="appendNumber('8')">8</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-light w-100" onclick="appendNumber('9')">9</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary w-100" onclick="setOperation('*')">×</button>
                            </div>

                            <!-- الصف الثالث -->
                            <div class="col-3">
                                <button class="btn btn-light w-100" onclick="appendNumber('4')">4</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-light w-100" onclick="appendNumber('5')">5</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-light w-100" onclick="appendNumber('6')">6</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary w-100" onclick="setOperation('-')">−</button>
                            </div>

                            <!-- الصف الرابع -->
                            <div class="col-3">
                                <button class="btn btn-light w-100" onclick="appendNumber('1')">1</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-light w-100" onclick="appendNumber('2')">2</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-light w-100" onclick="appendNumber('3')">3</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary w-100" onclick="setOperation('+')">+</button>
                            </div>

                            <!-- الصف الخامس -->
                            <div class="col-3">
                                <button class="btn btn-light w-100" onclick="appendNumber('0')">0</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-light w-100" onclick="appendDecimal()">.</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-secondary w-100" onclick="toggleSign()">±</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-success w-100" onclick="calculate()">=</button>
                            </div>
                        </div>

                        <!-- أزرار إضافية -->
                        <div class="row g-2 mt-2">
                            <div class="col-3">
                                <button class="btn btn-outline-info w-100" onclick="calculateSquare()">x²</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-info w-100" onclick="calculateSquareRoot()">√</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-info w-100" onclick="calculatePercentage()">%</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-info w-100" onclick="calculateInverse()">1/x</button>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات الاستخدام -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white text-center">
                                <div class="card-body">
                                    <h6 class="card-title">عدد العمليات</h6>
                                    <h4 id="operationCount">0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white text-center">
                                <div class="card-body">
                                    <h6 class="card-title">آخر نتيجة</h6>
                                    <h4 id="lastResult">0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white text-center">
                                <div class="card-body">
                                    <h6 class="card-title">أكبر رقم</h6>
                                    <h4 id="maxNumber">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- سجل العمليات -->
                    <div class="mt-4">
                        <h6>سجل العمليات</h6>
                        <div class="form-control" id="operationLog" style="height: 100px; overflow-y: auto; font-size: 12px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.calculator-display {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
}

.calculator-display .form-control {
    border: none;
    background: transparent;
    font-size: 24px;
    font-weight: bold;
    height: auto;
    padding: 5px 10px;
}

.calculator-display #history {
    font-size: 14px;
    min-height: 20px;
}

.calculator-buttons .btn {
    height: 50px;
    font-size: 18px;
    font-weight: bold;
}

.calculator-buttons .btn-light {
    background: #ffffff;
    border: 1px solid #dee2e6;
}

.calculator-buttons .btn-light:hover {
    background: #e9ecef;
}

#operationLog {
    background: #f8f9fa;
    font-family: 'Courier New', monospace;
}

.btn:active {
    transform: scale(0.95);
}

@media (max-width: 768px) {
    .calculator-buttons .btn {
        height: 45px;
        font-size: 16px;
    }
}
</style>

<script>
let currentNumber = '0';
let previousNumber = null;
let operation = null;
let shouldResetScreen = false;
let operationCount = 0;
let lastResult = 0;
let maxNumber = 0;
let operationLog = [];

// إضافة رقم للشاشة
function appendNumber(num) {
    if (shouldResetScreen) {
        currentNumber = '';
        shouldResetScreen = false;
    }
    
    if (currentNumber === '0' && num !== '.') {
        currentNumber = num;
    } else {
        currentNumber += num;
    }
    
    updateDisplay();
}

// إضافة نقطة عشرية
function appendDecimal() {
    if (shouldResetScreen) {
        currentNumber = '0.';
        shouldResetScreen = false;
    } else if (!currentNumber.includes('.')) {
        currentNumber += '.';
    }
    updateDisplay();
}

// تعيين العملية
function setOperation(op) {
    if (operation !== null && !shouldResetScreen) {
        calculate();
    }
    
    previousNumber = parseFloat(currentNumber);
    operation = op;
    shouldResetScreen = true;
    
    updateHistory();
}

// الحساب
function calculate() {
    if (operation === null || shouldResetScreen) {
        return;
    }
    
    const current = parseFloat(currentNumber);
    const previous = parseFloat(previousNumber);
    let result;
    
    switch (operation) {
        case '+':
            result = previous + current;
            break;
        case '-':
            result = previous - current;
            break;
        case '*':
            result = previous * current;
            break;
        case '/':
            if (current === 0) {
                alert('لا يمكن القسمة على صفر!');
                return;
            }
            result = previous / current;
            break;
        default:
            return;
    }
    
    // تحديث الإحصائيات
    operationCount++;
    lastResult = result;
    if (result > maxNumber) maxNumber = result;
    
    // إضافة للسجل
    const logEntry = `${previous} ${operation} ${current} = ${result}`;
    operationLog.unshift(logEntry);
    if (operationLog.length > 10) operationLog.pop();
    
    // تحديث العرض
    currentNumber = result.toString();
    operation = null;
    shouldResetScreen = true;
    
    updateDisplay();
    updateHistory();
    updateStats();
    updateLog();
}

// مسح الكل
function clearAll() {
    currentNumber = '0';
    previousNumber = null;
    operation = null;
    shouldResetScreen = false;
    updateDisplay();
    updateHistory();
}

// مسح الإدخال الحالي
function clearEntry() {
    currentNumber = '0';
    shouldResetScreen = false;
    updateDisplay();
}

// حذف آخر رقم
function backspace() {
    if (currentNumber.length === 1) {
        currentNumber = '0';
    } else {
        currentNumber = currentNumber.slice(0, -1);
    }
    updateDisplay();
}

// تغيير الإشارة
function toggleSign() {
    currentNumber = (parseFloat(currentNumber) * -1).toString();
    updateDisplay();
}

// حساب المربع
function calculateSquare() {
    const num = parseFloat(currentNumber);
    const result = num * num;
    currentNumber = result.toString();
    shouldResetScreen = true;
    updateDisplay();
    addToLog(`(${num})² = ${result}`);
}

// حساب الجذر التربيعي
function calculateSquareRoot() {
    const num = parseFloat(currentNumber);
    if (num < 0) {
        alert('لا يمكن حساب الجذر التربيعي لعدد سالب!');
        return;
    }
    const result = Math.sqrt(num);
    currentNumber = result.toString();
    shouldResetScreen = true;
    updateDisplay();
    addToLog(`√(${num}) = ${result}`);
}

// حساب النسبة المئوية
function calculatePercentage() {
    const num = parseFloat(currentNumber);
    const result = num / 100;
    currentNumber = result.toString();
    shouldResetScreen = true;
    updateDisplay();
    addToLog(`${num}% = ${result}`);
}

// حساب المعكوس
function calculateInverse() {
    const num = parseFloat(currentNumber);
    if (num === 0) {
        alert('لا يمكن حساب معكوس الصفر!');
        return;
    }
    const result = 1 / num;
    currentNumber = result.toString();
    shouldResetScreen = true;
    updateDisplay();
    addToLog(`1/(${num}) = ${result}`);
}

// تحديث الشاشة
function updateDisplay() {
    document.getElementById('display').textContent = currentNumber;
}

// تحديث التاريخ
function updateHistory() {
    const history = document.getElementById('history');
    if (previousNumber !== null && operation !== null) {
        history.textContent = `${previousNumber} ${operation}`;
    } else {
        history.textContent = '';
    }
}

// تحديث الإحصائيات
function updateStats() {
    document.getElementById('operationCount').textContent = operationCount;
    document.getElementById('lastResult').textContent = lastResult.toFixed(2);
    document.getElementById('maxNumber').textContent = maxNumber.toFixed(2);
}

// تحديث السجل
function updateLog() {
    const logElement = document.getElementById('operationLog');
    logElement.innerHTML = operationLog.join('<br>');
}

// إضافة للسجل
function addToLog(entry) {
    operationLog.unshift(entry);
    if (operationLog.length > 10) operationLog.pop();
    updateLog();
}

// معالجة المفاتيح
document.addEventListener('keydown', function(event) {
    const key = event.key;
    
    if (key >= '0' && key <= '9') {
        appendNumber(key);
    } else if (key === '.') {
        appendDecimal();
    } else if (key === '+') {
        setOperation('+');
    } else if (key === '-') {
        setOperation('-');
    } else if (key === '*') {
        setOperation('*');
    } else if (key === '/') {
        setOperation('/');
    } else if (key === 'Enter' || key === '=') {
        calculate();
    } else if (key === 'Escape') {
        clearAll();
    } else if (key === 'Backspace') {
        backspace();
    }
});

// تهيئة الآلة الحاسبة
updateDisplay();
updateStats();
</script>
@endsection
