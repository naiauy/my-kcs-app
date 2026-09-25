@extends('layouts.app')

@section('title', 'รายการสินค้าทั้งหมด - KCS SHOP')

@section('content')
    <!-- Product Detail Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- ปุ่มย้อนกลับ -->
        <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline inline-flex items-center mb-6">
            ← กลับไปหน้าสินค้าทั้งหมด
        </a>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">

                <!-- ฝั่งซ้าย: รูปภาพสินค้า -->
                @php
                // 📸 1. จัดการแกะกล่องรูปภาพ JSON ออกมาเป็น Array
                $images = [];
                if ($product->image_url) {
                $images = is_string($product->image_url) ? json_decode($product->image_url, true) : $product->image_url;
                }
                // ดึงรูปแรกเป็นรูปหลัก ถ้าไม่มีให้ใช้ภาพ Default
                $mainImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                @endphp
                <!-- ฝั่งซ้าย: รูปภาพสินค้าหลายรูป + วิดีโอ -->
                <div class="space-y-6">
                    <!-- 🖼️ ฝั่งซ้าย: ส่วนแสดงรูปภาพ (Main Image + Thumbnails) -->
                    <div>
                        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                            @if($mainImage)
                            <img id="current-main-image"
                                src="{{ str_starts_with($mainImage, 'http') ? $mainImage : asset('storage/' . $mainImage) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-[450px] object-cover transition-all duration-300">
                            @else
                            <div class="w-full h-[450px] bg-gray-50 flex items-center justify-center text-gray-400">
                                📦 ไม่มีรูปภาพสินค้า
                            </div>
                            @endif
                        </div>

                        <!-- ถ้ามีรูปภาพมากกว่า 1 รูป ให้พ่นรูปเล็ก (Thumbnails) ออกมาด้านล่าง -->
                        @if(is_array($images) && count($images) > 1)
                        <div class="flex gap-2 mt-3 overflow-x-auto pb-2">
                            @foreach($images as $img)
                            @php $actualSrc = str_starts_with($img, 'http') ? $img : asset('storage/' . $img); @endphp
                            <button type="button"
                                onclick="changeMainImage('{{ $actualSrc }}')"
                                class="w-20 h-20 rounded-lg border border-gray-200 overflow-hidden hover:border-blue-500 focus:outline-none transition-all flex-shrink-0">
                                <img src="{{ $actualSrc }}" class="w-full h-full object-cover">
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- 3. วิดีโอรีวิวสินค้า (ถ้ามีข้อมูลในฐานข้อมูล) -->
                    @if($product->video_url)
                    <div class="pt-4 border-t border-gray-100">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">วิดีโอรีวิวสินค้า</h4>
                        <div class="aspect-video rounded-lg overflow-hidden shadow-sm border border-gray-100">
                            <!-- รองรับลิงก์วิดีโอรีวิว เช่น จาก YouTube Embed -->
                            <iframe class="w-full h-full" src="{{ $product->video_url }}" title="Product Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- โค้ด JavaScript สั้นๆ ท้ายไฟล์ (ก่อนปิด </body>) สำหรับกดสลับรูปภาพหลัก -->
                <!-- 💡 สคริปต์จิ๋วสลับรูปภาพหลักเมื่อแอดมินคลิกรูปภาพเล็ก (Thumbnails) -->
                <script>
                    function changeMainImage(src) {
                        const mainImg = document.getElementById('current-main-image');
                        if (mainImg) {
                            mainImg.style.opacity = '0.3';
                            setTimeout(() => {
                                mainImg.src = src;
                                mainImg.style.opacity = '1';
                            }, 150);
                        }
                    }
                </script>

                <!-- ฝั่งขวา: รายละเอียดและราคา -->
                <div class="flex flex-col justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-950 mb-4">{{ $product->name }}</h1>

                        <div class="flex items-baseline space-x-4 mb-6">
                            <!-- <span class="text-3xl font-extrabold text-blue-600">฿{{ number_format($product->price, 2) }}</span> -->
                            <span id="product-display-price" data-base-price="{{ $product->price }}" class="text-2xl font-bold text-blue-600">
                                {{ number_format($product->price, 2) }} บาท
                            </span>
                            <span class="text-sm text-gray-400">สินค้าคงเหลือในคลัง: {{ $product->stock }} ชิ้น</span>
                        </div>

                        <hr class="border-gray-100 mb-6">

                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">รายละเอียดสินค้า</h3>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-line mb-6">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- ส่วนปุ่มแอคชัน -->
                    <!-- ค้นหาและแทนที่ส่วนปุ่มแอคชันด้วยโค้ดชุดนี้ -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-6">
                            <!-- 💡 กล่องแจ้งเตือนเมื่อเพิ่มสินค้าลงตะกร้าสำเร็จ -->
                            @if(session('success'))
                            <div id="toast-success" class="fixed top-5 right-5 z-50 flex items-center w-full max-w-xs p-4 text-gray-900 bg-white rounded-2xl border border-gray-100 shadow-xl transition-all duration-500 transform translate-y-0" role="alert">
                                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-50 rounded-lg">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                                    </svg>
                                </div>
                                <div class="ms-3 text-sm font-semibold text-gray-800">{{ session('success') }}</div>
                                <button type="button" onclick="closeToast()" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-950 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                </button>
                            </div>

                            <!-- สคริปต์สั่งให้ Popup (Toast) หายไปเองอัตโนมัติภายใน 3 วินาที -->
                            <script>
                                function closeToast() {
                                    const toast = document.getElementById('toast-success');
                                    if (toast) {
                                        toast.style.opacity = '0';
                                        setTimeout(() => toast.remove(), 500);
                                    }
                                }
                                // ตั้งเวลา 3000 มิลลิวินาที (3 วินาที) แล้วให้เฟดปิดตัวเอง
                                setTimeout(closeToast, 3000);
                            </script>
                            @endif

                            @csrf

                            @php
                            $colors = $product->options->where('option_type', 'color');
                            $sizes = $product->options->where('option_type', 'size');
                            @endphp

                            <!-- 🎨 1. ตัวเลือกสีแบบปุ่มกด -->
                            @if($colors->count() > 0)
                            <div>
                                <label class="block text-sm font-semibold text-gray-950 mb-2">เลือกสี</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($colors as $index => $color)
                                    @php
                                    $optImg = $color->option_image ? asset('storage/' . $color->option_image) : null;
                                    @endphp
                                    <label class="relative cursor-pointer">
                                        <!-- ซ่อน Radio Input ตัวจริงไว้หลังบ้าน -->
                                        <!-- <input type="radio" name="options[color]" value="{{ $color->option_value }}" class="peer sr-only" {{ $index === 0 ? 'checked' : '' }}> -->
                                        <input type="radio" name="options[color]"
                                            value="{{ $color->option_value }}"
                                            data-price="{{ $color->price_modifier }}"
                                            data-image="{{ $optImg }}"
                                            class="peer sr-only option-picker" {{ $index === 0 ? 'checked' : '' }}>

                                        <!-- ปุ่มกดจำลองที่จะเปลี่ยนสีตามสถานะการเลือก (Peer State) -->
                                        <div class="px-4 py-2 text-sm font-medium border border-gray-200 rounded-lg text-gray-700 bg-white shadow-sm hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                                            {{ $color->option_value }}
                                            @if($color->price_modifier > 0) (+{{ number_format($color->price_modifier) }}฿) @endif
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- 📏 2. ตัวเลือกขนาดแบบปุ่มกด -->
                            @if($sizes->count() > 0)
                            <div>
                                <label class="block text-sm font-semibold text-gray-950 mb-2">เลือกขนาด / ความจุ</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($sizes as $index => $size)
                                    <label class="relative cursor-pointer">
                                        <!-- ซ่อน Radio Input ตัวจริงไว้หลังบ้าน -->
                                        <input type="radio" name="options[size]"
                                            value="{{ $size->option_value }}"
                                            data-price="{{ $size->price_modifier }}"
                                            class="peer sr-only option-picker" {{ $index === 0 ? 'checked' : '' }}>

                                        <!-- ปุ่มกดจำลองที่จะเปลี่ยนสีตามสถานะการเลือก -->
                                        <div class="px-4 py-2 text-sm font-medium border border-gray-200 rounded-lg text-gray-700 bg-white shadow-sm hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                                            {{ $size->option_value }}
                                            @if($size->price_modifier > 0) (+{{ number_format($size->price_modifier) }}฿) @endif
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- ช่องเลือกจำนวนชิ้นเดิม -->
                            <div class="pt-2">
                                <label class="block text-sm font-semibold text-gray-950 mb-2">จำนวน</label>
                                <div class="flex items-center space-x-2">
                                    <input type="number" name="quantity" value="1" min="1" class="w-20 border border-gray-200 rounded-lg p-2 text-sm text-center font-bold text-gray-800 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                </div>
                            </div>

                            <!-- ปุ่มกดส่งเข้าตะกร้า -->
                            <button type="submit" class="w-full md:w-auto bg-blue-600 text-white px-8 py-3 rounded-xl text-sm font-bold shadow-md hover:bg-blue-700 hover:shadow-lg active:scale-95 transition-all">
                                🛒 เพิ่มลงตะกร้าสินค้า
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>
<script>
    document.querySelectorAll('.option-picker').forEach(input => {
        input.addEventListener('change', updateProductDetails);
    });

    function updateProductDetails() {
        const priceDisplay = document.getElementById('product-display-price');
        const mainImage = document.getElementById('current-main-image');

        let basePrice = parseFloat(priceDisplay.getAttribute('data-base-price'));
        let additionalPrice = 0;

        // วนลูปเช็กวิทยุทุกตัวที่ถูกเลือก (Checked) เพื่อนำราคาบวกเพิ่มมารวมกัน
        document.querySelectorAll('.option-picker:checked').forEach(checkedInput => {
            // รวมราคาบวกเพิ่ม
            additionalPrice += parseFloat(checkedInput.getAttribute('data-price') || 0);

            // ถ้าตัวเลือกนั้นเป็นสีและมีรูปภาพเฉพาะตัว ให้สลับรูปภาพหลักทันที
            let optionImg = checkedInput.getAttribute('data-image');
            if (optionImg && optionImg !== 'null' && optionImg !== '') {
                mainImage.src = optionImg;
            }
        });

        // อัปเดตราคาแสดงผลรวมบนหน้าจอ
        let finalPrice = basePrice + additionalPrice;
        priceDisplay.innerText = finalPrice.toLocaleString('th-TH', {
            minimumFractionDigits: 2
        }) + ' บาท';
    }

    // รันครั้งแรกเพื่อให้ราคาตั้งต้นอัปเดตอัตโนมัติ
    document.addEventListener('DOMContentLoaded', updateProductDetails);
</script>

@endsection