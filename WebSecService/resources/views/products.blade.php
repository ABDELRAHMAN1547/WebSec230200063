@extends('layouts.app')

@section('title', 'كتالوج المنتجات')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>كتالوج المنتجات
                        </h4>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2" id="cartCount">0</span>
                            <span class="text-dark">منتجات في السلة</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- فلاتر البحث -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="البحث في المنتجات...">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <select class="form-select" id="sortSelect">
                                    <option value="">ترتيب حسب</option>
                                    <option value="price-low">السعر: من الأقل للأعلى</option>
                                    <option value="price-high">السعر: من الأعلى للأقل</option>
                                    <option value="name">الاسم: أ-ي</option>
                                    <option value="name-desc">الاسم: ي-أ</option>
                                </select>
                                <select class="form-select" id="categorySelect">
                                    <option value="">جميع الفئات</option>
                                    <option value="electronics">الإلكترونيات</option>
                                    <option value="clothing">الملابس</option>
                                    <option value="books">الكتب</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- عرض المنتجات -->
                    <div class="row" id="productsContainer">
                        @foreach($products as $product)
                            <div class="col-md-4 col-lg-3 mb-4 product-item" 
                                 data-name="{{ strtolower($product['name']) }}" 
                                 data-category="{{ $product['category'] ?? 'electronics' }}"
                                 data-price="{{ $product['price'] }}">
                                <div class="card h-100 product-card">
                                    <div class="product-image-container">
                                        <img src="{{ $product['image'] }}" 
                                             class="card-img-top product-image" 
                                             alt="{{ $product['name'] }}"
                                             onerror="this.src='https://via.placeholder.com/300x200?text={{ urlencode($product['name']) }}'">
                                        <div class="product-overlay">
                                            <button class="btn btn-sm btn-primary" onclick="addToCart('{{ $product['name'] }}', {{ $product['price'] }})">
                                                <i class="fas fa-cart-plus me-1"></i>إضافة للسلة
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title">{{ $product['name'] }}</h6>
                                        <p class="card-text text-muted small">{{ $product['desc'] }}</p>
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="h5 text-primary mb-0">{{ number_format($product['price'], 2) }} جنيه</span>
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= rand(3, 5) ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-outline-primary btn-sm w-100" onclick="addToCart('{{ $product['name'] }}', {{ $product['price'] }})">
                                                    <i class="fas fa-cart-plus me-1"></i>إضافة للسلة
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- رسالة عدم وجود منتجات -->
                    <div class="text-center py-5" id="noProducts" style="display: none;">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد منتجات</h5>
                        <p class="text-muted">جرب تغيير معايير البحث</p>
                    </div>

                    <!-- إحصائيات -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white text-center">
                                <div class="card-body">
                                    <h5 class="card-title">إجمالي المنتجات</h5>
                                    <h3>{{ count($products) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white text-center">
                                <div class="card-body">
                                    <h5 class="card-title">متوسط السعر</h5>
                                    <h3>{{ number_format($products->avg('price'), 2) }} جنيه</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white text-center">
                                <div class="card-body">
                                    <h5 class="card-title">أعلى سعر</h5>
                                    <h3>{{ number_format($products->max('price'), 2) }} جنيه</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white text-center">
                                <div class="card-body">
                                    <h5 class="card-title">أقل سعر</h5>
                                    <h3>{{ number_format($products->min('price'), 2) }} جنيه</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal للسلة -->
<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-shopping-cart me-2"></i>سلة التسوق
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="cartItems">
                    <!-- سيتم ملؤها بواسطة JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                                            <span class="h5 mb-0">المجموع: <span id="cartTotal">0.00</span> جنيه</span>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="button" class="btn btn-success" onclick="checkout()">
                            <i class="fas fa-credit-card me-1"></i>إتمام الشراء
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.product-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: 1px solid #e9ecef;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.product-image-container {
    position: relative;
    overflow: hidden;
}

.product-image {
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.rating {
    font-size: 12px;
}

.cart-item {
    border-bottom: 1px solid #e9ecef;
    padding: 10px 0;
}

.cart-item:last-child {
    border-bottom: none;
}
</style>

<script>
let cart = [];
let cartTotal = 0;

// إضافة منتج للسلة
function addToCart(name, price) {
    const existingItem = cart.find(item => item.name === name);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({ name, price, quantity: 1 });
    }
    
    cartTotal += price;
    updateCartDisplay();
    
    // إظهار رسالة نجاح
    showNotification(`${name} تمت إضافته للسلة`, 'success');
}

// تحديث عرض السلة
function updateCartDisplay() {
    const cartCount = document.getElementById('cartCount');
    const cartItems = document.getElementById('cartItems');
    const cartTotalElement = document.getElementById('cartTotal');
    
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalItems;
    cartTotalElement.textContent = cartTotal.toFixed(2);
    
    // تحديث محتوى السلة
    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="text-muted text-center">السلة فارغة</p>';
    } else {
        cartItems.innerHTML = cart.map(item => `
            <div class="cart-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">${item.name}</h6>
                                            <small class="text-muted">${item.price.toFixed(2)} جنيه × ${item.quantity}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                                            <span class="h6 mb-0">${(item.price * item.quantity).toFixed(2)} جنيه</span>
                    <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart('${item.name}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }
}

// إزالة منتج من السلة
function removeFromCart(name) {
    const itemIndex = cart.findIndex(item => item.name === name);
    if (itemIndex > -1) {
        const item = cart[itemIndex];
        cartTotal -= item.price * item.quantity;
        cart.splice(itemIndex, 1);
        updateCartDisplay();
        showNotification(`${name} تم إزالته من السلة`, 'warning');
    }
}

// البحث والتصفية
document.getElementById('searchInput').addEventListener('input', filterProducts);
document.getElementById('sortSelect').addEventListener('change', filterProducts);
document.getElementById('categorySelect').addEventListener('change', filterProducts);

function filterProducts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const sortBy = document.getElementById('sortSelect').value;
    const category = document.getElementById('categorySelect').value;
    
    const products = document.querySelectorAll('.product-item');
    let visibleCount = 0;
    
    products.forEach(product => {
        const name = product.dataset.name;
        const productCategory = product.dataset.category;
        const price = parseFloat(product.dataset.price);
        
        const matchesSearch = name.includes(searchTerm);
        const matchesCategory = !category || productCategory === category;
        
        if (matchesSearch && matchesCategory) {
            product.style.display = 'block';
            visibleCount++;
        } else {
            product.style.display = 'none';
        }
    });
    
    // إظهار/إخفاء رسالة عدم وجود منتجات
    document.getElementById('noProducts').style.display = visibleCount === 0 ? 'block' : 'none';
    
    // ترتيب المنتجات
    if (sortBy) {
        const productsArray = Array.from(products).filter(p => p.style.display !== 'none');
        productsArray.sort((a, b) => {
            const aPrice = parseFloat(a.dataset.price);
            const bPrice = parseFloat(b.dataset.price);
            const aName = a.dataset.name;
            const bName = b.dataset.name;
            
            switch(sortBy) {
                case 'price-low':
                    return aPrice - bPrice;
                case 'price-high':
                    return bPrice - aPrice;
                case 'name':
                    return aName.localeCompare(bName);
                case 'name-desc':
                    return bName.localeCompare(aName);
                default:
                    return 0;
            }
        });
        
        const container = document.getElementById('productsContainer');
        productsArray.forEach(product => container.appendChild(product));
    }
}

// إتمام الشراء
function checkout() {
    if (cart.length === 0) {
        showNotification('السلة فارغة', 'error');
        return;
    }
    
    showNotification('تم إتمام الطلب بنجاح!', 'success');
    cart = [];
    cartTotal = 0;
    updateCartDisplay();
    
    // إغلاق Modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('cartModal'));
    modal.hide();
}

// إظهار إشعارات
function showNotification(message, type) {
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

// عرض السلة عند النقر على العداد
document.getElementById('cartCount').addEventListener('click', () => {
    const modal = new bootstrap.Modal(document.getElementById('cartModal'));
    modal.show();
});

// تهيئة السلة
updateCartDisplay();
</script>
@endsection
