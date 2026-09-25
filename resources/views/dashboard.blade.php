@extends('layouts.admin')

@section('title', 'แผงควบคุมแอดมิน - KCS SHOP')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">แผงควบคุมระบบ (Admin Dashboard)</h1>
            <p class="text-gray-500 text-sm mt-1">ยินดีต้อนรับคุณ {{ Auth::user()->name }} เข้าสู่ระบบจัดการร้าน KCS SHOP</p>
        </div>
        <!-- ปุ่ม Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-xl text-sm font-medium border border-red-200 transition-colors">
                ออกจากระบบ (Logout)
            </button>
        </form>
    </div>

    <!-- เมนูลัดจัดการระบบ -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- การ์ด 1: จัดการสินค้า -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                📦
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">จัดการสินค้า</h3>
            <p class="text-gray-500 text-xs mb-4">เพิ่มสินค้าใหม่ แก้ไข หรือจัดการสต็อกสินค้าในร้าน</p>
            <a href="{{ route('products.create') }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700">
                + เพิ่มสินค้าใหม่ &rarr;
            </a>
        </div>

        <!-- การ์ด 2: รายการสั่งซื้อ -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center mb-4">
                🛍️
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">รายการสั่งซื้อ</h3>
            <p class="text-gray-500 text-xs mb-4">ตรวจสอบออเดอร์ แจ้งชำระเงิน และสถานะการจัดส่ง</p>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center text-sm font-semibold text-green-600 hover:text-green-700">
                ดูออเดอร์ทั้งหมด &rarr;
            </a>
        </div>

        <!-- การ์ด 3: ดูหน้าร้านค้า -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4">
                🏪
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">หน้าร้านค้า</h3>
            <p class="text-gray-500 text-xs mb-4">สลับไปหน้าแรกของร้านเพื่อดูมุมมองของลูกค้า</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm font-semibold text-purple-600 hover:text-purple-700">
                ไปยังหน้าร้านค้า &rarr;
            </a>
        </div>
    </div>
</div>
@endsection