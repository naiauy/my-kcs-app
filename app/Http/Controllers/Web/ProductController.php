<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // 🎯 เพิ่มบรรทัดเด็ดบรรทัดนี้เข้าไปครับ!

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->paginate(8);
        return view('products.index', compact('products'));
    }

    // ฟังก์ชันใหม่สำหรับแสดงรายละเอียดสินค้าเดี่ยวๆ
    public function show($id)
    {
        // ดึงข้อมูลสินค้าพร้อมรูปภาพประกอบทั้งหมดในคำสั่งเดียว
        $product = Product::with('images')->where('is_active', true)->findOrFail($id);

        return view('products.show', compact('product'));
    }

    // 1. หน้าฟอร์มเพิ่มสินค้า
    public function create()
    {
        return view('products.create');
    }

    // 2. ฟังก์ชันบันทึกข้อมูลสินค้าลงฐานข้อมูล
    public function store(Request $request)
    {
        // Validation ตรวจสอบข้อมูลเบื้องต้น
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0', // 🎯 เพิ่มตรวจสอบจำนวนสต็อก
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // เช็กไฟล์รูปภาพ
        ]);

        // ใช้ Database Transaction เพื่อความปลอดภัย
        DB::beginTransaction();

        try {
            // บันทึกข้อมูลสินค้าหลัก
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name) ?: time(), // 🎯 เจน Slug อัตโนมัติจากชื่อสินค้า (ถ้าเป็นภาษาไทยล้วนแล้วเป็นค่าว่าง ให้ใส่เวลาแทนชั่วคราว)
                'price' => $request->price,
                'stock' => $request->stock, // 🎯 เพิ่มการเซฟจำนวนสต็อกลงตารางตรงนี้ครับ
                'description' => $request->description,
                'image_url' => json_encode([]), // เซฟเป็น JSON ว่างไว้ก่อน หรือหากใช้ตารางแยกก็ปล่อยผ่านได้ครับ
            ]);

            // บันทึกรูปภาพ (กรณีอัปโหลดหลายรูปพร้อมกัน)
            $uploadedImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    // อัปโหลดไปเก็บใน storage/app/public/products
                    $path = $file->store('products', 'public');
                    $uploadedImages[] = $path;

                    // 💡 เคสที่ A: ถ้าคุณอุ้ยใช้ตารางแยกชื่อ product_images ให้บันทึกลงตารางลูกตรงนี้
                    // $product->images()->create(['image_url' => $path]);
                }
            }

            // 💡 เคสที่ B: ถ้าคุณอุ้ยใช้คอลัมน์ image_url ในตาราง products ตรงๆ ให้จับอัปเดตตรงนี้ครับ
            if (!empty($uploadedImages)) {
                $product->update([
                    'image_url' => json_encode($uploadedImages)
                ]);
            }

            // บันทึกตัวเลือกสินค้า (Options) แบบวนลูปดึงข้อมูลจากอินพุตไดนามิก
            if ($request->has('options')) {
                foreach ($request->options as $option) {
                    if (!empty($option['value'])) {
                        ProductOption::create([
                            'product_id' => $product->id,
                            'option_type' => $option['type'], // 'color' หรือ 'size'
                            'option_value' => $option['value'] // เช่น 'สีแดง', 'Size L'
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('products.create')->with('success', '🎉 เพิ่มสินค้าและตัวเลือกเรียบร้อยแล้ว!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}
