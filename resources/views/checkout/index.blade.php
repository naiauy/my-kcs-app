<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน - MY SHOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

    <main class="max-w-5xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">💳 ชำระเงินและสั่งซื้อสินค้า</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- ฝั่งซ้าย: ฟอร์มกรอกที่อยู่จัดส่ง -->
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-6">ข้อมูลที่อยู่จัดส่ง</h2>
                
                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล ผู้รับสินค้า</label>
                        <input type="text" name="customer_name" required class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์ติดต่อ</label>
                        <input type="text" name="customer_phone" required class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ที่อยู่จัดส่งอย่างละเอียด</label>
                        <textarea name="shipping_address" rows="4" required class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:border-blue-500" placeholder="บ้านเลขที่, ถนน, แขวง, เขต, จังหวัด..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">วิธีการชำระเงิน</label>
                        <select name="payment_method" class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:border-blue-500">
                            <option value="qr_code">Thai QR Payment (สแกนคิวอาร์โค้ด)</option>
                            <option value="transfer">โอนเงินผ่านบัญชีธนาคาร</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 rounded-md hover:bg-blue-700 transition-colors duration-200 text-center block mt-6">
                        ✅ ยืนยันคำสั่งซื้อสุทธิ
                    </button>
                </form>
            </div>

            <!-- ฝั่งขวา: สรุปรายการในตะกร้า -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit space-y-4">
                <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3">สรุปรายการสินค้า</h2>
                @php $total = 0; $delivery_fee = 35; @endphp
                <div class="divide-y divide-gray-100 max-h-60 overflow-y-auto pr-1">
                    @foreach($cart as $item)
                        @php $total += $item['price'] * $item['quantity']; @endphp
                        <div class="flex justify-between py-3 text-sm">
                            <div class="pr-4">
                                <p class="font-medium text-gray-800 line-clamp-1">{{ $item['name'] }}</p>
                                <p class="text-gray-400 text-xs">จำนวน: {{ $item['quantity'] }} ชิ้น</p>
                            </div>
                            <span class="font-semibold text-gray-800 shrink-0">฿{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </div>
                    @endforeach
                    @php
                        if($total > 300) {
                            $delivery_fee += 15;
                        }    
                    $total += $delivery_fee;
                    @endphp
                     <div class="flex justify-between py-3 text-sm">
                            <div class="pr-4">
                                <p class="font-medium text-gray-800 line-clamp-1">ค่าบริการจัดส่งสินค้า</p>
                                <p class="text-gray-400 text-xs"></p>
                            </div>
                            <span class="font-semibold text-gray-800 shrink-0">฿{{ number_format($delivery_fee, 2) }}</span>
                        </div>
                </div>
                <div class="border-t border-gray-100 pt-4 flex justify-between items-baseline">
                    <span class="text-gray-500 font-medium">ยอดรวมทั้งสิ้น:</span>
                    <span class="text-2xl font-extrabold text-blue-600">฿{{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>
    </main>

</body>
</html>