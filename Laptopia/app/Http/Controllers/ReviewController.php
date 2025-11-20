<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = $request->user();

        $hasShippedOrder = $user->orders()
            ->whereIn('status', [Order::STATUS_DELIVERED])
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();

        if (! $hasShippedOrder) {
            throw ValidationException::withMessages([
                'rating' => __('Only customers with delivered orders can review this product.'),
            ]);
        }

        $user->reviews()->updateOrCreate(
            ['product_id' => $product->id],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return back()->with('status', __('Thanks for sharing your feedback!'));
    }
}
