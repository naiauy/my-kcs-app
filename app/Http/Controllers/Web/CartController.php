<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // 1. หน้าแสดงรายการสินค้าในตะกร้า
    public function index()
    {
        // ดึงข้อมูลตะกร้าสินค้าจาก Session (ถ้าไม่มีให้เป็นอาร์เรย์ว่าง)
        $cart = session()->get('cart', []);

        return view('cart.index', compact('cart'));
    }

    // 2. ฟังก์ชันเพิ่มสินค้าลงตะกร้า
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);

        // รับค่าตัวเลือกที่ลูกค้ากดส่งมา (เช่น ['color' => 'สีแดง', 'size' => 'L'])
        $selectedOptions = $request->input('options', []);

        // สร้างข้อความสรุปออปชัน เช่น "สีแดง, L"
        $optionString = !empty($selectedOptions) ? implode(', ', $selectedOptions) : '';

        // สร้างคีย์ประจำตัวสินค้าในตะกร้าโดยพ่วง Option ไปด้วย (ป้องกันไม่ให้ไปสมทบกับสีอื่น)
        $cartKey = $id . '_' . md5($optionString);

        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                "id" => $product->id, // ID สินค้าหลัก
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "options" => $optionString // 💡 พ่วงข้อมูลออปชันเก็บไว้ใน Session ตะกร้า
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'เพิ่มสินค้าลงตะกร้าเรียบร้อย!');
    }

    // 3. ฟังก์ชันลบสินค้าออกจากตะกร้า
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'ลบสินค้าออกจากตะกร้าแล้ว');
    }

    // 4. ฟังก์ชันอัปเดตจำนวนสินค้าในตะกร้า
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        // ตรวจสอบความถูกต้องของข้อมูลที่ส่งมา (ต้องเป็นตัวเลข และอย่างน้อย 1 ชิ้น)
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // ถ้าพบสินค้านี้ในตะกร้า ให้เปลี่ยนจำนวนเป็นค่าใหม่ทันที
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->input('quantity');
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'อัปเดตจำนวนสินค้าเรียบร้อยแล้ว');
        }

        return redirect()->back()->with('error', 'ไม่พบสินค้าชิ้นนี้ในตะกร้า');
    }
}
