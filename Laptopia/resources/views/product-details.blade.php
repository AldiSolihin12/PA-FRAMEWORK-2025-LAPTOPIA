@extends('components.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $imageUrl = Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : Storage::url($product->image);
    $isInWishlist = auth()->check() ? auth()->user()->wishlistItems()->where('product_id', $product->id)->exists() : false;
    $rating = round($averageRating ?? 0, 1);
@endphp

@section('content')
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 py-12">
        <div class="absolute inset-0" aria-hidden="true">
            <div class="absolute -top-40 left-1/2 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-gradient-to-br from-cyan-500/20 via-indigo-500/15 to-transparent blur-3xl"></div>
            <div class="absolute bottom-[-120px] right-[-120px] h-[420px] w-[420px] rounded-full bg-gradient-to-tr from-indigo-500/20 via-sky-400/10 to-transparent blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-6xl px-6">
            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-cyan-400/40 bg-cyan-400/10 px-5 py-3 text-sm text-cyan-200">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-400/40 bg-red-500/10 px-5 py-3 text-sm text-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid gap-10 rounded-[36px] border border-white/10 bg-slate-900/80 p-10 shadow-2xl shadow-slate-950/40 backdrop-blur lg:grid-cols-[1.2fr_1fr]">
                <div class="relative overflow-hidden rounded-[28px] border border-white/10">
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-transparent p-6">
                        <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-slate-200">
                            @if ($product->category)
                                <span class="rounded-full bg-white/10 px-3 py-1">{{ $product->category->name }}</span>
                            @endif
                            <span class="rounded-full bg-cyan-400/80 px-3 py-1 text-slate-950">{{ $product->brand }}</span>
                            <span class="rounded-full bg-white/10 px-3 py-1">{{ $product->stock }} in stock</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-8">
                    <div class="space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <h1 class="text-3xl font-bold text-white md:text-4xl">{{ $product->name }}</h1>
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-cyan-300">
                                ★ {{ number_format($rating, 1) }} <span class="text-slate-500">({{ $reviewCount }})</span>
                            </span>
                        </div>
                        <p class="text-sm leading-relaxed text-slate-300">
                            {{ $product->details->description ?? 'Premium laptop engineered for creators, gamers, and professionals seeking uncompromised performance.' }}
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ([
                            ['Processor', $product->details->processor ?? '—'],
                            ['Graphics', $product->details->graphics ?? '—'],
                            ['Memory', $product->details->ram ?? '—'],
                            ['Storage', $product->details->storage ?? '—'],
                            ['Display', $product->details->display ?? '—'],
                            ['Battery', $product->details->battery ?? '—'],
                            ['Weight', $product->details->weight ?? '—'],
                            ['Ports', $product->details->ports ?? '—'],
                        ] as [$label, $value])
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ $label }}</p>
                                <p class="mt-2 text-sm font-semibold text-slate-100">{{ $value }}</p>
                            </div>
                        @endforeach
                        <div class="sm:col-span-2 rounded-2xl border border-white/10 bg-white/5 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Operating system</p>
                            <p class="mt-2 text-sm font-semibold text-slate-100">{{ $product->details->operating_system ?? 'Windows 11 Pro' }}</p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">In-store pickup price</p>
                                <p class="mt-1 text-3xl font-bold text-cyan-300">${{ number_format($product->price, 2) }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('cart.store', $product) }}" class="flex items-center gap-3">
                                    @csrf
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 rounded-xl border border-white/10 bg-slate-900/80 px-3 py-2 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" />
                                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02] cursor-pointer">Add to cart</button>
                                </form>
                                @auth
                                    <form method="POST" action="{{ $isInWishlist ? route('wishlist.destroy', $product) : route('wishlist.store', $product) }}">
                                        @csrf
                                        @if ($isInWishlist)
                                            @method('DELETE')
                                        @endif
                                        <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-cyan-300 hover:bg-cyan-400/10 cursor-pointer">
                                            {{ $isInWishlist ? 'Remove wishlist' : 'Add to wishlist' }}
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </div>
                        <p class="mt-4 text-xs text-slate-500">Checkout online and finish the transaction in-store. Our team prepares your device and migrates data on request.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-950 py-20">
        <div class="mx-auto max-w-6xl px-6">
            <h2 class="text-2xl font-semibold text-white">Customer reviews</h2>
            <p class="mt-2 text-sm text-slate-400">Average rating {{ number_format($rating, 1) }} from {{ $reviewCount }} review(s).</p>

            @auth
                @if ($canReview)
                    <div class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-6">
                        <form method="POST" action="{{ route('products.reviews.store', $product) }}" class="space-y-4">
                            @csrf
                            <div class="flex items-center gap-3 text-sm text-slate-200">
                                <label for="rating" class="uppercase tracking-[0.3em] text-xs text-slate-500">Rating</label>
                                <select id="rating" name="rating" required class="rounded-xl border border-white/10 bg-slate-900/80 px-3 py-2 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" @selected(optional($userReview)->rating == $i)>{{ $i }} ★</option>
                                    @endfor
                                </select>
                            </div>
                            <textarea name="comment" rows="4" placeholder="Share your experience with this laptop" class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none">{{ old('comment', optional($userReview)->comment) }}</textarea>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-5 py-2 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02] cursor-pointer">
                                {{ $userReview ? 'Update review' : 'Publish review' }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-6 text-sm text-slate-300">
                        <p>You can leave a review once your order for this product has been delivered.</p>
                    </div>
                @endif
            @else
                <p class="mt-8 text-sm text-slate-400">Please <a href="{{ route('login') }}" class="text-cyan-300 hover:text-cyan-200">sign in</a> to leave a review.</p>
            @endauth

            <div class="mt-10 space-y-6">
                @forelse ($product->reviews as $review)
                    <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-r from-cyan-400 to-indigo-500 text-sm font-semibold text-slate-950">
                                    {{ strtoupper(Str::substr($review->user->name, 0, 1)) }}
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $review->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-cyan-300">{{ $review->rating }} ★</span>
                        </div>
                        @if ($review->comment)
                            <p class="mt-4 text-sm text-slate-300">{{ $review->comment }}</p>
                        @endif
                    </article>
                @empty
                    <p class="text-sm text-slate-500">Be the first to share your experience with {{ $product->name }}.</p>
                @endforelse
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="bg-slate-950 pb-20">
            <div class="mx-auto max-w-6xl px-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-white">Related laptops</h2>
                    <a href="{{ route('welcome') }}#products" class="text-sm font-semibold text-cyan-300 hover:text-cyan-200">View all</a>
                </div>
                <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($relatedProducts as $related)
                        @php
                            $relatedImage = Str::startsWith($related->image, ['http://', 'https://']) ? $related->image : Storage::url($related->image);
                            $relatedRating = round($related->reviews_avg_rating ?? 0, 1);
                        @endphp
                        <article class="rounded-2xl border border-white/10 bg-white/5 p-4 transition hover:border-cyan-400/40">
                            <img src="{{ $relatedImage }}" alt="{{ $related->name }}" class="h-36 w-full rounded-xl object-cover" />
                            <div class="mt-4 space-y-2">
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ $related->brand }}</p>
                                <h3 class="text-base font-semibold text-white">{{ $related->name }}</h3>
                                <div class="flex items-center justify-between text-xs text-slate-400">
                                    <span>★ {{ number_format($relatedRating, 1) }}</span>
                                    <span>${{ number_format($related->price, 0) }}</span>
                                </div>
                                <a href="{{ route('product.details', $related->slug) }}" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-3 py-2 text-xs font-semibold text-slate-950 shadow">View</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
