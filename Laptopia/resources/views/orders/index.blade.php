@extends('components.layouts.app')

@section('content')
    <section class="bg-slate-950 py-16">
        <div class="mx-auto max-w-6xl px-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold text-white">Orders</h1>
                    <p class="text-sm text-slate-400">Track order progress and confirmation status.</p>
                </div>
                <a href="{{ route('welcome') }}#products" class="rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-slate-300 hover:bg-white/5">Shop more</a>
            </div>

            <div class="mt-10 space-y-6">
                @forelse ($orders as $order)
                    <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Order code</p>
                                <p class="text-lg font-semibold text-white">{{ $order->code }}</p>
                            </div>
                            <div class="flex items-center gap-3 text-xs uppercase tracking-[0.3em]">
                                <span class="rounded-full border border-white/10 px-3 py-1 text-slate-400">{{ $order->created_at->format('d M Y') }}</span>
                                @php
                                    $statusStyles = [
                                        \App\Models\Order::STATUS_PENDING => 'bg-yellow-400/20 text-yellow-200 border border-yellow-400/40',
                                        \App\Models\Order::STATUS_CONFIRMED => 'bg-sky-400/20 text-sky-200 border border-sky-400/40',
                                        \App\Models\Order::STATUS_SHIPPED => 'bg-indigo-400/20 text-indigo-200 border border-indigo-400/40',
                                        \App\Models\Order::STATUS_DELIVERED => 'bg-emerald-500/20 text-emerald-200 border border-emerald-500/40',
                                    ];
                                @endphp
                                <span class="rounded-full px-3 py-1 font-semibold {{ $statusStyles[$order->status] ?? 'bg-slate-400/20 text-slate-200 border border-slate-400/40' }}">{{ ucfirst($order->status) }}</span>
                            </div>
                        </div>

                        @if (auth()->user()->hasAnyRole(['admin', 'worker']))
                            <p class="mt-2 text-xs text-slate-500">Customer: <span class="text-slate-300">{{ $order->user->name }} ({{ $order->recipient_email }})</span></p>
                        @endif

                        <div class="mt-4 grid gap-2 text-sm text-slate-300 md:grid-cols-2">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Penerima</p>
                                <p>{{ $order->recipient_name }} · {{ $order->recipient_phone }}</p>
                                <p class="text-xs text-slate-500">{{ $order->shipping_address ?? 'Alamat belum tersedia' }}, {{ $order->shipping_city ?? '-' }} {{ $order->shipping_postal_code ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Items</p>
                                <p>{{ $order->items_count }} unit(s) · ${{ number_format($order->total, 2) }}</p>
                                <p class="text-xs text-slate-500">Shipping: {{ ucfirst($order->shipping_method ?? '-') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                            <div class="text-xs text-slate-500">Last updated {{ $order->updated_at->diffForHumans() }}</div>
                            <a href="{{ route('orders.show', $order) }}" class="rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02]">View details</a>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-10 text-center text-sm text-slate-400">
                        You haven’t placed any orders yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-8 text-slate-400">
                {{ $orders->links() }}
            </div>
        </div>
    </section>
@endsection
