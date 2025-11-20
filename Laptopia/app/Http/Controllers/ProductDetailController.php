<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductDetailController extends Controller
{
    public function __invoke(Product $product): View
    {
        $product->load([
            'category',
            'details',
            'reviews.user',
            'tags',
        ]);

        $relatedProducts = Product::with(['details', 'category'])
            ->withAvg('reviews', 'rating')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $averageRating = $product->reviews->avg('rating');
        $reviewCount = $product->reviews->count();

        $userReview = null;
        $canReview = false;
        if (Auth::check()) {
            $user = Auth::user();
            $userReview = $user->reviews()->where('product_id', $product->id)->first();
            $canReview = $user->orders()
                ->whereIn('status', [Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])
                ->whereHas('items', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->exists();
        }

        return view('product-details', [
            'title' => $product->name,
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'averageRating' => $averageRating,
            'reviewCount' => $reviewCount,
            'userReview' => $userReview,
            'canReview' => $canReview,
        ]);
    }
}
