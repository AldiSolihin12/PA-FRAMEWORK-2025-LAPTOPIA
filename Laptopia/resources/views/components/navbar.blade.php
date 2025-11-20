@php
    use App\Models\Order;
    use Illuminate\Support\Facades\Auth;

    $cartCount = collect(session('cart.items', []))->sum('quantity');

    $orderIndicatorCount = 0;

    if (Auth::check()) {
        $user = Auth::user();

        $relevantStatuses = [
            Order::STATUS_PENDING,
            Order::STATUS_CONFIRMED,
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
        ];

        if ($user->hasAnyRole(['worker'])) {
            $orders = Order::with(['items', 'user.reviews'])
                ->whereIn('status', $relevantStatuses)
                ->get();

            $reviewCache = [];

            foreach ($orders as $order) {
                if (in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_CONFIRMED, Order::STATUS_SHIPPED], true)) {
                    $orderIndicatorCount++;
                    continue;
                }

                $orderUserId = $order->user_id;

                if (! isset($reviewCache[$orderUserId])) {
                    $reviewCache[$orderUserId] = $order->user
                        ? $order->user->reviews->pluck('product_id')->all()
                        : [];
                }

                $reviewedMap = $reviewCache[$orderUserId] ? array_flip($reviewCache[$orderUserId]) : [];

                $needsReview = $order->items->contains(function ($item) use ($reviewedMap) {
                    return $item->product_id && ! isset($reviewedMap[$item->product_id]);
                });

                if ($needsReview) {
                    $orderIndicatorCount++;
                }
            }
        } else {
            $reviewedProductIds = $user->reviews()->pluck('product_id')->all();
            $reviewedProductMap = $reviewedProductIds ? array_flip($reviewedProductIds) : [];

            $orders = Order::with(['items'])
                ->where('user_id', $user->id)
                ->whereIn('status', $relevantStatuses)
                ->get();

            foreach ($orders as $order) {
                if (in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_CONFIRMED, Order::STATUS_SHIPPED], true)) {
                    $orderIndicatorCount++;
                    continue;
                }

                $needsReview = $order->items->contains(function ($item) use ($reviewedProductMap) {
                    return $item->product_id && ! isset($reviewedProductMap[$item->product_id]);
                });

                if ($needsReview) {
                    $orderIndicatorCount++;
                }
            }
        }
    }
@endphp

<script>
document.getElementById('userMenuButton').addEventListener('click', function () {
    document.getElementById('userDropdown').classList.toggle('hidden');
});

// Klik di luar dropdown → tutup
document.addEventListener('click', function (e) {
    const btn = document.getElementById('userMenuButton');
    const dropdown = document.getElementById('userDropdown');

    if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>

<nav class="fixed inset-x-0 top-0 z-50 bg-slate-950/90 backdrop-blur-xl border-b border-white/5">
    <div class="mx-auto flex w-full max-w-11/12 items-center justify-between gap-4 px-4 py-4">

        {{-- LOGO --}}
        <a href="{{ route('welcome') }}" class="flex items-center gap-3 select-none">
            <div class="h-12 w-12 rounded-xl flex items-center justify-center">
            <img 
                src="{{ asset('images/Laptopia.png') }}" 
                alt="Laptopia" 
                class="h-10 w-10 object-contain"
            >
        </div>

            <span class="text-lg font-semibold text-white">Laptopia</span>
        </a>

        {{-- CENTER: SEARCHBAR --}}
        <form method="GET" action="{{ route('products.index') }}" 
            class="hidden md:flex flex-1 max-w-3xl items-center rounded-xl bg-white/10 border border-white/10 px-4 py-2 shadow-inner focus-within:border-cyan-400 transition">

            <input type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Search laptops, brand, chipset..."
                class="w-full bg-transparent text-sm text-white placeholder:text-slate-400 focus:outline-none" />

            <button type="submit" 
                class="ml-2 cursor-pointer inline-flex items-center rounded-lg bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow-md hover:scale-[1.03] transition">
                Search
            </button>
        </form>

        {{-- RIGHT: ACTION BUTTONS --}}
        <div class="hidden md:flex items-center gap-5 text-slate-200">
            {{-- CART --}}
            <a href="{{ route('cart.index') }}" class="relative flex items-center gap-2 hover:text-white">
                <span>Cart</span>
                @if ($cartCount > 0)
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-cyan-400 text-xs font-bold text-slate-900">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('products.index') }}" class="hover:text-white">Catalogue</a>
            {{-- AUTH --}}
            @auth
                <a href="{{ route('wishlist.index') }}" class="hover:text-white">Wishlist</a>
                <a href="{{ route('orders.index') }}" class="relative flex items-center gap-2 hover:text-white">
                    <span>Orders</span>
                    @if ($orderIndicatorCount > 0)
                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-cyan-400 text-xs font-bold text-slate-900">
                            {{ $orderIndicatorCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <div class="flex items-center gap-2 text-sm font-semibold text-cyan-400">
                        {{ auth()->user()->name }}
                    </div>
                @endauth

                @if (auth()->user()->hasAnyRole(['admin']))
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-cyan-400/30 bg-gradient-to-r from-cyan-400/10 via-indigo-500/10 to-transparent px-4 py-2 text-sm font-semibold text-cyan-200 shadow-lg shadow-cyan-500/10 transition hover:border-cyan-300 hover:text-cyan-100 hover:shadow-cyan-400/20">
                        <span class="h-2 w-2 rounded-full bg-cyan-400"></span>
                        Dashboard
                    </a>
                @endif  

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                        class="rounded-xl bg-gradient-to-r from-indigo-500 via-sky-500 to-cyan-400 px-4 py-2 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/30 hover:scale-[1.02] transition cursor-pointer">
                        Logout
                    </button>
                </form>

            @else
                <a href="{{ route('login') }}" class="hover:text-white">Sign in</a>
                <a href="{{ route('register') }}" 
                   class="rounded-xl bg-gradient-to-r from-indigo-500 via-sky-500 to-cyan-400 px-4 py-2 text-sm font-semibold text-slate-950">
                    Create account
                </a>
            @endauth
        </div>

        {{-- MOBILE MENU BUTTON --}}
        <button id="nav-toggle" class="md:hidden rounded-lg border border-white/10 p-2 text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

    </div>

    {{-- MOBILE MENU DROPDOWN --}}
    <div id="nav-menu" class="hidden md:hidden border-t border-white/10 bg-slate-950 px-6 pb-6">
        
        {{-- MOBILE SEARCHBAR --}}
        <form method="GET" action="{{ route('products.index') }}" class="flex items-center mt-4 rounded-xl bg-white/10 border border-white/10 px-4 py-2 shadow-inner">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search..." class="w-full bg-transparent text-sm text-white placeholder:text-slate-400 focus:outline-none" />
            <button type="submit" class="ml-2 rounded-md bg-cyan-400 px-3 py-1 text-sm font-semibold text-slate-900">Go</button>
        </form>

        <ul class="mt-4 space-y-4 text-sm font-semibold text-slate-200">
            <li><a href="{{ route('welcome') }}#home" class="block rounded-lg px-3 py-2 hover:bg-white/10">Home</a></li>
            <li><a href="{{ route('products.index') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">Catalogue</a></li>
            <li><a href="{{ route('products.index') }}#filters" class="block rounded-lg px-3 py-2 hover:bg-white/10">Filters</a></li>
            <li><a href="{{ route('welcome') }}#contact" class="block rounded-lg px-3 py-2 hover:bg-white/10">Support</a></li>
            <li><a href="{{ route('cart.index') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">Cart ({{ $cartCount }})</a></li>

            @auth
                <li><a href="{{ route('wishlist.index') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">Wishlist</a></li>
                <li>
                    <a href="{{ route('orders.index') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">
                        Orders @if ($orderIndicatorCount > 0) ({{ $orderIndicatorCount }}) @endif
                    </a>
                </li>
                @if (auth()->user()->hasRole('admin'))
                    <li><a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">Dashboard</a></li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-lg px-3 py-2 text-left hover:bg-white/10">Logout</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">Sign in</a></li>
                <li><a href="{{ route('register') }}" class="block rounded-lg px-3 py-2 hover:bg-white/10">Create account</a></li>
            @endauth
        </ul>
    </div>
</nav>
