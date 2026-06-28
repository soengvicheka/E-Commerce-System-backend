<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#7c3aed',
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            600: '#7c3aed',
                            700: '#6d28d9',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link { transition: all 0.15s ease; }
        .card-stat { transition: transform 0.15s ease, box-shadow 0.15s ease; }
        .card-stat:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0">
            <div class="p-5 border-b border-slate-700/60">
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-primary-600 flex items-center justify-center text-white font-bold text-sm">V</div>
                    <div>
                        <h1 class="text-base font-bold text-white tracking-tight leading-tight">{{ config('app.name') }}</h1>
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wide">Admin Panel</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-3 space-y-0.5">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <span class="text-base">📊</span>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <span class="text-base">📁</span>
                    <span class="text-sm font-medium">Categories</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <span class="text-base">📦</span>
                    <span class="text-sm font-medium">Products</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <span class="text-base">🛒</span>
                    <span class="text-sm font-medium">Orders</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <span class="text-base">👥</span>
                    <span class="text-sm font-medium">Users</span>
                </a>
            </nav>

            <div class="p-3 border-t border-slate-700/60">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-red-600/90 text-slate-300 hover:text-white transition-colors text-left">
                        <span class="text-base">🚪</span>
                        <span class="text-sm font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">@yield('title')</h2>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-500 font-medium">{{ Auth::user()->name }}</span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-700 ring-1 ring-primary-100">Admin</span>
                </div>
            </header>

            <div class="p-6">
                @if(session('success'))
                    <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
