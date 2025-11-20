<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $products = Product::with(['category', 'details'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('details', function ($detailsQuery) use ($search) {
                            $detailsQuery->where('processor', 'like', "%{$search}%")
                                ->orWhere('graphics', 'like', "%{$search}%")
                                ->orWhere('ram', 'like', "%{$search}%")
                                ->orWhere('storage', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'search'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['required', 'image', 'max:4096'],
            'description' => ['nullable', 'string'],
            'processor' => ['required', 'string', 'max:255'],
            'graphics' => ['nullable', 'string', 'max:255'],
            'ram' => ['required', 'string', 'max:255'],
            'storage' => ['required', 'string', 'max:255'],
            'display' => ['nullable', 'string', 'max:255'],
            'battery' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'string', 'max:255'],
            'ports' => ['nullable', 'string', 'max:255'],
            'operating_system' => ['nullable', 'string', 'max:255'],
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        $slug = $this->generateUniqueSlug($validated['name']);

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'brand' => $validated['brand'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image' => $imagePath,
            'category_id' => $validated['category_id'],
        ]);

        $product->details()->create([
            'description' => $validated['description'] ?? null,
            'processor' => $validated['processor'],
            'graphics' => $validated['graphics'] ?? null,
            'ram' => $validated['ram'],
            'storage' => $validated['storage'],
            'display' => $validated['display'] ?? null,
            'battery' => $validated['battery'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'ports' => $validated['ports'] ?? null,
            'operating_system' => $validated['operating_system'] ?? null,
        ]);

        return redirect()->route('admin.products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product): View
    {
        $product->load('details');
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:4096'],
            'description' => ['nullable', 'string'],
            'processor' => ['required', 'string', 'max:255'],
            'graphics' => ['nullable', 'string', 'max:255'],
            'ram' => ['required', 'string', 'max:255'],
            'storage' => ['required', 'string', 'max:255'],
            'display' => ['nullable', 'string', 'max:255'],
            'battery' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'string', 'max:255'],
            'ports' => ['nullable', 'string', 'max:255'],
            'operating_system' => ['nullable', 'string', 'max:255'],
        ]);

        $data = [
            'name' => $validated['name'],
            'brand' => $validated['brand'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'],
        ];

        if ($product->name !== $validated['name']) {
            $data['slug'] = $this->generateUniqueSlug($validated['name'], $product->id);
        }

        if ($request->hasFile('image')) {
            if ($product->image && ! Str::startsWith($product->image, ['http://', 'https://'])) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        $product->details()->updateOrCreate(
            [],
            [
                'description' => $validated['description'] ?? null,
                'processor' => $validated['processor'],
                'graphics' => $validated['graphics'] ?? null,
                'ram' => $validated['ram'],
                'storage' => $validated['storage'],
                'display' => $validated['display'] ?? null,
                'battery' => $validated['battery'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'ports' => $validated['ports'] ?? null,
                'operating_system' => $validated['operating_system'] ?? null,
            ]
        );

        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image && ! Str::startsWith($product->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted.');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}
