<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\CartManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartManager $cart): View
    {
        $items = $cart->items();
        $totals = $cart->totals();

        return view('cart.index', compact('items', 'totals'));
    }

    public function store(Request $request, Product $product, CartManager $cart): RedirectResponse
    {
        try {
            $cart->add($product, (int) $request->input('quantity', 1));
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors())->withInput();
        }

        return back()->with('status', __('Product added to cart.'));
    }

    public function update(Request $request, Product $product, CartManager $cart): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $cart->update($product, (int) $validated['quantity']);
        } catch (ValidationException $exception) {
            if ($request->expectsJson()) {
                throw $exception;
            }

            return back()->withErrors($exception->errors())->withInput();
        }

        if ($request->expectsJson()) {
            $items = $cart->items();
            $item = $items->first(fn ($cartItem) => $cartItem['product']->id === $product->id);
            $totals = $cart->totals();

            return response()->json([
                'message' => __('Cart updated.'),
                'item' => $item ? [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                    'selected' => $item['selected'],
                ] : null,
                'totals' => $totals,
            ]);
        }

        return back()->with('status', __('Cart updated.'));
    }

    public function select(Request $request, Product $product, CartManager $cart): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'selected' => ['required', 'boolean'],
        ]);

        $cart->setSelected($product, (bool) $validated['selected']);

        if ($request->expectsJson()) {
            $items = $cart->items();
            $item = $items->first(fn ($cartItem) => $cartItem['product']->id === $product->id);
            $totals = $cart->totals();

            return response()->json([
                'message' => __('Cart updated.'),
                'item' => $item ? [
                    'product_id' => $product->id,
                    'selected' => (bool) ($item['selected'] ?? false),
                ] : null,
                'totals' => $totals,
            ]);
        }

        return back()->with('status', __('Cart updated.'));
    }

    public function destroy(Product $product, CartManager $cart): RedirectResponse
    {
        $cart->remove($product);

        return back()->with('status', __('Product removed from cart.'));
    }

    public function clear(CartManager $cart): RedirectResponse
    {
        $cart->clear();

        return back()->with('status', __('Cart cleared.'));
    }
}
