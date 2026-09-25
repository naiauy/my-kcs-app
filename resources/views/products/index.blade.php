@extends('layouts.app')

@section('title', 'รายการสินค้าทั้งหมด - KCS SHOP')

@section('content')
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
                @csrf

                @php
                $colors = $product->options->where('option_type', 'color');
                $sizes = $product->options->where('option_type', 'size');


                $actionRoute = route('cart.add', $product->id) ;
                $actionLabel = 'เพิ่มลงตะกร้า';
                $actionMethod = 'POST';
                if($colors->count() > 0 || $sizes->count() > 0){
                $actionRoute = route('products.show', $product->id) ;
                $actionMethod = 'GET';
                }
                @endphp

                <form action="{{ $actionRoute }}" method="{{ $actionMethod }}"> @csrf
                    <button class="w-full bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        {{ $actionLabel }}
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

@endsection