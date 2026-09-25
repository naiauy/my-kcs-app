<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // 🎯 เพิ่มบรรทัดเด็ดบรรทัดนี้เข้าไปครับ!
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. ดึงข้อมูลหมวดหมู่สินค้าทั้งหมดมาทำแถบเมนู
        // $categories = Category::all();
        // 1. เช็กว่ามี categories ใน session หรือยัง ถ้ายังไม่มีให้ query แล้วเก็บลง session
        // if (!Session::has('categories')) {
        //     // $categories = Category::select('id', 'name')->get();
        //     $categories = Category::all();
        //     Session::put('categories', $categories);
        // } else {
        //     // ดึงจาก session มาใช้งาน
        //     $categories = Session::get('categories');
        // }

        $categories = Cache::remember('global_product_categories', 86400, function () {
            return Category::all();
        });


        // 2. ขึ้นโครง Query ดึงเฉพาะสินค้าที่เปิดใช้งาน (is_active = true) เป็นตัวตั้งต้น
        $query = Product::where('is_active', true);

        // 🎯 ใช้ if เช็กแบบตรงไปตรงมาแทนการใช้ when() เพื่อลดความซับซ้อนของ Closure Function
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // 🎯 4. เช็กกรองตามคำค้นหา (Search Keyword)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // 5. สั่งแบ่งหน้า พร้อมคงค่า Query String ทั้งหมดไว้ใน URL (เพื่อให้กดเปลี่ยนหน้า 2, 3 แล้วคำค้นหาไม่หลุด)
        $products = $query->paginate(8)->withQueryString();

        // 3. ส่งตัวแปรทั้งหมดไปที่หน้าวิว
        return view('products.index', compact('products', 'categories'));
    }

    // ฟังก์ชันใหม่สำหรับแสดงรายละเอียดสินค้าเดี่ยวๆ
    public function show($id)
    {
        // ดึงข้อมูลสินค้าพร้อมรูปภาพประกอบทั้งหมดในคำสั่งเดียว
        $product = Product::with('images')->where('is_active', true)->findOrFail($id);

        $categories = Cache::remember('global_product_categories', 86400, function () {
            return Category::all();
        });
        
        return view('products.show', compact('product', 'categories'));
    }

    // 1. หน้าฟอร์มเพิ่มสินค้า
    public function create()
    {
        // ดึงหมวดหมู่ทั้งหมดในระบบส่งไปให้หน้าฟอร์มเลือก
        $categories = Cache::remember('global_product_categories', 86400, function () {
            return Category::all();
        });

        return view('products.create', compact('categories'));
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
                'category_id' => $request->input('category_id'),
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
            // ค้นหาท่อนวนลูปเซฟ Option เดิมใน ProductController.php แล้วเปลี่ยนเป็นชุดนี้ครับ:
            if ($request->has('options') && is_array($request->options)) {
                foreach ($request->options as $index => $option) {
                    if (!empty($option['value'])) {

                        // เช็กว่ามีการอัปโหลดรูปภาพเฉพาะของ Option แถวนี้เข้ามาไหม
                        $optionImagePath = null;
                        if ($request->hasFile("options_images.{$index}")) {
                            $optionImagePath = $request->file("options_images.{$index}")->store('products/options', 'public');
                        }

                        ProductOption::create([
                            'product_id' => $product->id,
                            'option_type' => $option['type'],
                            'option_value' => $option['value'],
                            'price_modifier' => $option['price_modifier'] ?? 0.00, // เซฟราคาบวกเพิ่ม
                            'option_image' => $optionImagePath // เซฟพาธรูปภาพออปชัน
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
