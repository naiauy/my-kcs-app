<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Shop</title>
    <!-- ดึง Tailwind CSS มาใช้งานเพื่อให้จัดหน้าตาสวยงาม -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">

    <!-- ส่วนหัวของเว็บไซต์ (Navbar) -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="text-xl font-bold text-blue-600 tracking-wide">📦 MY SHOP</div>
                <div class="space-x-4">
                    <a href="#" class="text-gray-600 hover:text-blue-600">หน้าแรก</a>
                    <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-blue-600 relative">
                        ตะกร้าสินค้า
                        <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">{{ session()->has('cart') ? count(session()->get('cart')) : 0 }}</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>


    <!-- เนื้อหาหลัก (Product Grid) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">สินค้าทั้งหมด</h1>

        <!-- วนลูปแสดงสินค้าแบบ Grid: บนมือถือแสดง 1 คอลัมน์, แท็บเล็ต 2, คอมพิวเตอร์ 4 คอลัมน์ -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow duration-300">
                <div>
                    <!-- รูปภาพสินค้า -->
                    @php
                    $imageUrl = null;
                    if ($product->image_url) {
                    // ลองแปลงค่าจาก JSON สตริงก์ให้กลับมาเป็น Array ของ PHP
                    $images = is_string($product->image_url) ? json_decode($product->image_url, true) : $product->image_url;

                    // ถ้าแปลงสำเร็จและมีรูปอยู่ข้างใน ให้ดึงรูปแรก (อินเด็กซ์ 0) มาใช้งาน
                    $imageUrl = is_array($images) ? ($images[0] ?? null) : $product->image_url;
                    }
                    @endphp

                    @if($imageUrl)
                    <!-- 💡 ถ้าเป็นพาธโลคอลในเครื่องให้พ่วง asset('storage/...') ถ้าเป็น URL ตรงๆ ก็พ่นออกไปเลย -->
                    <img src="{{ str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-48 object-cover rounded-xl">
                    @else
                    <!-- ภาพสแตนด์บายกรณีไม่มีรูปภาพ -->
                    <div class="w-full h-48 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 text-sm">
                        📦 ไม่มีรูปภาพสินค้า
                    </div>
                    @endif

                    <!-- รายละเอียดสินค้าในกล่อง -->
                    <div class="p-4">
                        <!-- แทนที่แท็ก h2 เดิมด้วยโค้ดชุดนี้ครับ -->
                        <h2 class="font-semibold text-gray-800 text-base line-clamp-1 mb-1" title="{{ $product->name }}">
                            <a href="{{ route('products.show', $product->id) }}" class="hover:text-blue-600 transition-colors">
                                {{ $product->name }}
                            </a>
                        </h2>
                        <p class="text-gray-500 text-xs line-clamp-2 mb-3">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>

                <div class="p-4 pt-0">
                    <!-- ราคาและปุ่มสั่งซื้อ -->
                    <div class="flex items-baseline justify-between mb-3">
                        <span class="text-lg font-bold text-blue-600">฿{{ number_format($product->price, 2) }}</span>
                        <span class="text-xs text-gray-400">คงเหลือ: {{ $product->stock }} ชิ้น</span>
                    </div>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST"> @csrf
                        <button class="w-full bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-md hover:bg-blue-700 transition-colors duration-200">
                            เพิ่มลงตะกร้า
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- ตัวแบ่งหน้า (Pagination links) -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </main>

</body>

</html>