<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้าใหม่หลังบ้าน</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen py-12">

    <div class="max-w-3xl mx-auto px-4">
        <!-- บล็อกแจ้งเตือน Success / Error -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-medium">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl font-medium">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            <h1 class="text-xl font-bold text-gray-950 mb-6">📦 ระบบเพิ่มสินค้าใหม่ในร้านค้า</h1>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- ส่วนข้อมูลสินค้าหลัก -->
                <!-- ค้นหาท่อนกรอกชื่อและราคา แล้วปรับให้มีช่องสต็อกเพิ่มเข้ามาแบบนี้ครับ -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ชื่อสินค้า <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="เช่น เสื้อยืด Oversize">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">ราคาสินค้า (บาท) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" step="0.01" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="เช่น 350.00">
                    </div>
                    <!-- 🎯 ช่องกรอกจำนวนสต็อกที่เพิ่มเข้ามาใหม่ -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">จำนวนสต็อกสินค้า <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="10" min="0" required class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="เช่น 100">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">รายละเอียดสินค้า</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="ระบุสรรพคุณสินค้าสั้นๆ..."></textarea>
                </div>

                <!-- 📸 ส่วนอัปโหลดรูปภาพสินค้า (อัปโหลดได้หลายไฟล์พร้อมกัน) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">รูปภาพสินค้า (เลือกได้หลายรูปพร้อมกัน)</label>
                    <input type="file" name="images[]" multiple class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-xs text-gray-400 mt-1">สามารถกด Ctrl หรือ Shift ค้างไว้ขณะเลือกเพื่ออัปโหลดหลายรูปได้ครับ</p>
                </div>

                <hr class="border-gray-100">

                <!-- ⚙️ ส่วนของอินพุตตัวเลือกสินค้า (Dynamic Options) -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-sm font-bold text-gray-950">🛠️ ตัวเลือกของสินค้า (สี หรือ ขนาด)</label>
                        <button type="button" id="add-option-btn" class="text-xs bg-blue-50 text-blue-600 font-bold px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors">
                            ➕ เพิ่มแถวตัวเลือก
                        </button>
                    </div>

                    <!-- กล่องบรรจุแถวตัวเลือก (จะถูกเพิ่มด้วย JavaScript) -->
                    <div id="options-container" class="space-y-3">
                        <!-- แถวเริ่มต้นแถวแรกสุดเป็นตัวอย่างให้แอดมินใช้ง่ายขึ้น -->
                        <div class="flex items-center space-x-2 option-row bg-gray-50/50 p-3 rounded-xl border border-gray-100">
                            <select name="options[0][type]" class="border border-gray-200 rounded-lg p-2 text-sm bg-white focus:outline-none">
                                <option value="color">สี (Color)</option>
                                <option value="size">ขนาด / ความจุ (Size)</option>
                            </select>
                            <input type="text" name="options[0][value]" class="flex-1 border border-gray-200 rounded-lg p-2 text-sm focus:outline-none" placeholder="เช่น สีขาวมินิมอล หรือ Size XL">
                            <button type="button" class="text-red-500 hover:text-red-700 font-medium text-sm px-2 remove-option-btn">ลบ</button>
                        </div>
                    </div>
                </div>

                <!-- ปุ่มส่งฟอร์ม -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl text-sm font-bold shadow-md hover:bg-blue-700 transition-all">
                        💾 บันทึกสินค้าเข้าสู่ระบบ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 💡 สคริปต์ JavaScript เติมฟอร์มตัวเลือกอัตโนมัติ -->
    <script>
        let optionIndex = 1;
        const container = document.getElementById('options-container');
        const addBtn = document.getElementById('add-option-btn');

        // เมื่อกดปุ่ม "เพิ่มแถวตัวเลือก"
        addBtn.addEventListener('click', () => {
            const row = document.createElement('div');
            row.className = 'flex items-center space-x-2 option-row bg-gray-50/50 p-3 rounded-xl border border-gray-100';
            row.innerHTML = `
                <select name="options[${optionIndex}][type]" class="border border-gray-200 rounded-lg p-2 text-sm bg-white focus:outline-none">
                    <option value="color">สี (Color)</option>
                    <option value="size">ขนาด / ความจุ (Size)</option>
                </select>
                <input type="text" name="options[${optionIndex}][value]" class="flex-1 border border-gray-200 rounded-lg p-2 text-sm focus:outline-none" placeholder="ระบุตัวเลือก...">
                <button type="button" class="text-red-500 hover:text-red-700 font-medium text-sm px-2 remove-option-btn">ลบ</button>
            `;
            container.appendChild(row);
            optionIndex++;
        });

        // เมื่อกดปุ่ม "ลบแถวตัวเลือก"
        container.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-option-btn')) {
                e.target.closest('.option-row').remove();
            }
        });
    </script>
</body>

</html>