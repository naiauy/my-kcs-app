<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ร้านคุณชมช๊อป - @yield('title', 'แหล่งรวมขายปลีกและส่งอุปกรณ์งานฝีมือ แฮนด์เมด และสินค้ากิ๊ฟช็อปออนไลน์ ที่ตอบโจทย์คนรักงานคราฟต์อย่างครบครัน')</title>

    <!-- Tailwind CSS (ผ่าน CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen">

    <div class="max-w-6xl mx-auto px-4 py-6">

        <!-- 🏷️ แถบเมนูหมวดหมู่สินค้า (Category Navigation) -->
        <div class="mb-8">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">หมวดหมู่สินค้า</h2>
            <div class="flex flex-wrap gap-2 pb-3 border-b border-gray-100">

                <!-- ปุ่ม "ทั้งหมด" -->
                <a href="{{ route('products.index') }}"
                    class="px-4 py-2 text-sm font-medium rounded-xl border transition-all {{ !request()->has('category') ? 'bg-blue-600 border-blue-600 text-white shadow-sm shadow-blue-100' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                    🛍️ ทั้งหมด
                </a>

                <!-- วนลูปดึงหมวดหมู่จากฐานข้อมูลมาสร้างปุ่มเมนู -->
                @foreach ($categories ?? [] as $cat)
                <a href="{{ route('products.index', ['category' => $cat->id]) }}"
                    class="px-4 py-2 text-sm font-medium rounded-xl border transition-all {{ request('category') == $cat->id ? 'bg-blue-600 border-blue-600 text-white shadow-sm shadow-blue-100' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                    {{ $cat->name }}
                </a>
                @endforeach

            </div>
        </div>

        <!-- 🔍 ฟอร์มค้นหาสินค้า (Search Bar - จัดกึ่งกลางหน้าจอ) -->
        <div class="flex justify-center w-full mb-8">
            <form action="{{ route('products.index') }}" method="GET" class="w-full max-w-lg">
                <!-- คงค่าหมวดหมู่เดิมไว้ถ้ามีการเลือกอยู่ -->
                @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <div class="flex gap-2 items-center">
                    <div class="relative w-full">
                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="ค้นหาชื่อสินค้าที่ต้องการ..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm shadow-sm transition-all">

                        <!-- SVG Icon Search -->
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium shadow-sm transition-colors flex-shrink-0">
                        ค้นหา
                    </button>

                    @if(request('search'))
                    <a href="{{ route('products.index', ['category' => request('category')]) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors flex-shrink-0">
                        ล้างค่า
                    </a>
                    @endif
                </div>
            </form>
        </div>

    </div>

    <!-- ส่วนหัวของเว็บไซต์ (Navbar) -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                    role="link" tabindex="0" style="cursor:pointer;">
                    <img src="{{ asset('images/kcs-logo-small.jpg') }}" alt="ร้านคุณชมช๊อป" class="h-8 w-auto">
                    <span class="text-xl font-bold text-blue-600 tracking-wide">ร้านคุณชมช๊อป</span>
                </div>
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


    <!-- 2. ส่วนเนื้อหาที่จะเปลี่ยนไปตามแต่ละหน้า -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- 3. ส่วน Footer (วางโค้ด Footer ที่เราเพิ่งทำไว้ตรงนี้) -->
    <!-- <footer> Section for KCS SHOP -->
    <footer class="bg-slate-900 text-gray-300 pt-12 pb-8 border-t border-slate-800 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">

                <!-- Column 1: About & Social Links -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/kcs-logo-small.jpg') }}" alt="KCS SHOP Logo" class="h-8 w-auto rounded">
                        <span class="text-xl font-bold text-white tracking-wide">ร้านคุณชมช๊อป</span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        ศูนย์รวมสินค้าไอทีและแฟชั่นคุณภาพ ตอบโจทย์ทุกไลฟ์สไตล์การใช้งาน พร้อมบริการหลังการขายที่ใส่ใจทุกรายละเอียด
                    </p>

                    <!-- Social Media Icons (SVG Inline) -->
                    <div class="pt-2">
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">ติดตามเราได้ที่</span>
                        <div class="flex space-x-3">
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/www.khunchomshop.net/?locale=th_TH" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition-colors duration-200" aria-label="Facebook">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            <!-- LINE -->
                            <a href="http://line.me/ti/p/%40elt7287y" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center text-gray-400 hover:bg-green-500 hover:text-white transition-colors duration-200" aria-label="LINE">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M19.34 10.14c0-3.9-4.18-7.07-9.34-7.07S0 6.24 0 10.14c0 3.49 3.32 6.42 7.8 6.96.3.07.72.2.82.47.1.25.06.63.03.88l-.13.82c-.04.25-.19.98.86.53 1.05-.44 5.67-3.34 7.73-5.72 1.48-1.61 2.23-3.23 2.23-4.94zm-12.87 2.1h-1.3v-3.8h1.3v3.8zm3.2 0h-1.3v-3.8h.9l1.4 2.1v-2.1h1.3v3.8h-.9l-1.4-2.1v2.1zm4.4 0h-2.5v-3.8h2.5v.9h-1.2v.5h1.1v.9h-1.1v.6h1.2v.9zm2.7 0h-1.3v-3.8h1.3v3.8z" />
                                </svg>
                            </a>
                            <!-- Instagram -->
                            <a href="https://www.instagram.com/khunchomshop/" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center text-gray-400 hover:bg-pink-600 hover:text-white transition-colors duration-200" aria-label="Instagram">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                            </a>
                            <!-- Shopee / TikTok -->
                            <a href="https://www.tiktok.com/@khunchomshop0?is_from_webapp=1&sender_device=pc" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center text-gray-400 hover:bg-slate-700 hover:text-white transition-colors duration-200" aria-label="TikTok">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.525.001c1.31 0 2.57.25 3.73.722v4.887c-.722-.387-1.545-.6-2.422-.6-2.87 0-5.2 2.33-5.2 5.2s2.33 5.2 5.2 5.2c2.45 0 4.51-1.7 5.04-4h-5.04V6.522h10.02c.07.56.11 1.14.11 1.73 0 6.63-5.37 12-12 12S0 14.882 0 8.252 5.37.001 12.005.001h.52zm7.155 3.327c-.89-.6-1.92-.98-3.03-1.09V.081c1.78.21 3.42.87 4.8 1.88l-1.77 1.366z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4 border-l-2 border-blue-500 pl-2">ลิงก์ด่วน</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('products.index') }}" class="hover:text-blue-400 transition-colors">สินค้าทั้งหมด</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">หมวดหมู่สินค้า</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">โปรโมชั่นพิเศษ</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">แจ้งชำระเงิน</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">ติดตามสถานะพัสดุ</a></li>
                    </ul>
                </div>

                <!-- Column 3: Customer Care / Services -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4 border-l-2 border-blue-500 pl-2">บริการของเรา</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>รับประกันสินค้าแท้ 100%</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span>จัดส่งด่วนทั่วประเทศ</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>เปลี่ยนคืนภายใน 7 วัน</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span>ระบบชำระเงินปลอดภัย</span>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Contact Point -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4 border-l-2 border-blue-500 pl-2">ติดต่อเรา</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-400">
                                <a href="https://maps.app.goo.gl/MvPFYZLBZW22jaVb9" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">
                                    กรุงเทพมหานคร ประเทศไทย
                                </a>
                            </span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <a href="tel:0897260064" class="text-gray-400 hover:text-white transition-colors">089-726-0064</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <a href="mailto:khunchomshop@gmail.com" class="text-gray-400 hover:text-white transition-colors">khunchomshop@gmail.com</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-400">จันทร์ - เสาร์: 09:00 - 15:00 น.</span>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Bar: Copyright & Payment Channels -->
            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                <p>&copy; {{ date('Y') }} Khunchomshop. All rights reserved.</p>
                <div class="flex items-center space-x-4">
                    <span>รองรับการชำระเงิน:</span>
                    <span class="bg-slate-800 px-2 py-1 rounded text-gray-300 font-medium">PromptPay</span>
                    <span class="bg-slate-800 px-2 py-1 rounded text-gray-300 font-medium">Bank Transfer</span>
                </div>
            </div>
        </div>
    </footer>
    <form method="POST" action="{{ route('logout') }}" class="inline">
        @csrf
        <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800 hover:underline">
            ออกจากระบบ
        </button>
    </form>
</body>

</html>