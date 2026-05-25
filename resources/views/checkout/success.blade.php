<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สั่งซื้อสำเร็จ - MY SHOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

    <main class="max-w-3xl mx-auto px-4 py-16 text-center">
        
        <!-- ไอคอนติ๊กถูกฉลองความสำเร็จ -->
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-sm">
            ✓
        </div>

        <h1 class="text-3xl font-bold text-gray-950 mb-2">บันทึกคำสั่งซื้อเรียบร้อยแล้ว!</h1>
        <p class="text-gray-500 mb-8">ขอบคุณสำหรับคำสั่งซื้อของคุณ เลขที่ใบสั่งซื้อคือ <span class="font-bold text-gray-800">#{{ $order->id }}</span></p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
            
            <!-- ฝั่งซ้าย: สรุปยอดเงินและวิธีชำระเงิน -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <h2 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-gray-50">💵 ยอดชำระเงิน</h2>
                    <div class="mb-6">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">ยอดเงินสุทธิที่ต้องชำระ</p>
                        <p class="text-3xl font-extrabold text-blue-600 mt-1">฿{{ number_format($order->total_amount, 2) }}</p>
                    </div>

                    <h2 class="text-base font-bold text-gray-800 mb-3">🏦 ช่องทางการชำระเงิน</h2>
                    
                    @if($order->payment_method == 'qr_code')
                        <!-- กรณีเลือก Thai QR Payment -->
                        <div class="bg-blue-50/50 p-4 rounded-lg border border-blue-100 text-center space-y-3">
                            <p class="text-sm font-semibold text-blue-800">Thai QR Payment</p>
                            <!-- ตัวอย่างรูปภาพ QR Code สมมติ (ในระบบจริงจะใช้ Library เจนเป็น PromptPay Tag 30) -->
                            <div class="w-40 h-40 bg-white border border-gray-200 mx-auto rounded flex items-center justify-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://github.com" alt="QR Code" class="w-full h-full p-2">
                            </div>
                            <p class="text-xs text-gray-400">สแกนคิวอาร์โค้ดด้วยแอปธนาคารของคุณเพื่อชำระเงิน</p>
                        </div>
                    @else
                        <!-- กรณีเลือกโอนเงินธนาคาร -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-2 text-sm">
                            <p class="font-semibold text-gray-700">โอนเงินผ่านบัญชีธนาคาร:</p>
                            <p class="text-gray-600">ธนาคารกสิกรไทย (KBank)</p>
                            <p class="text-gray-900 font-bold tracking-wide text-base">012-3-45678-9</p>
                            <p class="text-gray-500">ชื่อบัญชี: บริษัท มาย ช็อป จำกัด</p>
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100">
                    <p class="text-xs text-amber-600 flex items-center">
                        ⚠️ เมื่อชำระเงินเสร็จแล้ว กรุณาเก็บหลักฐานไว้เพื่อยืนยันกับเจ้าหน้าที่
                    </p>
                </div>
            </div>

            <!-- ฝั่งขวา: รายละเอียดการจัดส่งและรายการสินค้า -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-6">
                <div>
                    <h2 class="text-base font-bold text-gray-800 mb-3">📍 ข้อมูลจัดส่ง</h2>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><span class="font-medium text-gray-800">ผู้รับ:</span> {{ $order->customer_name }}</p>
                        <p><span class="font-medium text-gray-800">เบอร์โทร:</span> {{ $order->customer_phone }}</p>
                        <p class="leading-relaxed"><span class="font-medium text-gray-800">ที่อยู่:</span> {{ $order->shipping_address }}</p>
                    </div>
                </div>

                <div>
                    <h2 class="text-base font-bold text-gray-800 mb-3 border-t border-gray-50 pt-4">📦 รายการสินค้า</h2>
                    <div class="divide-y divide-gray-100 max-h-40 overflow-y-auto text-sm pr-1">
                        @foreach($order->items as $item)
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600 line-clamp-1 pr-4">{{ $item->product_name }} <strong class="text-gray-400">x{{ $item->quantity }}</strong></span>
                                <span class="font-medium text-gray-800 shrink-0">฿{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        <!-- ปุ่มกดกลับไปช้อปต่อด้านล่างสุด -->
        <div class="mt-12">
            <a href="{{ route('products.index') }}" class="inline-block bg-gray-900 text-white font-medium px-8 py-3 rounded-md hover:bg-gray-800 transition-colors">
                กลับไปหน้าแรกเพื่อเลือกซื้อสินค้าต่อ
            </a>
        </div>

    </main>

</body>
</html>