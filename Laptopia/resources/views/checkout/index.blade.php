++ new file
@extends('components.layouts.app')

@section('content')
    <section class="bg-slate-950 py-16">
        <div class="mx-auto max-w-5xl px-6">
            <h1 class="text-3xl font-semibold text-white">Shipping Checkout</h1>
            <p class="mt-2 text-sm text-slate-400">Complete the recipient details and shipping address to process your Laptopia order.</p>

            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-red-400/40 bg-red-500/10 px-5 py-3 text-sm text-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mt-10 grid gap-10 lg:grid-cols-[1.2fr_1fr]">
                @php
                    $selectedMethod = old('shipping_method', 'standard');
                    $defaultOption = $shippingOptions[$selectedMethod] ?? reset($shippingOptions);
                @endphp

                <form method="POST" action="{{ route('checkout.store') }}" class="space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8" data-shipping-form>
                    @csrf
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="recipient_name" class="text-xs uppercase tracking-[0.3em] text-slate-500">Recipient Name</label>
                            <input id="recipient_name" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" required class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" />
                        </div>
                        <div>
                            <label for="recipient_phone" class="text-xs uppercase tracking-[0.3em] text-slate-500">Phone Number</label>
                            <input id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone') }}" required class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" />
                        </div>
                        <div>
                            <label for="recipient_email" class="text-xs uppercase tracking-[0.3em] text-slate-500">Email</label>
                            <input id="recipient_email" type="email" name="recipient_email" value="{{ old('recipient_email', auth()->user()->email) }}" required class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" />
                        </div>
                        <div>
                            <label for="shipping_address" class="text-xs uppercase tracking-[0.3em] text-slate-500">Full Address</label>
                            <input id="shipping_address" name="shipping_address" value="{{ old('shipping_address') }}" required class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" placeholder="Street name, house number, landmarks" />
                        </div>
                        <div>
                            <label for="shipping_city" class="text-xs uppercase tracking-[0.3em] text-slate-500">City / District</label>
                            <input id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}" required class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" />
                        </div>
                        <div>
                            <label for="shipping_postal_code" class="text-xs uppercase tracking-[0.3em] text-slate-500">Postal Code</label>
                            <input id="shipping_postal_code" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" />
                        </div>
                        <div>
                            <label for="shipping_method" class="text-xs uppercase tracking-[0.3em] text-slate-500">Shipping Method</label>
                            <select id="shipping_method" name="shipping_method" class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" data-shipping-method>
                                @foreach ($shippingOptions as $value => $option)
                                    <option value="{{ $value }}" @selected($selectedMethod === $value) data-cost="{{ $option['cost'] }}">{{ $option['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="text-xs uppercase tracking-[0.3em] text-slate-500">Additional Notes</label>
                        <textarea id="notes" name="notes" rows="4" placeholder="Courier instructions or special requests" class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-6 py-3 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02] cursor-pointer">Place Order</button>

                    <p class="text-center text-xs text-slate-500">We will send you an email containing the shipping details and tracking information.</p>
                </form>

                <aside class="space-y-4">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                        <h2 class="text-lg font-semibold text-white">Order Summary</h2>
                        <div class="mt-4 space-y-4 text-sm text-slate-300">
                            @foreach ($items as $item)
                                <div class="flex items-center justify-between">
                                    <span>{{ $item['product']->name }} × {{ $item['quantity'] }}</span>
                                    <span>${{ number_format($item['line_total'], 2) }}</span>
                                </div>
                            @endforeach
                            <div class="flex items-center justify-between">
                                <span>Shipping Cost</span>
                                <span data-checkout-shipping-cost>${{ number_format($defaultOption['cost'] ?? 0, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-white/10 pt-4 text-sm font-semibold text-white">
                                <span>Total</span>
                                <span data-checkout-grand-total>${{ number_format($totals['subtotal'] + ($defaultOption['cost'] ?? 0), 2) }}</span>
                            </div>
                        </div>
                        <p class="mt-4 text-xs text-slate-500">Payment is made upon delivery. Please ensure the recipient details are correct to speed up the process.</p>
                    </div>

                    <a href="{{ route('cart.index') }}" class="block rounded-xl border border-white/10 px-4 py-3 text-center text-sm font-semibold text-slate-300 hover:bg-white/5">Back to Cart</a>
                </aside>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const shippingSelect = document.querySelector('[data-shipping-method]');
            const shippingCostEl = document.querySelector('[data-checkout-shipping-cost]');
            const totalEl = document.querySelector('[data-checkout-grand-total]');
            const form = document.querySelector('[data-shipping-form]');

            if (!shippingSelect || !shippingCostEl || !totalEl || !form) {
                return;
            }

            const subtotal = Number({{ json_encode($totals['subtotal']) }});

            const formatCurrency = (value) => {
                return '$' + Number(value).toFixed(2);
            };

            const updateTotals = () => {
                const option = shippingSelect.selectedOptions[0];
                const shippingCost = option ? Number(option.dataset.cost || 0) : 0;
                shippingCostEl.textContent = formatCurrency(shippingCost);
                totalEl.textContent = formatCurrency(subtotal + shippingCost);
            };

            shippingSelect.addEventListener('change', updateTotals);
            updateTotals();
        });
    </script>
@endsection
