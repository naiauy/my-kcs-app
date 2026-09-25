<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KCS Admin - @yield('title', 'ระบบจัดการร้านค้า')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans antialiased">

    <div class="min-h-screen flex">
        
        <!-- 📌 1. Admin Sidebar (เมนูด้านข้าง) -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0">
            <!-- Admin Logo Bar -->
            <div class="p-5 flex items-center gap-3 border-b border-slate-800">
                <img src="{{ asset('images/kcs-logo-small.jpg') }}" alt="KCS SHOP" class="h-8 w-auto rounded">
                <div>
                    <span class="font-bold text-white block text-base leading-tight">KCS Backoffice</span>
                    <span class="text-xs text-blue-400">Admin Control Panel</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 p-4 space-y-1 text-sm font-medium">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    📊 แผงควบคุม (Dashboard)
                </a>
                <a href="{{ route('products.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('products.create') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    📦 เพิ่มสินค้าใหม่
                </a>
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ request()->routeIs('orders.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    🛍️ รายการสั่งซื้อ (Orders)
                </a>
                
                <div class="pt-4 mt-4 border-t border-slate-800">
                    <a href="{{ route('products.index') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                        🏪 เปิดดูหน้าร้านค้า ↗
                    </a>
                </div>
            </nav>

            <!-- Admin Profile & Logout Bar -->
            <div class="p-4 border-t border-slate-800 flex items-center justify-between text-xs">
                <div>
                    <p class="font-bold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-slate-500 truncate max-w-[120px]">{{ Auth::user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-300 font-medium hover:underline">
                        ออก
                    </button>
                </form>
            </div>
        </aside>

        <!-- 📌 2. Admin Main Content Area (พื้นที่ทำงานด้านขวา) -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Header -->
            <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800">@yield('title', 'แผงควบคุม')</h2>
                <span class="text-xs bg-green-100 text-green-700 font-semibold px-2.5 py-1 rounded-full">
                    ● System Online
                </span>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-8 overflow-y-auto">
                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>