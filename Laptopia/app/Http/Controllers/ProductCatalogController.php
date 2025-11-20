<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ProductCatalogController extends Controller
{
    public function __invoke(): View
    {
        $featuredProducts = Product::with(['details', 'category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', [
            'title' => 'Laptopia Storefront',
            'featuredProducts' => $featuredProducts,
        ]);
    }

    public function index(Request $request): View
    {
        $filters = $this->extractFilters($request);

        $products = Product::with(['details', 'category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->when($filters['search'], function ($query) use ($filters) {
                $query->where(function ($inner) use ($filters) {
                    $inner->where('name', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('brand', 'like', '%' . $filters['search'] . '%')
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', '%' . $filters['search'] . '%'))
                        ->orWhereHas('details', function ($detailsQuery) use ($filters) {
                            $detailsQuery->where('processor', 'like', '%' . $filters['search'] . '%')
                                ->orWhere('graphics', 'like', '%' . $filters['search'] . '%')
                                ->orWhere('ram', 'like', '%' . $filters['search'] . '%')
                                ->orWhere('storage', 'like', '%' . $filters['search'] . '%');
                        });
                });
            })
            ->when($filters['brand'], fn ($query) => $query->whereIn('brand', $filters['brand']))
            ->when($filters['category'], fn ($query) => $query->where('category_id', $filters['category']))
            ->when($filters['price_min'], fn ($query) => $query->where('price', '>=', $filters['price_min']))
            ->when($filters['price_max'], fn ($query) => $query->where('price', '<=', $filters['price_max']))
            ->tap(function ($query) use ($filters) {
                match ($filters['sort']) {
                    'termurah' => $query->orderBy('price'),
                    'termahal' => $query->orderByDesc('price'),
                    'rating' => $query->orderByDesc('reviews_avg_rating')
                        ->orderByDesc('reviews_count'),
                    default => $query->latest(),
                };
            })
            ->paginate(12)
            ->withQueryString();

        $brands = Product::query()->select('brand')->distinct()->orderBy('brand')->pluck('brand');
        $categories = Category::orderBy('name')->get();

        $priceRange = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        return view('products.index', [
            'title' => 'Laptopia Catalogue',
            'products' => $products,
            'filters' => $filters,
            'brands' => $brands,
            'categories' => $categories,
            'priceRange' => $priceRange,
        ]);
    }

    private function extractFilters(Request $request): array
    {
        $brand = collect(Arr::wrap($request->input('brand')))->filter()->values()->all();

        return [
            'search' => $request->string('search')->toString(),
            'brand' => $brand,
            'category' => $request->input('category'),
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
            'sort' => $request->input('sort', 'terbaru'),
        ];
    }
}
