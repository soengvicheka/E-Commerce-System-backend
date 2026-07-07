<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="text-2xl font-bold text-purple-700">VKEN.shop</a>
                <nav class="hidden md:flex items-center gap-6">
                    <a href="/" class="text-gray-700 hover:text-purple-600 font-medium">Home</a>
                    <a href="#products" class="text-gray-700 hover:text-purple-600 font-medium">Products</a>
                    <a href="#categories" class="text-gray-700 hover:text-purple-600 font-medium">Categories</a>
                </nav>
                <div class="flex items-center gap-4">
                    <input type="text" placeholder="Search..." class="hidden md:block px-3 py-1.5 border rounded-lg text-sm focus:ring-2 focus:ring-purple-500">
                    <a href="{{ route('admin.login') }}" class="px-3 py-1.5 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700">Admin</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="text-white relative" style="background: url('/assets/hero.png') center/cover no-repeat;">
        <div class="max-w-7xl mx-auto px-4 py-24 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-5 tracking-tight">Discover Quality Products</h1>
            <p class="text-lg md:text-xl text-white/85 mb-10 max-w-3xl mx-auto leading-relaxed">Shop the latest trends with confidence. Free shipping over $500 and 30-day returns.</p>
            <div class="inline-flex items-center gap-4">
                <a href="#products" class="px-8 py-3 bg-[#7c3aed] hover:bg-[#6d28d9] text-white rounded-full font-semibold transition shadow-lg shadow-primary/20">Shop Now</a>
                <a href="#categories" class="px-8 py-3 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-full font-semibold transition">Browse All</a>
            </div>
        </div>
    </section>

    <!-- Value Props -->
    <section class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-2 md:grid-cols-5 gap-4 text-center text-sm">
            <div class="flex flex-col items-center gap-2">
                <span class="text-2xl">🚚</span>
                <span class="font-medium text-gray-700">Free Shipping</span>
                <span class="text-gray-500">Orders Over $500</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <span class="text-2xl">↩️</span>
                <span class="font-medium text-gray-700">30 Days Returns</span>
                <span class="text-gray-500">For Exchange</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <span class="text-2xl">🔒</span>
                <span class="font-medium text-gray-700">Secured Payment</span>
                <span class="text-gray-500">Cards Accepted</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <span class="text-2xl">🎁</span>
                <span class="font-medium text-gray-700">Special Gifts</span>
                <span class="text-gray-500">Contact Us</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <span class="text-2xl">🎧</span>
                <span class="font-medium text-gray-700">Support 24/7</span>
                <span class="text-gray-500">Anytime</span>
            </div>
        </div>
    </section>

    <!-- Feature Grid (Mobile-first from image 1) -->
    <section class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Shop By Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $category)
            <a href="#" class="bg-white rounded-xl p-6 border shadow-sm hover:shadow-md transition text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-purple-50 flex items-center justify-center text-3xl mb-3">📁</div>
                <h3 class="font-semibold text-gray-800">{{ $category->name }}</h3>
            </a>
            @endforeach
        </div>
    </section>

    <!-- Products -->
    <section id="products" class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Latest Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latest as $product)
            <div class="bg-white rounded-xl border shadow-sm overflow-hidden hover:shadow-md transition">
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">📦</div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 truncate">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500 mb-2">{{ $product->category->name ?? '' }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-purple-700">${{ number_format($product->sale_price ?? $product->price, 2) }}</span>
                        <button class="px-3 py-1 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700">Add to Cart</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Featured Banners -->
    @if($featured->count() > 0)
    <section class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Featured Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($featured->take(2) as $product)
            <div class="bg-gradient-to-r from-purple-500 to-blue-500 rounded-xl p-6 text-white flex items-center gap-6">
                <div class="flex-1">
                    <span class="px-2 py-1 rounded bg-white/20 text-xs">Featured</span>
                    <h3 class="text-xl font-bold mt-2">{{ $product->name }}</h3>
                    <p class="text-purple-100 mt-1">{{ $product->short_description }}</p>
                    <p class="text-2xl font-bold mt-3">${{ number_format($product->sale_price ?? $product->price, 2) }}</p>
                </div>
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-lg">
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif

    @section('scripts')
    <script>
        document.getElementById('app')?.remove();
    </script>
    @endsection
</body>
</html>
