<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดคำสั่งซื้อ #{{ $order->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans">

    <main class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-6">
            <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:text-blue-600">← กลับไปที่รายการออเดอร์ทั้งหมด</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-8 space-y-8">
            <!-- ส่วนหัวออเดอร์ -->
            <div class="flex justify-between items-start border-b border-gray-100 pb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-950">คำสั่งซื้อ #{{ $order->id }}</h1>
                    <p class="text-xs text-gray-400 mt-1">วันที่ทำรายการ: {{ $order->created_at->format('d/m/Y H:i') }} น.</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $order->status == 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-green-50 text-green-700 border border-green-100' }}">
                    {{ $order->status == 'pending' ? 'รอตรวจสอบการชำระเงิน' : 'ชำระเงินเสร็จสิ้น' }}
                </span>
            </div>

            <!-- ข้อมูลที่อยู่ลูกค้า -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-5 rounded-xl border border-gray-100 text-sm">
                <div>
                    <h3 class="font-bold text-gray-900 mb-2">📍 ข้อมูลการจัดส่ง</h3>
                    <p class="text-gray-700"><span class="text-gray-400">ชื่อผู้รับ:</span> {{ $order->customer_name }}</p>
                    <p class="text-gray-700 mt-1"><span class="text-gray-400">เบอร์โทร:</span> {{ $order->customer_phone }}</p>
                    <p class="text-gray-700 mt-1 leading-relaxed"><span class="text-gray-400">ที่อยู่:</span> {{ $order->shipping_address }}</p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-2">💳 ข้อมูลการชำระเงิน</h3>
                    <p class="text-gray-700"><span class="text-gray-400">ช่องทาง:</span> {{ $order->payment_method == 'qr_code' ? 'Thai QR Payment' : 'โอนเงินธนาคาร' }}</p>
                    <p class="text-gray-700 mt-1"><span class="text-gray-400">ยอดเงินรวมสุทธิ:</span> <strong class="text-blue-600 text-base">฿{{ number_format($order->total_amount, 2) }}</strong></p>
                </div>
            </div>

            <!-- รายการสินค้าด้านใน -->
            <!-- รายการสินค้าด้านใน (เวอร์ชันอัปเกรดมีรูปภาพและรหัสสินค้า) -->
            <div>
                <h3 class="font-bold text-gray-950 mb-4 text-base">📦 รายการสินค้าในออเดอร์นี้</h3>
                <div class="border border-gray-100 rounded-lg overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-medium">
                            <tr>
                                <th class="py-3 px-4 w-24 text-center">รูปสินค้า</th>
                                <th class="py-3 px-4">รายละเอียดสินค้า</th>
                                <th class="py-3 px-4 text-center">ราคา/ชิ้น</th>
                                <th class="py-3 px-4 text-center">จำนวน</th>
                                <th class="py-3 px-4 text-right">ยอดรวม</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @foreach($order->items as $item)
                            <tr class="align-middle">
                                <!-- 1. คอลัมน์แสดงรูปภาพสินค้า (ดึงผ่าน Relationship) -->
                                <td class="py-3 px-4 text-center">
                                    <!-- เช็กว่ามีตัวสินค้า และมีข้อมูลในตารางความสัมพันธ์รูปภาพหรือไม่ -->
                                    @if($item->product && $item->product->images && $item->product->images->count() > 0)
                                    @php
                                    // ดึงข้อมูลรูปภาพแถวแรกสุด (First Image) ออกมาเป็นตัวแทนโชว์ในหน้ารายการ
                                    $firstImage = $item->product->images->first();
                                    // สมมติว่าในตารางลูกตั้งชื่อคอลัมน์เก็บพาธว่า image_url หรือ image
                                    $imagePath = $firstImage->image_url ?? $firstImage->image;
                                    @endphp

                                    <img src="{{ str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/' . $imagePath) }}"
                                        alt="Product Image"
                                        class="w-12 h-12 object-cover rounded-md border border-gray-100 mx-auto shadow-sm">
                                    @else
                                    <div class="w-12 h-12 bg-gray-100 rounded-md border border-gray-100 flex items-center justify-center text-gray-400 text-[10px] mx-auto shadow-sm">No Image</div>
                                    @endif
                                </td>

                                <!-- 2. คอลัมน์แสดงชื่อ และ รหัสสินค้า -->
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-950">{{ $item->product_name }}</div>
                                    <!-- แสดงรหัสสินค้าด้านล่างชื่อ เพื่อความสแกนง่าย -->
                                    <div class="text-xs text-gray-400 mt-0.5 font-mono tracking-wider">SKU ID: #{{ $item->product_id }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5 font-mono tracking-wider">Options: #{{ $item->selected_options }}</div>
                                </td>

                                <td class="py-3 px-4 text-center text-gray-500">฿{{ number_format($item->price, 2) }}</td>
                                <td class="py-3 px-4 text-center font-bold text-gray-800">{{ $item->quantity }}</td>
                                <td class="py-3 px-4 text-right font-semibold text-gray-900">฿{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50/50 font-bold border-t border-gray-100">
                            <tr>
                                <td colspan="4" class="py-4 px-4 text-right text-gray-500 text-sm">ยอดเงินสุทธิทั้งสิ้น:</td>
                                <td class="py-4 px-4 text-right text-lg text-blue-600">฿{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>

</body>

</html>