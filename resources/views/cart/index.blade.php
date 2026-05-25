<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า - MY SHOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="text-xl font-bold text-blue-600 tracking-wide">
                    <a href="{{ route('products.index') }}">📦 MY SHOP</a>
                </div>
                <div>
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-blue-600">กลับไปเลือกซื้อสินค้า</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- เนื้อหาหลัก -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-2xl font-bold text-gray-800 mb-8">📥 ตะกร้าสินค้าของคุณ</h1>

        @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- ตารางรายการสินค้าฝั่งซ้าย -->
            <div class="lg:col-span-2 space-y-4">
                @php $total = 0; @endphp
                @foreach($cart as $id => $details)
                @php $total += $details['price'] * $details['quantity']; @endphp

                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        @php
                        $cartProduct = \App\Models\Product::find($details['id'] ?? null);
                        $imageUrl = null;

                        if ($cartProduct) {
                        if ($cartProduct->images && $cartProduct->images->count() > 0) {
                        $firstImg = $cartProduct->images->first();
                        $imageUrl = $firstImg->image_url ?? $firstImg->image;
                        }
                        elseif ($cartProduct->image_url) {
                        $images = is_string($cartProduct->image_url) ? json_decode($cartProduct->image_url, true) : $cartProduct->image_url;
                        $imageUrl = is_array($images) ? ($images[0] ?? null) : $cartProduct->image_url;
                        }
                        }
                        @endphp

                        @if($imageUrl)
                        <img src="{{ str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl) }}"
                            class="w-16 h-16 object-cover rounded-lg border border-gray-100 shadow-sm">
                        @else
                        <div class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-100 flex items-center justify-center text-gray-400 text-xs shadow-sm">
                            No Image
                        </div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-gray-800 text-base">{{ $details['name'] }}</h3>
                            <span class="text-gray-500">{{ $details['options'] }}</span>
                            <p class="text-blue-600 font-medium text-sm mt-1">฿{{ number_format($details['price'], 2) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-6">
                        <!-- ของใหม่: ฟอร์มสำหรับปรับเพิ่ม-ลดจำนวนสินค้า -->
                        <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center space-x-2 bg-gray-50 p-1 rounded-lg border border-gray-200">
                            @csrf
                            @method('PATCH')

                            <label class="text-xs text-gray-500 pl-2">จำนวน:</label>
                            <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-14 text-center bg-transparent font-semibold text-gray-800 focus:outline-none">

                            <button type="submit" class="bg-blue-600 text-white text-xs px-2 py-1 rounded-md hover:bg-blue-700 transition-colors">
                                อัปเดต
                            </button>
                        </form>
                        <span class="font-bold text-gray-800">฿{{ number_format($details['price'] * $details['quantity'], 2) }}</span>

                        <!-- ปุ่มลบสินค้า -->
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">ลบ</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- กล่องสรุปราคารวมฝั่งขวา -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit space-y-6">
                <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">สรุปยอดคำสั่งซื้อ</h2>
                <div class="flex justify-between items-baseline">
                    <span class="text-gray-500">ยอดรวมทั้งสิ้น:</span>
                    <span class="text-2xl font-extrabold text-blue-600">฿{{ number_format($total, 2) }}</span>
                </div>
                <button class="w-full bg-blue-600 text-white font-medium py-3 rounded-md hover:bg-blue-700 transition-colors">
                    <a href="{{ route('checkout.index') }}" class="w-full bg-blue-600 text-white font-medium py-3 rounded-md hover:bg-blue-700 transition-colors block text-center">
                        ไปหน้าชำระเงิน (Checkout) →
                    </a>
                </button>
            </div>
        </div>
        @else
        <!-- กรณีไม่มีสินค้าในตะกร้า -->
        <div class="bg-white text-center py-16 rounded-xl shadow-sm border border-gray-100">
            <p class="text-gray-400 text-lg mb-4">ยังไม่มีสินค้าในตะกร้าของคุณในขณะนี้</p>
            <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md inline-block hover:bg-blue-700">ไปเลือกดูสินค้า</a>
        </div>
        @endif
    </main>

</body>

</html>