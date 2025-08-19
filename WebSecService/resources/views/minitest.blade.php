@extends('layouts.app')

@section('title', 'فاتورة السوبر ماركت')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>فاتورة السوبر ماركت
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">معلومات الفاتورة</h6>
                            <p class="mb-1"><strong>رقم الفاتورة:</strong> #{{ rand(1000, 9999) }}</p>
                            <p class="mb-1"><strong>التاريخ:</strong> {{ now()->format('Y-m-d') }}</p>
                            <p class="mb-0"><strong>الوقت:</strong> {{ now()->format('H:i') }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">معلومات المتجر</h6>
                            <p class="mb-1"><strong>اسم المتجر:</strong> سوبر ماركت المدينة</p>
                            <p class="mb-1"><strong>العنوان:</strong> شارع الملك فهد</p>
                            <p class="mb-0"><strong>الهاتف:</strong> 966-11-1234567</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th>السعر الوحدة</th>
                                    <th>الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($bill as $index => $item)
                                    @php 
                                        $subtotal = $item['qty'] * $item['price'];
                                        $total += $subtotal;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="product-icon me-2">
                                                    @switch(strtolower($item['item']))
                                                        @case('apples')
                                                            <i class="fas fa-apple-alt text-success"></i>
                                                            @break
                                                        @case('milk')
                                                            <i class="fas fa-wine-bottle text-info"></i>
                                                            @break
                                                        @case('bread')
                                                            <i class="fas fa-bread-slice text-warning"></i>
                                                            @break
                                                        @default
                                                            <i class="fas fa-shopping-bag text-primary"></i>
                                                    @endswitch
                                                </div>
                                                {{ $item['item'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $item['qty'] }}</span>
                                        </td>
                                        <td>{{ number_format($item['price'], 2) }} جنيه</td>
                                        <td>{{ number_format($subtotal, 2) }} جنيه</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>المجموع الفرعي:</strong></td>
                                    <td>{{ number_format($total, 2) }} جنيه</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>الضريبة (15%):</strong></td>
                                    <td>{{ number_format($total * 0.15, 2) }} جنيه</td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="4" class="text-end"><strong>الإجمالي النهائي:</strong></td>
                                    <td><strong>{{ number_format($total * 1.15, 2) }} جنيه</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">طرق الدفع المقبولة</h6>
                                    <div class="d-flex gap-2">
                                        <i class="fab fa-cc-visa text-primary"></i>
                                        <i class="fab fa-cc-mastercard text-warning"></i>
                                        <i class="fab fa-cc-paypal text-info"></i>
                                        <i class="fas fa-money-bill-wave text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">شكراً لزيارتكم</h6>
                                    <p class="mb-0 small">نتمنى لكم يوماً سعيداً</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-primary me-2" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>طباعة الفاتورة
                        </button>
                        <button class="btn btn-success me-2" onclick="downloadReceipt()">
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

.product-icon {
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
function downloadReceipt() {
    alert('سيتم إضافة ميزة تحميل PDF قريباً!');
}
</script>
@endsection
