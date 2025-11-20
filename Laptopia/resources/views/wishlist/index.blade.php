++ new file
@extends('components.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@section('content')
    <section class="bg-slate-950 py-16">
        <div class="mx-auto max-w-6xl px-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold text-white">Wishlist</h1>
                    <p class="text-sm text-slate-400">Save your favourite Laptopia builds for later.</p>
                </div>
                <a href="{{ route('welcome') }}#products" class="rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-slate-300 hover:bg-white/5">Continue browsing</a>
            </div>

            <div class="mt-10 grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($items as $item)
                    @php
                        $product = $item->product;
                        $image = Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : Storage::url($product->image);
                        $rating = round($product->reviews_avg_rating ?? $product->reviews()->avg('rating') ?? 0, 1);
                    @endphp
                    <article class="flex h-full flex-col gap-4 rounded-3xl border border-white/10 bg-white/5 p-6">
                        <img src="{{ $image }}" alt="{{ $product->name }}" class="h-40 w-full rounded-2xl object-cover" />
                        <div class="flex-1 space-y-3">
                            <div class="flex items-center justify-between">
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ $product->brand }}</p>
                                <span class="text-xs text-slate-400">★ {{ number_format($rating, 1) }}</span>
                            </div>
                            <h2 class="text-lg font-semibold text-white">{{ $product->name }}</h2>
                            <p class="text-sm text-slate-400">{{ Str::limit($product->details->description ?? '-', 80) }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-cyan-300">${{ number_format($product->price, 2) }}</span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('product.details', $product->slug) }}" class="rounded-xl border border-white/10 px-3 py-2 text-xs font-semibold text-cyan-300 hover:bg-cyan-400/10">View</a>
                                <form method="POST" action="{{ route('wishlist.destroy', $product) }}" onsubmit="return confirm('Remove from wishlist?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-3 py-2 text-xs font-semibold text-slate-950 shadow">Remove</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="sm:col-span-2 xl:col-span-3 rounded-3xl border border-white/10 bg-white/5 p-10 text-center text-sm text-slate-400">
                        Your wishlist is empty. Explore the catalogue to add laptops.
                    </div>
                @endforelse
            </div>

            <div class="mt-8 text-slate-400">
                {{ $items->links() }}
            </div>
        </div>
    </section>
@endsection
