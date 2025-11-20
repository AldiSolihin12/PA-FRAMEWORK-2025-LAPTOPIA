++ new file
@extends('components.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@section('content')
    <section class="bg-slate-950 py-16">
        <div class="mx-auto max-w-6xl px-6">
            <div class="mb-10 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold text-white">Shopping cart</h1>
                    <p class="text-sm text-slate-400">{{ $totals['item_count'] }} item(s) ready for pickup.</p>
                </div>
                @if ($items->isNotEmpty())
                    <form method="POST" action="{{ route('cart.clear') }}" onclick="return confirm('Clear all items in your cart?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-cyan-300 hover:bg-cyan-400/10">Clear cart</button>
                    </form>
                @endif
            </div>

            @if ($items->isEmpty())
                <div class="rounded-3xl border border-white/10 bg-white/5 p-10 text-center">
                    <p class="text-sm text-slate-400">Your cart is empty. Browse the <a href="{{ route('welcome') }}#products" class="text-cyan-300 hover:text-cyan-200">catalogue</a> to add laptops.</p>
                </div>
            @else
                <div class="grid gap-8 lg:grid-cols-[2fr_1fr]">
                    <div class="space-y-4">
                        @foreach ($items as $item)
                            @php
                                $product = $item['product'];
                                $image = Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : Storage::url($product->image);
                            @endphp
                            <article class="flex flex-col gap-4 rounded-3xl border border-white/10 bg-white/5 p-6 shadow-sm shadow-slate-950/20 md:flex-row md:items-center">
                                <img src="{{ $image }}" alt="{{ $product->name }}" class="h-32 w-32 rounded-2xl object-cover" />
                                <div class="flex-1 space-y-2">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <a href="{{ route('product.details', $product->slug) }}" class="text-lg font-semibold text-white hover:text-cyan-300">{{ $product->name }}</a>
                                        <span class="text-sm font-semibold text-cyan-300">${{ number_format($item['unit_price'], 2) }}</span>
                                    </div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ $product->brand }} · {{ $product->category?->name }}</p>
                                    <p class="text-sm text-slate-400">{{ Str::limit($product->details->processor ?? '-', 50) }} · {{ $product->details->ram ?? '-' }} · {{ $product->details->storage ?? '-' }}</p>
                                    <div class="flex flex-wrap items-center gap-4 pt-2">
                                        <form method="POST" action="{{ route('cart.update', $product) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <label for="quantity-{{ $product->id }}" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Qty</label>
                                            <input id="quantity-{{ $product->id }}" type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $product->stock }}" class="w-20 rounded-xl border border-white/10 bg-slate-900/80 px-3 py-2 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" />
                                            <button type="submit" class="rounded-xl border border-white/10 px-3 py-2 text-xs font-semibold text-cyan-300 hover:bg-cyan-400/10">Update</button>
                                        </form>
                                        <form method="POST" action="{{ route('cart.destroy', $product) }}" onsubmit="return confirm('Remove this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-300 hover:text-red-200">Remove</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="text-right text-sm text-slate-300">
                                    <p>Subtotal</p>
                                    <p class="mt-1 text-lg font-semibold text-cyan-300">${{ number_format($item['line_total'], 2) }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <aside class="space-y-4">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                            <h2 class="text-lg font-semibold text-white">Order summary</h2>
                            <div class="mt-4 space-y-3 text-sm text-slate-300">
                                <div class="flex items-center justify-between">
                                    <span>Items ({{ $totals['item_count'] }})</span>
                                    <span>${{ number_format($totals['subtotal'], 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>Pickup method</span>
                                    <span>In-store checkout</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-white">Total</span>
                                    <span class="text-xl font-bold text-cyan-300">${{ number_format($totals['subtotal'], 2) }}</span>
                                </div>
                            </div>

                            @auth
                                <a href="{{ route('checkout.show') }}" class="mt-5 block rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-4 py-3 text-center text-sm font-semibold text-slate-950 shadow hover:scale-[1.02]">Proceed to checkout</a>
                            @else
                                <a href="{{ route('login') }}" class="mt-5 block rounded-xl border border-white/10 px-4 py-3 text-center text-sm font-semibold text-cyan-300 hover:bg-cyan-400/10">Sign in to checkout</a>
                            @endauth
                        </div>
                        <a href="{{ route('welcome') }}#products" class="block rounded-xl border border-white/10 px-4 py-3 text-center text-sm font-semibold text-slate-300 hover:bg-white/5">Continue shopping</a>
                    </aside>
                </div>
            @endif
        </div>
    </section>
@endsection
