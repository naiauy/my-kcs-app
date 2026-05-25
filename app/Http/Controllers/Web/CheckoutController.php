<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // 1. หน้ากรอกข้อมูลชำระเงิน
    public function index()
    {
        $cart = session()->get('cart', []);

        // ถ้าตะกร้าว่างเปล่า ไม่ยอมให้เข้าหน้านี้ ให้เด้งกลับไปหน้าตะกร้า
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'ไม่มีสินค้าในตะกร้าให้ชำระเงินครับ');
        }

        return view('checkout.index', compact('cart'));
    }

    // 2. ฟังก์ชันประมวลผลเซฟใบสั่งซื้อลง Database
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index');
        }

        // ตรวจสอบความถูกต้องของข้อมูลลูกค้า
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
        ]);

        // คำนวณยอดเงินรวมสุทธิ
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // 💡 ปรับปรุงตรงนี้: สั่งให้ตัวแปร $order มารับค่าที่ return ออกมาจาก DB::transaction
        $order = DB::transaction(function () use ($request, $cart, $totalAmount) {
            // 1. บันทึกลงตาราง orders
            $newOrder = Order::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method ?? 'qr_code',
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);

            // 2. วนลูปบันทึกสินค้าลงตาราง order_items
            // วนลูปบันทึกสินค้าลงตาราง order_items (แก้ไขตรงนี้ครับ)
            foreach ($cart as $cartKey => $item) {
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $item['id'], // ดึงจาก id ด้านในอาร์เรย์แทนคีย์หลักเดิม
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'selected_options' => $item['options'], // 💡 เซฟออปชันที่ลูกค้าเลือกลง Database เรียบร้อย!
                ]);
            }

            // ต้องส่งตัวแปร $newOrder ออกไปนอกบล็อก Transaction ด้วยครับ
            return $newOrder;
        });

        // เคลียร์ตะกร้าสินค้าใน Session ทิ้งหลังจากสั่งซื้อเสร็จแล้ว
        session()->forget('cart');

        // 💡 ตอนนี้ด้านนอกบล็อกจะมีตัวแปร $order มารองรับเรียบร้อยแล้ว ส่ง ID ไปหน้า Success ได้ฉลุยครับ!
        return redirect()->route('checkout.success', $order->id);
    }

    public function success($id)
    {
        // ดึงข้อมูลใบสั่งซื้อพร้อมรายการสินค้าด้านใน (Eager Loading ด้วย with('items'))
        $order = Order::with('items')->findOrFail($id);

        return view('checkout.success', compact('order'));
    }
}
