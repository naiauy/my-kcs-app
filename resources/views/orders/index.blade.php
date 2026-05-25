<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการคำสั่งซื้อ - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

    <main class="max-w-6xl mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-950">📦 รายการคำสั่งซื้อของลูกค้า</h1>
            <a href="{{ route('products.index') }}" class="text-sm text-blue-600 hover:underline">ไปหน้าแรกของร้านค้า</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6 font-semibold">เลขที่ออเดอร์</th>
                        <th class="py-4 px-6 font-semibold">ชื่อลูกค้า</th>
                        <th class="py-4 px-6 font-semibold">เบอร์โทรศัพท์</th>
                        <th class="py-4 px-6 font-semibold">ยอดรวมสุทธิ</th>
                        <th class="py-4 px-6 font-semibold">สถานะ</th>
                        <th class="py-4 px-6 font-semibold">วันที่สั่งซื้อ</th>
                        <th class="py-4 px-6 font-semibold text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="py-4 px-6 font-bold text-gray-900">#{{ $order->id }}</td>
                            <td class="py-4 px-6 font-medium">{{ $order->customer_name }}</td>
                            <td class="py-4 px-6 text-gray-500">{{ $order->customer_phone }}</td>
                            <td class="py-4 px-6 font-bold text-blue-600">฿{{ number_format($order->total_amount, 2) }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold 
                                    {{ $order->status == 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-green-50 text-green-700 border border-green-200' }}">
                                    {{ $order->status == 'pending' ? 'รอชำระเงิน' : 'ชำระเงินแล้ว' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-400 text-xs">{{ $order->created_at->format('d/m/Y H:i') }} น.</td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('orders.show', $order->id) }}" class="bg-gray-100 hover:bg-blue-600 hover:text-white text-gray-600 text-xs px-3 py-1.5 rounded transition-colors inline-block font-medium">
                                    เปิดดูรายละเอียด
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">ยังไม่มีประวัติคำสั่งซื้อเข้ามาในระบบขณะนี้</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ตัวแบ่งหน้า Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </main>

</body>
</html>