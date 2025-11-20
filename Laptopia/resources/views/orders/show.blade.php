@extends('components.layouts.app')

@php
    use App\Models\Order;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $statusStyles = [
        Order::STATUS_PENDING => 'bg-yellow-400/20 text-yellow-200 border border-yellow-400/40',
        Order::STATUS_CONFIRMED => 'bg-sky-400/20 text-sky-200 border border-sky-400/40',
        Order::STATUS_SHIPPED => 'bg-indigo-400/20 text-indigo-200 border border-indigo-400/40',
        Order::STATUS_DELIVERED => 'bg-emerald-500/20 text-emerald-200 border border-emerald-500/40',
    ];
    $statusHierarchy = [
        Order::STATUS_PENDING => 1,
        Order::STATUS_CONFIRMED => 2,
        Order::STATUS_SHIPPED => 3,
        Order::STATUS_DELIVERED => 4,
    ];
    $statusTimeline = [
        Order::STATUS_PENDING => [
            'label' => __('Order Created'),
            'timestamp' => $order->created_at,
        ],
        Order::STATUS_CONFIRMED => [
            'label' => __('Order Confirmed'),
            'timestamp' => $order->confirmed_at,
        ],
        Order::STATUS_SHIPPED => [
            'label' => __('In Delivery'),
            'timestamp' => $order->shipped_at,
        ],
        Order::STATUS_DELIVERED => [
            'label' => __('Delivered'),
            'timestamp' => $order->delivered_at,
        ],
    ];
    $currentRank = $statusHierarchy[$order->status] ?? 0;
@endphp

@section('content')
    <section class="bg-slate-950 py-16">
        <div class="mx-auto max-w-5xl px-6">
            <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-cyan-300 hover:text-cyan-200">← Back to orders</a>

            <div class="mt-6 rounded-3xl border border-white/10 bg-white/5 p-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h1 class="text-2xl font-semibold text-white">Order {{ $order->code }}</h1>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] {{ $statusStyles[$order->status] ?? 'bg-slate-400/20 text-slate-200 border border-slate-400/40' }}">{{ ucfirst($order->status) }}</span>
                </div>
                <p class="mt-2 text-xs text-slate-500">Created on {{ $order->created_at->format('d M Y H:i') }} • Last updated {{ $order->updated_at->diffForHumans() }}</p>

                <div class="mt-6 grid gap-6 md:grid-cols-[1.4fr_0.8fr]">
                    <div class="space-y-5">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-5 text-sm text-slate-300">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Recipient Information</p>
                            <p class="mt-2 font-semibold text-white">{{ $order->recipient_name }}</p>
                            <p>{{ $order->recipient_phone }}</p>
                            <p>{{ $order->recipient_email }}</p>
                            <p class="mt-4 text-xs uppercase tracking-[0.3em] text-slate-500">Full Address</p>
                            <p class="mt-2 text-sm text-slate-300">{{ $order->shipping_address ?? '-' }}</p>
                            <p class="text-xs text-slate-500">{{ $order->shipping_city ?? '-' }} {{ $order->shipping_postal_code ?? '' }}</p>
                            <p class="mt-4 text-xs uppercase tracking-[0.3em] text-slate-500">Shipping Method</p>
                            <p class="mt-2 text-sm text-slate-300">{{ ucfirst($order->shipping_method ?? '-') }}</p>
                            @if ($order->tracking_number)
                                <p class="mt-4 text-xs uppercase tracking-[0.3em] text-slate-500">Nomor resi</p>
                                <p class="mt-2 text-sm font-semibold text-cyan-300">{{ $order->tracking_number }}</p>
                            @endif
                            @if ($order->notes)
                                <p class="mt-4 text-xs uppercase tracking-[0.3em] text-slate-500">Catatan pelanggan</p>
                                <p class="mt-2 text-sm text-slate-300">{{ $order->notes }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 text-sm text-slate-300">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Cost Summary</p>
                        <div class="mt-3 space-y-2">
                            <div class="flex items-center justify-between">
                                <span>Items</span>
                                <span>{{ $order->items_count }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Shipping fee</span>
                                <span>${{ number_format($order->shipping_cost, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-base font-semibold text-white">
                                <span>Total</span>
                                <span>${{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-slate-500">Payment will be made after the order is received.</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-white">Shipping Status</h2>
                    <ul class="mt-4 space-y-4">
                        @foreach ($statusTimeline as $status => $meta)
                            @php
                                $rank = $statusHierarchy[$status];
                                $completed = $currentRank >= $rank;
                                $isCurrent = $currentRank === $rank;
                                $timestamp = $meta['timestamp'];
                            @endphp
                            <li class="flex items-start gap-3">
                                <span class="mt-1 flex h-6 w-6 items-center justify-center rounded-full border {{ $completed ? 'border-cyan-300 bg-cyan-400/20 text-cyan-200' : 'border-white/20 text-slate-500' }}">
                                    @if ($completed)
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                            <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.2 7.253a1 1 0 0 1-1.424.01L3.29 9.16a1 1 0 0 1 1.42-1.41l3.367 3.28 6.492-6.546a1 1 0 0 1 1.414-.006Z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold {{ $isCurrent ? 'text-white' : 'text-slate-300' }}">{{ $meta['label'] }}</p>
                                    <p class="text-xs text-slate-500">
                                        @if ($timestamp)
                                            {{ $timestamp->format('d M Y H:i') }}
                                        @elseif ($status === Order::STATUS_CONFIRMED)
                                            {{ __('Awaiting order confirmation') }}
                                        @elseif ($status === Order::STATUS_SHIPPED)
                                            {{ __('Order will be shipped once confirmed') }}
                                        @else
                                            {{ __('Awaiting recipient confirmation') }}
                                        @endif
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-white">Item Details</h2>
                    <div class="mt-4 space-y-3">
                        @foreach ($order->items as $item)
                            @php
                                $product = $item->product;
                                $image = $product ? (Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : Storage::url($product->image)) : null;
                                $productDetailUrl = $product && $product->slug ? route('product.details', $product->slug) : null;
                            @endphp
                            <div class="flex flex-col gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 md:flex-row md:items-center">
                                @if ($image)
                                    @if ($productDetailUrl)
                                        <a href="{{ $productDetailUrl }}" class="shrink-0 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-slate-900">
                                            <img src="{{ $image }}" alt="{{ $product->name }}" class="h-24 w-24 rounded-xl object-cover transition hover:opacity-90" />
                                        </a>
                                    @else
                                        <img src="{{ $image }}" alt="{{ $product->name }}" class="h-24 w-24 rounded-xl object-cover" />
                                    @endif
                                @endif
                                <div class="flex-1">
                                    @if ($productDetailUrl)
                                        <a href="{{ $productDetailUrl }}" class="text-sm font-semibold text-white transition hover:text-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-slate-900">
                                            {{ $product->name }}
                                        </a>
                                    @else
                                        <p class="text-sm font-semibold text-white">{{ $product->name ?? 'Produk tidak tersedia' }}</p>
                                    @endif
                                    <p class="text-xs text-slate-500">{{ $product->details->processor ?? '-' }} · {{ $product->details->ram ?? '-' }}</p>
                                </div>
                                <div class="text-right text-sm text-slate-300">
                                    <p>Qty {{ $item->quantity}}</p>
                                    <p class="text-lg font-semibold text-cyan-300">${{ number_format($item->total_price, 2) }}</p>
                                </div>
                                @if ($order->status === Order::STATUS_DELIVERED && $productDetailUrl)
                                    <div class="md:ml-6">
                                        <a href="{{ $productDetailUrl }}#reviews" class="inline-flex items-center justify-center rounded-xl border border-cyan-400/40 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-cyan-300 transition hover:bg-cyan-400/10 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-slate-900">
                                            {{ __('Rate this product') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 space-y-4">
                    @if (auth()->user()->hasAnyRole(['admin', 'worker']))
                        <div class="space-y-3">
                            @if ($order->status === Order::STATUS_PENDING)
                                <form method="POST" action="{{ route('orders.status', $order) }}" class="flex flex-wrap items-center gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ Order::STATUS_CONFIRMED }}">
                                    <button type="submit" class="rounded-xl bg-gradient-to-r from-sky-400 to-indigo-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02] cursor-pointer">Confirm Purchase</button>
                                </form>
                            @endif

                            @if (in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_CONFIRMED], true))
                                <form method="POST" action="{{ route('orders.status', $order) }}" class="flex flex-wrap items-center gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ Order::STATUS_SHIPPED }}">
                                    <div class="flex flex-1 flex-wrap items-center gap-3">
                                        <label for="tracking_number" class="text-xs uppercase tracking-[0.3em] text-slate-500">Tracking Number</label>
                                        <input id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" required class="min-w-[220px] flex-1 rounded-xl border border-white/10 bg-slate-900/80 px-3 py-2 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" placeholder="Enter Tracking Number" />
                                        <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-400 to-cyan-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02] cursor-pointer">Mark as Shipped</button>
                                    </div>
                                    @error('tracking_number')
                                        <span class="w-full text-xs text-red-300">{{ $message }}</span>
                                    @enderror
                                </form>
                            @elseif ($order->status === Order::STATUS_SHIPPED)
                                <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300">
                                    <p>Your order has been shipped. Tracking: <span class="font-semibold text-cyan-300">{{ $order->tracking_number }}</span></p>
                                </div>
                            @endif
                        </div>
                    @elseif ($order->status === Order::STATUS_SHIPPED && $order->user_id === auth()->id())
                        <div>
                            <button type="button" data-order-delivery-open class="rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02] cursor-pointer">Mark as arrived</button>
                            <p class="mt-2 text-xs text-slate-500">Press the button above once your package has arrived.</p>
                        </div>
                        <form method="POST" action="{{ route('orders.status', $order) }}" class="hidden" data-order-delivery-form>
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ Order::STATUS_DELIVERED }}">
                        </form>
                    @elseif ($order->status === Order::STATUS_DELIVERED)
                        <p class="text-xs text-slate-500">Your order has been marked as received. Thank you!</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="fixed inset-0 z-40 hidden items-center justify-center bg-slate-950/60 px-4" data-order-delivery-modal>
        <div class="w-full max-w-sm rounded-3xl border border-white/10 bg-white/10 p-6 text-center text-slate-100 backdrop-blur">
            <h3 class="text-lg font-semibold text-white">Delivery Confirmation</h3>
            <p class="mt-2 text-sm text-slate-300">Please make sure you have received the package before marking the order as arrived.</p>
            <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                <button type="button" data-order-delivery-cancel class="rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-slate-100 hover:bg-white/10 cursor-pointer">Not yet</button>
                <button type="button" data-order-delivery-submit class="rounded-xl bg-gradient-to-r from-emerald-400 to-cyan-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02] cursor-pointer">Received</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openBtn = document.querySelector('[data-order-delivery-open]');
            const modal = document.querySelector('[data-order-delivery-modal]');
            const cancelBtn = document.querySelector('[data-order-delivery-cancel]');
            const submitBtn = document.querySelector('[data-order-delivery-submit]');
            const form = document.querySelector('[data-order-delivery-form]');

            if (!openBtn || !modal || !cancelBtn || !submitBtn || !form) {
                return;
            }

            const toggleModal = (show) => {
                modal.classList.toggle('hidden', !show);
                modal.classList.toggle('flex', show);
            };

            openBtn.addEventListener('click', () => toggleModal(true));
            cancelBtn.addEventListener('click', () => toggleModal(false));
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    toggleModal(false);
                }
            });
            submitBtn.addEventListener('click', () => {
                toggleModal(false);
                form.submit();
            });
        });
    </script>
@endsection
