<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()
            ->wishlistItems()
            ->with(['product.details', 'product.reviews'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('wishlist.index', compact('items'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->user()->wishlistItems()->firstOrCreate([
            'product_id' => $product->id,
        ]);

        return back()->with('status', __('Product added to wishlist.'));
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->user()->wishlistItems()->where('product_id', $product->id)->delete();

        return back()->with('status', __('Product removed from wishlist.'));
    }
}
