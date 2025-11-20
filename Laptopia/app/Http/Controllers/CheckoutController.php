<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\CartManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(CartManager $cart): View|RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => __('Your cart is empty. Add products before checking out.'),
            ]);
        }

        $items = $cart->selectedItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => __('Select at least one product to proceed to checkout.'),
            ]);
        }

        $totals = $cart->totals();
        $shippingOptions = $this->shippingOptions();

        return view('checkout.index', compact('items', 'totals', 'shippingOptions'));
    }

    public function store(Request $request, CartManager $cart): RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => __('Your cart is empty. Add products before checking out.'),
            ]);
        }

        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'max:30'],
            'recipient_email' => ['required', 'string', 'email', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_city' => ['required', 'string', 'max:120'],
            'shipping_postal_code' => ['required', 'string', 'max:20'],
            'shipping_method' => ['required', 'string', 'in:standard,express'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $items = $cart->selectedItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => __('Select at least one product to proceed to checkout.'),
            ]);
        }

        $totals = $cart->totals();
        $shippingCost = $this->resolveShippingCost($validated['shipping_method'], $totals['selected_item_count']);
        $orderTotal = $totals['subtotal'] + $shippingCost;

        foreach ($items as $item) {
            if ($item['product']->stock < $item['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => __(':product has only :stock units left.', [
                        'product' => $item['product']->name,
                        'stock' => $item['product']->stock,
                    ]),
                ]);
            }
        }

        $order = DB::transaction(function () use ($items, $totals, $validated, $request, $shippingCost, $orderTotal) {
            /** @var \App\Models\User $user */
            $user = $request->user();

            $order = Order::create([
                'user_id' => $user->id,
                'code' => $this->generateOrderCode(),
                'status' => Order::STATUS_PENDING,
                'subtotal' => $totals['subtotal'],
                'shipping_cost' => $shippingCost,
                'total' => $orderTotal,
                'items_count' => $totals['selected_item_count'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'recipient_email' => $validated['recipient_email'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_postal_code' => $validated['shipping_postal_code'],
                'shipping_method' => $validated['shipping_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                $product = $item['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['line_total'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        $cart->removeProducts($items->map(fn ($item) => $item['product']->id)->all());

        return redirect()
            ->route('orders.show', $order)
            ->with('status', __('Order placed successfully. Kami akan mengirimkan pembaruan status pengiriman.'));
    }

    private function shippingOptions(): array
    {
        return [
            'standard' => [
                'label' => __('Standard (3-5 hari)'),
                'cost' => 0.0,
            ],
            'express' => [
                'label' => __('Express (1-2 hari)'),
                'cost' => 25.0,
            ],
        ];
    }

    private function resolveShippingCost(string $method, int $itemCount): float
    {
        $options = $this->shippingOptions();

        if (! isset($options[$method])) {
            return 0.0;
        }

        $baseCost = (float) $options[$method]['cost'];

        if ($method === 'express' && $itemCount > 2) {
            return $baseCost + (5.0 * ($itemCount - 2));
        }

        return $baseCost;
    }

    private function generateOrderCode(): string
    {
        do {
            $code = 'LPT-' . Str::upper(Str::random(8));
        } while (Order::where('code', $code)->exists());

        return $code;
    }
}
