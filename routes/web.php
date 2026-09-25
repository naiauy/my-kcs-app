<?php

use App\Http\Controllers\Web\ProductController;

use App\Http\Controllers\Web\CartController;
// หน้าเปิดดูตะกร้าสินค้า
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// กดส่งข้อมูลเพิ่มสินค้าลงตะกร้า (ใช้ POST เพื่อความปลอดภัย)
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');

// กดลบสินค้าออกจากตะกร้า
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// กดอัปเดตจำนวนสินค้าในตะกร้า (ใส่ต่อท้ายกลุ่มตะกร้าสินค้าเดิมได้เลย)
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');


use App\Http\Controllers\Web\CheckoutController;

// หน้ากรอกข้อมูลสั่งซื้อ
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// กดส่งฟอร์มยืนยันสั่งซื้อสินค้า
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// หน้าสรุปใบสั่งซื้อเสร็จสิ้น (รับ ID ใบสั่งซื้อแบบ Dynamic)
Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');

use App\Http\Controllers\Web\OrderController;

// // ดูรายการคำสั่งซื้อทั้งหมด
// Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

// // ดูรายละเอียดเจาะลึกเฉพาะออเดอร์นั้นๆ
// Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');


// ---------------------------------------------------------------
// 🔐 2. หน้าสำหรับเจ้าของร้าน/Admin (ต้อง Login ก่อนเท่านั้น)
// ---------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // 🎯 หน้า Dashboard สำหรับแอดมิน
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // หน้าสร้างสินค้าใหม่
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    // หน้าดูรายการคำสั่งซื้อ
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
});

// ---------------------------------------------------------------
// 🔑 3. ดึง Route การ Login/Logout ของ Breeze เข้ามาใช้งาน
// ---------------------------------------------------------------
require __DIR__ . '/auth.php';
Route::get('/', [ProductController::class, 'index']);
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
// Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// เส้นทางแบบ Dynamic `{id}` หมายถึงจะเปลี่ยนไปตาม ID ของสินค้าที่กดดู
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
