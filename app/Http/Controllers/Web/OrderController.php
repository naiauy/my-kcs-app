<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 1. หน้าแสดงรายการคำสั่งซื้อทั้งหมด
    public function index()
    {
        // ดึงข้อมูลออเดอร์ เรียงจากใหม่ไปเก่าสุด พร้อมแบ่งหน้า (Pagination) หน้าละ 10 รายการ
        $orders = Order::latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // 2. หน้าแสดงรายละเอียดเจาะลึกของแต่ละคำสั่งซื้อ
    public function show($id)
    {
        // ดึงข้อมูลออเดอร์พร้อมกับรายการสินค้าด้านใน (Eager Loading)
        $order = Order::with('items')->findOrFail($id);

        return view('orders.show', compact('order'));
    }
}