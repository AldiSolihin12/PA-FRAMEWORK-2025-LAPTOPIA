@extends('components.layouts.app')

@section('content')
<section class="py-12 px-6 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 min-h-[70vh] text-white">
    <div class="max-w-6xl mx-auto space-y-10">

        {{-- HEADER --}}
        <header class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-[0_0_40px_-10px_rgba(56,189,248,0.3)]">
            <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 via-indigo-500/10 to-transparent"></div>

            <div class="relative flex flex-col gap-8 px-8 py-10 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-4">
                    <span class="inline-flex items-center gap-2 px-4 py-1 text-xs font-semibold rounded-full bg-white/10 border border-white/20 tracking-[0.25em] text-cyan-300">
                        <span class="h-2 w-2 bg-cyan-400 rounded-full animate-pulse"></span>
                        DASHBOARD
                    </span>

                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-white">Laptopia Admin Panel</h1>
                        <p class="mt-2 max-w-xl text-sm md:text-base text-slate-300">
                            Manage inventory, monitor performance, and keep the Laptopia catalog up-to-date.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('admin.products.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-500 text-slate-950 px-6 py-3 font-semibold shadow-lg shadow-cyan-500/40 hover:scale-105 transition">
                        <svg class="h-4 w-4" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 5v14m7-7H5"/>
                        </svg>
                        New Product
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/10 px-6 py-3 font-semibold hover:bg-white/20 transition">
                        <svg class="h-4 w-4" stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        Inventory
                    </a>
                </div>
            </div>

            {{-- SMALL STATS --}}
            <div class="relative border-t border-white/10 px-8 py-8">
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    {{-- STAT CARD --}}
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs tracking-wide text-cyan-300 uppercase font-semibold">Products</p>
                                <p class="mt-3 text-3xl font-bold text-white">{{ $stats['products'] }}</p>
                            </div>

                            <div class="h-12 w-12 rounded-2xl bg-cyan-500/20 flex items-center justify-center text-cyan-300">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M3 7l9-4 9 4-9 4-9-4z"/>
                                    <path d="M3 12l9 4 9-4"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-400">Total products available in the store.</p>
                    </div>

                    {{-- More cards --}}
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs tracking-wide text-cyan-300 uppercase font-semibold">Categories</p>
                                <p class="mt-3 text-3xl font-bold text-white">{{ $stats['categories'] }}</p>
                            </div>

                            <div class="h-12 w-12 rounded-2xl bg-cyan-500/20 flex items-center justify-center text-cyan-300">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <polygon points="12 2 2 22 22 22"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-400">All active product categories.</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs tracking-wide text-cyan-300 uppercase font-semibold">Users</p>
                                <p class="mt-3 text-3xl font-bold text-white">{{ $stats['users'] }}</p>
                            </div>

                            <div class="h-12 w-12 rounded-2xl bg-cyan-500/20 flex items-center justify-center text-cyan-300">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-400">Total registered Laptopia users.</p>
                    </div>

                </div>
            </div>
        </header>

        {{-- MAIN TABLE + SIDEBAR --}}
        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Latest Products --}}
            <section class="rounded-3xl bg-white/5 border border-white/10 backdrop-blur-xl lg:col-span-2">
                <div class="flex flex-col gap-4 border-b border-white/10 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-white">Latest Products</h2>
                        <p class="text-sm text-slate-400">Newest items added to the Laptopia catalog.</p>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.categories.index') }}"
                           class="px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-xs uppercase tracking-wide hover:bg-white/20 transition">
                            Categories
                        </a>

                        <a href="{{ route('admin.products.index') }}"
                           class="px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-xs uppercase tracking-wide hover:bg-white/20 transition">
                            Products
                        </a>
                        
                        <a href="{{ route('admin.workers.index') }}"
                           class="px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-xs uppercase tracking-wide hover:bg-white/20 transition">
                            Workers
                        </a>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-cyan-300">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-cyan-300">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-cyan-300">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-cyan-300">Created</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/5">
                            @forelse ($latestProducts as $product)
                                <tr class="transition hover:bg-white/10">
                                    <td class="px-6 py-4 text-sm font-semibold text-white">
                                        {{ $product->name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $product->category?->name ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm font-semibold text-cyan-300">
                                        ${{ number_format($product->price, 0) }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-400">
                                        {{ $product->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-400">
                                        No products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Right Sidebar --}}
            <section class="rounded-3xl bg-white/5 border border-white/10 backdrop-blur-xl p-6 flex flex-col">
                <h2 class="text-lg font-semibold text-white mb-2">Operations Snapshot</h2>
                <p class="text-sm text-slate-400 mb-6">Latest product activity and updates.</p>

                @php
                    $highlightProduct = $latestProducts->first();
                @endphp

                @if ($highlightProduct)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wider text-cyan-300">Newest Product</p>

                        <h3 class="mt-2 text-base font-semibold text-white">{{ $highlightProduct->name }}</h3>
                        <p class="text-sm text-slate-400">{{ $highlightProduct->category?->name ?? 'Uncategorized' }}</p>

                        <span class="mt-3 inline-block rounded-xl bg-cyan-500/20 text-cyan-300 px-3 py-1 text-sm font-semibold">
                            ${{ number_format($highlightProduct->price, 0) }}
                        </span>

                        <a href="{{ route('admin.products.edit', $highlightProduct) }}"
                           class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-cyan-300 hover:text-cyan-200 transition">
                            Update product →
                        </a>
                    </div>

                    {{-- List --}}
                    <div class="mt-6">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-cyan-300">Recent activity</h3>
                        <ul class="mt-4 space-y-3">
                            @foreach ($latestProducts->skip(1)->take(4) as $product)
                                <li class="flex justify-between items-center rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                                    <div>
                                        <p class="text-sm font-semibold text-white">{{ $product->name }}</p>
                                        <p class="text-xs text-slate-400">Added {{ $product->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="rounded-xl bg-cyan-500/20 text-cyan-300 px-3 py-1 text-xs font-semibold">
                                        ${{ number_format($product->price, 0) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-sm text-slate-400">No data yet. Add a product to see activity.</p>
                @endif
            </section>
        </div>

    </div>
</section>
@endsection
