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
                    <p class="text-sm text-slate-400">
                        <span data-cart-total-items>{{ $totals['item_count'] }}</span> item(s) in cart ·
                        <span data-cart-selected-quantity>{{ $totals['selected_item_count'] }}</span> selected for checkout
                    </p>
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
                            <article class="flex flex-col gap-6 rounded-3xl border border-white/10 bg-gradient-to-br from-white/5 via-white/0 to-white/10 p-6 shadow-lg shadow-slate-950/10 backdrop-blur md:flex-row md:items-center" data-cart-item="{{ $product->id }}">
                                <div class="flex items-center gap-4">
                                    <label class="group relative flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-slate-900/80 transition hover:border-cyan-400 hover:shadow-[0_0_25px_-10px_rgba(45,212,191,0.8)]">
                                        <input type="checkbox" class="peer absolute inset-0 h-full w-full cursor-pointer appearance-none rounded-2xl" data-cart-select-input data-cart-select-url="{{ route('cart.select', $product) }}" aria-label="Select {{ $product->name }} for checkout" {{ $item['selected'] ? 'checked' : '' }} />
                                        <span class="pointer-events-none flex h-8 w-8 items-center justify-center rounded-2xl border border-white/10 text-transparent transition peer-checked:border-cyan-400 peer-checked:text-cyan-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                                                <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.2 7.253a1 1 0 0 1-1.424.01L3.29 9.16a1 1 0 0 1 1.42-1.41l3.367 3.28 6.492-6.546a1 1 0 0 1 1.414-.006Z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </label>
                                    <div class="group relative h-32 w-32 overflow-hidden rounded-2xl border border-white/10">
                                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-cyan-400/0 via-cyan-400/10 to-indigo-500/10 opacity-0 transition group-hover:opacity-100"></div>
                                        <img src="{{ $image }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                                    </div>
                                </div>
                                <div class="flex-1 space-y-2">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <a href="{{ route('product.details', $product->slug) }}" class="text-lg font-semibold text-white hover:text-cyan-300">{{ $product->name }}</a>
                                        <span class="text-sm font-semibold text-cyan-300">${{ number_format($item['unit_price'], 2) }}</span>
                                    </div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ $product->brand }} · {{ $product->category?->name }}</p>
                                    <p class="text-sm text-slate-400">{{ Str::limit($product->details->processor ?? '-', 50) }} · {{ $product->details->ram ?? '-' }} · {{ $product->details->storage ?? '-' }}</p>
                                    <div class="flex flex-wrap items-center gap-4 pt-2">
                                        <form method="POST" action="{{ route('cart.update', $product) }}" class="flex items-center gap-2" data-cart-quantity-form>
                                            @csrf
                                            @method('PATCH')
                                            <label for="quantity-{{ $product->id }}" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Qty</label>
                                            <input id="quantity-{{ $product->id }}" type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $product->stock }}" class="w-20 rounded-xl border border-white/10 bg-slate-900/80 px-3 py-2 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" data-cart-quantity-input data-product-id="{{ $product->id }}" />
                                            <button type="submit" class="sr-only">Update quantity</button>
                                        </form>
                                        <form method="POST" action="{{ route('cart.destroy', $product) }}" onsubmit="return confirm('Remove this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-300 hover:text-red-200">Remove</button>
                                        </form>
                                    </div>
                                    <p class="hidden text-xs text-red-300" data-cart-item-error></p>
                                </div>
                                <div class="text-right text-sm text-slate-300">
                                    <p>Subtotal</p>
                                    <p class="mt-1 text-lg font-semibold text-cyan-300" data-cart-line-total="{{ $product->id }}">${{ number_format($item['line_total'], 2) }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <aside class="space-y-4">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                            <h2 class="text-lg font-semibold text-white">Order summary</h2>
                            <div class="mt-4 space-y-3 text-sm text-slate-300">
                                <div class="flex items-center justify-between">
                                    <span>Items in cart</span>
                                    <span data-cart-total-items-display>{{ $totals['item_count'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Selected items</span>
                                    <span data-cart-selected-quantity-display>{{ $totals['selected_item_count'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-white">Total</span>
                                    <span class="text-xl font-bold text-cyan-300" data-cart-subtotal>${{ number_format($totals['subtotal'], 2) }}</span>
                                </div>
                            </div>

                            @php
                                $checkoutDisabled = ($totals['selected_count'] ?? 0) === 0;
                            @endphp

                            <p class="mt-4 text-xs text-red-300 {{ $checkoutDisabled ? '' : 'hidden' }}" data-cart-selection-warning>Select at least one item to continue.</p>

                            @auth
                                <a href="{{ route('checkout.show') }}" data-cart-checkout class="mt-5 block rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-4 py-3 text-center text-sm font-semibold text-slate-950 shadow transition transform {{ $checkoutDisabled ? 'pointer-events-none opacity-40' : 'hover:scale-[1.02]' }}" aria-disabled="{{ $checkoutDisabled ? 'true' : 'false' }}">Proceed to checkout</a>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const currencyFormatter = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });

            const subtotalEl = document.querySelector('[data-cart-subtotal]');
            const selectedQuantityEls = document.querySelectorAll('[data-cart-selected-quantity], [data-cart-selected-quantity-display]');
            const totalItemsEls = document.querySelectorAll('[data-cart-total-items], [data-cart-total-items-display]');
            const checkoutButton = document.querySelector('[data-cart-checkout]');
            const warningEl = document.querySelector('[data-cart-selection-warning]');

            const updateTotals = (totals) => {
                if (!totals) {
                    return;
                }

                const formattedSubtotal = currencyFormatter.format(Number(totals.subtotal ?? 0));

                if (subtotalEl) {
                    subtotalEl.textContent = formattedSubtotal;
                }

                selectedQuantityEls.forEach(el => {
                    if (el.hasAttribute('data-cart-selected-quantity')) {
                        el.textContent = totals.selected_item_count ?? 0;
                    }

                    if (el.hasAttribute('data-cart-selected-quantity-display')) {
                        el.textContent = totals.selected_item_count ?? 0;
                    }
                });

                totalItemsEls.forEach(el => {
                    if (el.hasAttribute('data-cart-total-items')) {
                        el.textContent = totals.item_count ?? 0;
                    }

                    if (el.hasAttribute('data-cart-total-items-display')) {
                        el.textContent = totals.item_count ?? 0;
                    }
                });

                const hasSelection = (totals.selected_count ?? 0) > 0;

                if (checkoutButton) {
                    checkoutButton.classList.toggle('pointer-events-none', !hasSelection);
                    checkoutButton.classList.toggle('opacity-40', !hasSelection);
                    checkoutButton.setAttribute('aria-disabled', hasSelection ? 'false' : 'true');
                    if (hasSelection) {
                        checkoutButton.classList.add('hover:scale-[1.02]');
                    } else {
                        checkoutButton.classList.remove('hover:scale-[1.02]');
                    }
                }

                if (warningEl) {
                    warningEl.classList.toggle('hidden', hasSelection);
                }
            };

            const handleError = (element, message) => {
                const container = element.closest('[data-cart-item]');
                const errorEl = container?.querySelector('[data-cart-item-error]');

                if (errorEl) {
                    errorEl.textContent = message ?? 'Unable to update cart. Please try again.';
                    errorEl.classList.remove('hidden');
                } else if (message) {
                    alert(message);
                }
            };

            const clearError = (element) => {
                const container = element.closest('[data-cart-item]');
                const errorEl = container?.querySelector('[data-cart-item-error]');

                if (errorEl) {
                    errorEl.classList.add('hidden');
                    errorEl.textContent = '';
                }
            };

            const sendRequest = async (url, payload) => {
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken ?? '',
                    },
                    body: JSON.stringify(payload),
                });

                if (!response.ok) {
                    const data = await response.json().catch(() => ({}));
                    const errorMessage = data.message || (data.errors ? Object.values(data.errors).flat()[0] : null);
                    const error = new Error(errorMessage ?? 'Request failed');
                    error.details = data;
                    throw error;
                }

                return response.json();
            };

            document.querySelectorAll('[data-cart-quantity-input]').forEach(input => {
                const url = input.closest('form')?.getAttribute('action');
                if (!url) {
                    return;
                }

                input.dataset.submittedValue = input.value;

                const triggerUpdate = () => {
                    const previousValue = input.dataset.submittedValue ?? input.value;
                    clearError(input);
                    input.classList.add('opacity-60');
                    input.setAttribute('disabled', 'disabled');

                    sendRequest(url, { quantity: Number(input.value) || 1 })
                        .then(data => {
                            if (data?.item && typeof data.item.quantity !== 'undefined') {
                                input.value = data.item.quantity;
                                input.dataset.submittedValue = String(data.item.quantity);
                            }
                            if (data?.item && data.item.product_id) {
                                const lineTotalEl = document.querySelector(`[data-cart-line-total="${data.item.product_id}"]`);
                                if (lineTotalEl) {
                                    lineTotalEl.textContent = currencyFormatter.format(Number(data.item.line_total ?? 0));
                                }
                            }

                            updateTotals(data?.totals);
                        })
                        .catch(error => {
                            input.value = previousValue;
                            handleError(input, error.message);
                        })
                        .finally(() => {
                            input.removeAttribute('disabled');
                            input.classList.remove('opacity-60');
                        });
                };

                let debounceTimer;

                const scheduleUpdate = () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(triggerUpdate, 500);
                };

                input.addEventListener('input', scheduleUpdate);
                input.addEventListener('change', scheduleUpdate);
            });

            document.querySelectorAll('[data-cart-select-input]').forEach(checkbox => {
                const url = checkbox.dataset.cartSelectUrl;
                if (!url) {
                    return;
                }

                checkbox.addEventListener('change', () => {
                    const previous = !checkbox.checked;

                    clearError(checkbox);
                    checkbox.setAttribute('disabled', 'disabled');

                    sendRequest(url, { selected: checkbox.checked })
                        .then(data => {
                            updateTotals(data?.totals);
                        })
                        .catch(error => {
                            checkbox.checked = previous;
                            handleError(checkbox, error.message);
                        })
                        .finally(() => {
                            checkbox.removeAttribute('disabled');
                        });
                });
            });
        });
    </script>
@endsection
