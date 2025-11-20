@extends('components.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

<script>
    // SIMPLE PURE JS CAROUSEL
    const slides = document.querySelectorAll(".carousel-img");
    let index = 0;

    function nextSlide() {
        slides[index].style.opacity = 0;
        index = (index + 1) % slides.length;
        slides[index].style.opacity = 1;
    }

    setInterval(nextSlide, 3500); // Auto slide every 3.5s
</script>

@section('content')
    <section id="home" class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 py-24">
        <div class="absolute inset-0" aria-hidden="true">
            <div class="absolute -top-40 left-1/2 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-gradient-to-br from-cyan-500/20 via-indigo-500/15 to-transparent blur-3xl"></div>
            <div class="absolute bottom-0 right-[-200px] h-[460px] w-[460px] rounded-full bg-gradient-to-tr from-indigo-500/20 via-sky-400/10 to-transparent blur-3xl"></div>
        </div>

        <div class="relative mx-auto flex max-w-6xl flex-col gap-16 px-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-xl text-center lg:text-left">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1 text-xs font-semibold tracking-[0.3em] text-cyan-300">NEW</span>
                <h1 class="mt-6 text-4xl font-extrabold leading-tight text-white md:text-5xl lg:text-6xl">
                    Discover laptops engineered for <span class="bg-gradient-to-r from-cyan-300 via-sky-300 to-indigo-200 bg-clip-text text-transparent">raw performance</span>
                </h1>
                <p class="mt-6 text-base leading-relaxed text-slate-300 md:text-lg">
                    Laptopia curates premium machines tailored for creators, gamers, and business specialists. Fine-tuned thermals, calibrated displays, and whisper-quiet designs—ready for studio sessions or on-site deployments.
                </p>

                <form method="GET" action="{{ route('products.index') }}" class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, brand, or chipset" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white shadow-inner placeholder:text-slate-400 focus:border-cyan-400 focus:outline-none focus:ring focus:ring-cyan-400/30" />
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-500 px-6 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/40 transition hover:scale-[1.02]">
                        Find laptops
                    </button>
                </form>

                <div class="mt-8 flex flex-wrap items-center gap-6 text-xs uppercase tracking-[0.3em] text-slate-400">
                    <span>Intel® Core Ultra</span>
                    <span>RTX 40 Series</span>
                    <span>Mini LED</span>
                    <span>Wi-Fi 7</span>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-md overflow-hidden rounded-[36px] border border-white/10 bg-white/5 shadow-[0_40px_80px_-30px_rgba(56,189,248,0.45)]">
                <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=1600&auto=format&fit=crop" alt="Laptop workstation" class="h-full w-full object-cover" />
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-transparent px-6 py-6 text-sm text-slate-200">
                    <p class="text-xs uppercase tracking-[0.35em] text-cyan-300">Creator ready</p>
                    <p class="mt-2 text-lg font-semibold text-white">OLED 3.2K • RTX Studio • 32GB RAM</p>
                </div>
            </div>
        </div>
    </section>

    <section id="products" class="relative py-24">
        <div class="mx-auto max-w-6xl px-6">
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

            <div class="text-center">
                <span class="text-xs uppercase tracking-[0.3em] text-cyan-400">Curated picks</span>
                <h2 class="mt-3 text-3xl font-bold text-white md:text-4xl">Featured laptop lineup</h2>
                <p class="mt-3 text-sm text-slate-400">Six highlights chosen from our latest arrivals and top-rated builds.</p>
            </div>

            <div class="mt-14 grid gap-10 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($featuredProducts as $product)
                    @php
                        $image = Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : Storage::url($product->image);
                        $rating = round($product->reviews_avg_rating ?? 0, 1);
                        $reviewCount = $product->reviews_count ?? 0;
                    @endphp
                    <article class="group flex h-full flex-col overflow-hidden rounded-3xl border border-white/10 bg-slate-900/70 backdrop-blur transition hover:border-cyan-400/40">
                        <div class="relative overflow-hidden">
                            <img src="{{ $image }}" alt="{{ $product->name }}" class="h-56 w-full object-cover transition duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-60"></div>
                            <div class="absolute left-5 top-5 flex items-center gap-2 text-xs font-semibold text-slate-200">
                                @if ($product->category)
                                    <span class="rounded-full bg-white/10 px-3 py-1 backdrop-blur">{{ $product->category->name }}</span>
                                @endif
                                <span class="rounded-full bg-cyan-400/80 px-3 py-1 text-slate-950">{{ $product->brand }}</span>
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
                                <li class="flex items-center justify-between"><span>Processor</span><span class="font-semibold text-slate-100">{{ Str::limit($product->details->processor ?? '-', 22) }}</span></li>
                                <li class="flex items-center justify-between"><span>Graphics</span><span class="font-semibold text-slate-100">{{ Str::limit($product->details->graphics ?? '-', 22) }}</span></li>
                                <li class="flex items-center justify-between"><span>Memory</span><span class="font-semibold text-slate-100">{{ $product->details->ram ?? '-' }}</span></li>
                            </ul>

                            <div class="flex items-center justify-between text-xs text-cyan-300">
                                <span class="inline-flex items-center gap-1 text-slate-300">
                                    ★ {{ number_format($rating, 1) }}
                                    <span class="text-slate-500">({{ $reviewCount }})</span>
                                </span>
                                <span class="text-slate-400">{{ $product->stock }} in stock</span>
                            </div>

                            <div class="mt-auto flex items-center justify-between">
                                <div class="text-2xl font-bold text-cyan-300">${{ number_format($product->price, 2) }}</div>
                                <div class="flex items-center gap-3">
                                    <form method="POST" action="{{ route('cart.store', $product) }}">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-cyan-400/60 px-3 py-2 text-xs font-semibold text-cyan-300 transition hover:bg-cyan-400/10 cursor-pointer">Add to cart</button>
                                    </form>
                                    <a href="{{ route('product.details', $product->slug) }}" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-4 py-2 text-xs font-semibold text-slate-950 shadow hover:scale-[1.02]">View</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="col-span-full text-center text-sm text-slate-400">No featured laptops available at the moment. Check back soon!</p>
                @endforelse
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-500 px-6 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/40 transition hover:scale-[1.02]">
                    Explore all laptops
                </a>
            </div>
        </div>
    </section>

    <section id="about" class="relative py-24">
        <div class="absolute inset-x-0 top-0 -z-10 h-1/2 bg-gradient-to-b from-slate-950 via-slate-900 to-transparent"></div>
        <div class="mx-auto max-w-6xl px-6">
            <div class="grid items-center gap-16 lg:grid-cols-2">
                <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-10 shadow-xl shadow-slate-950/40">
                    <span class="text-xs uppercase tracking-[0.3em] text-cyan-400">Laptopia labs</span>
                    <h2 class="mt-4 text-3xl font-bold text-white">Meticulously tuned hardware for immersive workflows</h2>
                    <p class="mt-4 text-sm text-slate-400">
                        Each configuration passes thermal stress tests, color calibration, and battery endurance cycles. We pair the latest silicon with silent cooling and premium build materials to keep you productive wherever you deploy.
                    </p>
                    <div class="mt-8 grid gap-4 text-sm text-slate-300 md:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-cyan-300">Creator grade</p>
                            <p class="mt-2 font-semibold">Factory calibrated Delta-E &lt; 2 displays with HDR 1000 certification.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-cyan-300">Quiet thermals</p>
                            <p class="mt-2 font-semibold">Vapor chamber cooling with whisper mode fans below 32 dB.</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-10 rounded-[36px] bg-gradient-to-br from-cyan-500/20 via-indigo-500/10 to-transparent blur-3xl"></div>
                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1600&auto=format&fit=crop" alt="Laptopia studio" class="relative rounded-[36px] border border-white/10" />
                </div>
            </div>
        </div>
    </section>

    {{-- <section id="contact" class="relative py-24">
        <div class="mx-auto max-w-4xl px-6">
            <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-12 shadow-xl shadow-slate-950/40">
                <div class="text-center">
                    <span class="text-xs uppercase tracking-[0.3em] text-cyan-400">In-store pickup</span>
                    <h2 class="mt-4 text-3xl font-semibold text-white">Need a tailored configuration?</h2>
                    <p class="mt-3 text-sm text-slate-400">Book a session with our specialists and assemble your Laptopia workstation or gaming battlestation.</p>
                </div>
                <form class="mt-10 grid gap-6 md:grid-cols-2">
                    <div class="md:col-span-1">
                        <input type="text" placeholder="Name" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" required />
                    </div>
                    <div class="md:col-span-1">
                        <input type="email" placeholder="Email" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" required />
                    </div>
                    <div class="md:col-span-2">
                        <textarea rows="4" placeholder="Tell us about your workflow or gaming needs" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" required></textarea>
                    </div>
                    <div class="md:col-span-2 flex justify-center">
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-500 px-6 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/40 transition hover:scale-[1.02]">Submit request</button>
                    </div>
                </form>
            </div>
        </div>
    </section> --}}
@endsection
