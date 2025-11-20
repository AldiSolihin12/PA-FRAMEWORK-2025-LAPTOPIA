<footer class="bg-slate-950/95 text-slate-300 py-16 border-t border-white/5">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid gap-12 md:grid-cols-4">
            <div class="md:col-span-2">
                <h3 class="text-2xl font-semibold text-white">Laptopia</h3>
                <p class="mt-4 text-sm leading-relaxed text-slate-400">
                    Curated laptops for creators, gamers, and professionals. Discover tuned configurations,
                    immersive displays, and precision-crafted hardware designed for your next big build.
                </p>
                <div class="mt-6 space-y-2 text-sm">
                    <p class="flex items-center gap-2"><span class="text-cyan-400">hello@laptopia.store</span></p>
                    <p class="flex items-center gap-2 text-slate-400">+62 812-3456-7890</p>
                    <p class="flex items-center gap-2 text-slate-400">Jl. Teknologi No. 88, Jakarta</p>
                </div>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-white">Shop</h4>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="{{ route('products.index') }}" class="hover:text-cyan-300 transition">All Laptops</a></li>
                    <li><a href="{{ route('products.index') }}#filters" class="hover:text-cyan-300 transition">Filters & Sorting</a></li>
                    <li><a href="{{ route('wishlist.index') }}" class="hover:text-cyan-300 transition">Wishlist</a></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:text-cyan-300 transition">Cart</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-white">Support</h4>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="{{ route('orders.index') }}" class="hover:text-cyan-300 transition">Order tracking</a></li>
                    {{-- <li><a href="{{ route('welcome') }}#contact" class="hover:text-cyan-300 transition">Store assistance</a></li> --}}
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/5 pt-6 text-xs text-slate-500 md:flex-row">
            <p>&copy; {{ date('Y') }} Laptopia. All rights reserved.</p>
            
        </div>
    </div>
</footer>