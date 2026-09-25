<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // 1. หน้าแสดงรายการสินค้าในตะกร้า
    public function index()
    {
        // ดึงข้อมูลตะกร้าสินค้าจาก Session (ถ้าไม่มีให้เป็นอาร์เรย์ว่าง)
        $cart = session()->get('cart', []);
        $categories = Cache::remember('global_product_categories', 86400, function () {
            return Category::all();
        });

        return view('cart.index', compact('cart', 'categories'));
    }

    // 2. ฟังก์ชันเพิ่มสินค้าลงตะกร้า
    // ใน CartController.php ท่อนที่สั่งแอดสินค้าลงตะกร้า
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);
        $options = $request->input('options', []); // อาร์เรย์ที่ส่งมา เช่น ['color' => 'สีขาว', 'size' => '256GB']

        $finalPrice = $product->price;
        $optionStrings = [];

        // ดึงค่าราคาบวกเพิ่มของออปชันแต่ละตัวจากฐานข้อมูลมาคำนวณจริงหลังบ้านเพื่อความปลอดภัย
        foreach ($options as $type => $value) {
            $optionRecord = \App\Models\ProductOption::where('product_id', $id)
                ->where('option_type', $type)
                ->where('option_value', $value)
                ->first();

            if ($optionRecord) {
                $finalPrice += $optionRecord->price_modifier; // บวกเพิ่มราคาเข้าไปในค่าตัวนี้
                $optionStrings[] = "{$optionRecord->option_value}";
            }
        }

        $optionString = implode(', ', $optionStrings);
        $cartKey = $id . '_' . md5($optionString);

        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $finalPrice, // 🎯 ใช้ราคาที่บวกรวมราคา Option เรียบร้อยแล้วเข้าไปเก็บในตะกร้า
                "options" => $optionString
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'เพิ่มลงตะกร้าเรียบร้อย!');
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
