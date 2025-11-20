@extends('components.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

<style>
    /* Hide default scrollbar */
    .brand-scroll::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    /* Custom floating scrollbar container */
    .brand-scroll {
        scrollbar-width: thin;
        scrollbar-color: #38bdf8 transparent;
        position: relative;
    }

    /* Custom scrollbar overlay */
    .brand-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .brand-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .brand-scroll::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #38bdf8, #4f46e5);
        border-radius: 10px;
    }

    .search-highlight {
        border: 1px solid rgba(56, 189, 248, 0.4);
        /* cyan-400 */
        background: linear-gradient(135deg,
                rgba(56, 189, 248, 0.08),
                rgba(99, 102, 241, 0.08));
        box-shadow: 0 0 18px rgba(56, 189, 248, 0.18);
        transition: all 0.3s ease;
    }

    .search-highlight:hover {
        box-shadow: 0 0 22px rgba(56, 189, 248, 0.4);
        border-color: rgba(56, 189, 248, 0.7);
    }

    .search-highlight:focus {
        background: linear-gradient(135deg,
                rgba(56, 189, 248, 0.14),
                rgba(99, 102, 241, 0.14));
        box-shadow: 0 0 28px rgba(56, 189, 248, 0.6);
    }

    .dropdown-clean {
        background: rgba(15, 23, 42, 0.55);
        /* slate-900/50 */
        border: 1px solid rgba(56, 189, 248, 0.3);
        color: #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        transition: all 0.25s ease;
        backdrop-filter: blur(6px);
    }

    .dropdown-clean:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 14px rgba(56, 189, 248, 0.45);
    }

    .dropdown-clean option {
        background: #0f172a;
        /* slate-900 */
    }
</style>

@section('content')
    {{-- HEADER --}}
    <section class="bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 py-20">
        <div class="mx-auto max-w-6xl px-6">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-3xl">
                    <span class="text-xs uppercase tracking-[0.3em] text-cyan-400">Laptopia catalogue</span>
                    <h1 class="mt-4 text-4xl font-bold text-white md:text-5xl">Browse the complete Laptopia lineup</h1>
                    <p class="mt-4 text-sm text-slate-400 md:text-base">
                        Filter by brand, configuration, or price to uncover the exact machine that fits your workflow.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="py-20" id="filters">
        <div class="mx-auto max-w-11/12 px-6">
            {{-- ensure items start at top so sticky works --}}
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-[320px_minmax(0,_1fr)] lg:items-start">

                {{-- SIDEBAR --}}
                <aside
                    class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl shadow-slate-950/40 backdrop-blur self-start lg:sticky lg:top-32">
                    <form method="GET" action="{{ route('products.index') }}" class="space-y-8">

                        {{-- SEARCH --}}
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Search</label>
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                                placeholder="Search laptops..."
                                class="search-highlight w-full rounded-xl px-4 py-3 text-sm text-slate-100 placeholder:text-cyan-300/60" />

                        </div>

                        {{-- BRAND FILTER --}}
                        <div class="space-y-3">
                            <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Brand</label>
                            <div
                                class="flex flex-col gap-2 max-h-44 overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent brand-scroll">
                                @foreach ($brands as $brand)
                                    <label
                                        class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-slate-100">
                                        <input type="checkbox" name="brand[]" value="{{ $brand }}"
                                            class="rounded border-white/20 bg-slate-900 text-cyan-400 focus:ring-cyan-500"
                                            @checked(in_array($brand, $filters['brand'] ?? []))>
                                        <span>{{ $brand }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- CATEGORY --}}
                        <div class="space-y-2">
                            <label for="category" class="text-xs uppercase tracking-[0.3em] text-slate-500">Category</label>
                            <select id="category" name="category"
                                class="w-full rounded-xl dropdown-clean border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none focus:ring focus:ring-cyan-400/30">
                                <option value="">All categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PRICE RANGE --}}
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Price (USD)</label>

                            <div class="flex items-center gap-3 text-sm text-slate-200">
                                <input type="number" name="price_min" placeholder="Min"
                                    value="{{ $filters['price_min'] ?? '' }}"
                                    min="{{ isset($priceRange) ? number_format($priceRange->min_price, 0, '', '') : '' }}"
                                    class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm focus:border-cyan-400 focus:outline-none" />

                                <span class="text-xs text-slate-500">to</span>

                                <input type="number" name="price_max" placeholder="Max"
                                    value="{{ $filters['price_max'] ?? '' }}"
                                    max="{{ isset($priceRange) ? number_format($priceRange->max_price, 0, '', '') : '' }}"
                                    class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm focus:border-cyan-400 focus:outline-none" />
                            </div>

                            @if (isset($priceRange))
                                <p class="text-xs text-slate-500">Range: ${{ number_format($priceRange->min_price, 0) }} -
                                    ${{ number_format($priceRange->max_price, 0) }}</p>
                            @endif
                        </div>

                        {{-- SORT BY --}}
                        <div class="space-y-2">
                            <label for="sort" class="text-xs uppercase tracking-[0.3em] text-slate-500">Sort by</label>
                            <select id="sort" name="sort"
                                class="w-full rounded-xl dropdown-clean border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none">
                                <option value="terbaru" @selected(($filters['sort'] ?? '') === 'terbaru')>Newest</option>
                                <option value="termurah" @selected(($filters['sort'] ?? '') === 'termurah')>Price: Low to High</option>
                                <option value="termahal" @selected(($filters['sort'] ?? '') === 'termahal')>Price: High to Low</option>
                                <option value="rating" @selected(($filters['sort'] ?? '') === 'rating')>Top Rated</option>
                            </select>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="flex flex-col gap-3 pt-2">
                            <button type="submit"
                                class="rounded-xl bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-500 px-5 py-3 text-sm font-semibold text-slate-950 shadow-lg hover:scale-[1.02] transition">
                                Apply Filters
                            </button>

                            <a href="{{ route('products.index') }}"
                                class="text-xs font-semibold text-cyan-300 hover:text-cyan-200">Reset Filters</a>
                        </div>

                    </form>
                </aside>

                {{-- PRODUCT LIST --}}
                <div>
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                        <div>
                            <span class="text-xs uppercase tracking-[0.3em] text-cyan-400">Results</span>
                            <h2 class="mt-3 text-3xl font-bold text-white md:text-4xl">{{ $products->total() }} laptops
                            </h2>
                            <p class="mt-2 text-sm text-slate-400">Showing
                                {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} devices</p>
                        </div>
                        <div class="text-sm text-slate-400">
                            {{ $products->links() }}
                        </div>
                    </div>

                    <div class="mt-12 grid gap-10 sm:grid-cols-2 xl:grid-cols-3">
                        @forelse ($products as $product)
                            @php
                                $image = Str::startsWith($product->image, ['http://', 'https://'])
                                    ? $product->image
                                    : Storage::url($product->image);
                                $rating = round($product->reviews_avg_rating ?? 0, 1);
                                $reviewCount = $product->reviews_count ?? 0;
                            @endphp
                            <article
                                class="group flex h-full flex-col overflow-hidden rounded-3xl border border-white/10 bg-slate-900/70 backdrop-blur transition hover:border-cyan-400/40">
                                <div class="relative overflow-hidden">
                                    <img src="{{ $image }}" alt="{{ $product->name }}"
                                        class="h-56 w-full object-cover transition duration-500 group-hover:scale-105" />
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-60">
                                    </div>

                                    <div
                                        class="absolute left-5 top-5 flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-200">
                                        @if ($product->category)
                                            <span
                                                class="rounded-full bg-white/10 px-3 py-1 backdrop-blur">{{ $product->category->name }}</span>
                                        @endif
                                        <span
                                            class="rounded-full bg-cyan-400/80 px-3 py-1 text-slate-950">{{ $product->brand }}</span>
                                    </div>
                                </div>

                                <div class="flex flex-1 flex-col gap-5 p-6">
                                    <div class="space-y-2">
                                        <h3 class="text-lg font-semibold text-white">{{ $product->name }}</h3>
                                        <p class="text-sm leading-relaxed text-slate-400">
                                            {{ Str::limit($product->details->description ?? 'Premium laptop engineered for productivity and creativity.', 120) }}
                                        </p>
                                    </div>

                                    <ul class="grid gap-2 text-xs text-slate-300">
                                        <li class="flex items-center justify-between"><span>Processor</span><span
                                                class="font-semibold text-slate-100">{{ Str::limit($product->details->processor ?? '-', 22) }}</span>
                                        </li>
                                        <li class="flex items-center justify-between"><span>Graphics</span><span
                                                class="font-semibold text-slate-100">{{ Str::limit($product->details->graphics ?? '-', 22) }}</span>
                                        </li>
                                        <li class="flex items-center justify-between"><span>Memory</span><span
                                                class="font-semibold text-slate-100">{{ $product->details->ram ?? '-' }}</span>
                                        </li>
                                    </ul>

                                    <div class="flex items-center justify-between text-xs text-cyan-300">
                                        <span class="inline-flex items-center gap-1 text-slate-300">★
                                            {{ number_format($rating, 1) }} <span
                                                class="text-slate-500">({{ $reviewCount }})</span></span>
                                        <span class="text-slate-400">{{ $product->stock }} in stock</span>
                                    </div>

                                    <div class="mt-auto flex items-center justify-between">
                                        <div class="text-2xl font-bold text-cyan-300">
                                            ${{ number_format($product->price, 2) }}</div>
                                        <div class="flex items-center gap-3">
                                            <form method="POST" action="{{ route('cart.store', $product) }}">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-xl border border-cyan-400/60 px-3 py-2 text-xs font-semibold text-cyan-300 transition hover:bg-cyan-400/10 cursor-pointer">Add
                                                    to cart</button>
                                            </form>
                                            <a href="{{ route('product.details', $product->slug) }}"
                                                class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-4 py-2 text-xs font-semibold text-slate-950 shadow hover:scale-[1.02]">View</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <p class="col-span-full text-center text-sm text-slate-400">No laptops match your filters.</p>
                        @endforelse
                    </div>

                    <div class="mt-12 text-center text-slate-400">
                        {{ $products->links() }}
                    </div>
                </div>
            </div> {{-- end grid --}}
        </div> {{-- end container --}}
    </section>
@endsection
