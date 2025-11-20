<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class CartManager
{
    private const SESSION_KEY = 'cart.items';
    private const SESSION_KEY_SELECTED = 'cart.selected';

    public function items(): Collection
    {
        $rawItems = collect(Session::get(self::SESSION_KEY, []));

        if ($rawItems->isEmpty()) {
            Session::forget(self::SESSION_KEY_SELECTED);
            return collect();
        }

        $products = Product::with(['details', 'category'])
            ->whereIn('id', $rawItems->pluck('product_id'))
            ->get()
            ->keyBy('id');

        $cartProductIds = $rawItems->pluck('product_id')->map(fn ($id) => (int) $id);
        $selectedIds = $this->syncSelectedIds($cartProductIds);
        $hasExplicitSelection = Session::has(self::SESSION_KEY_SELECTED);

        return $rawItems->map(function (array $item) use ($products, $selectedIds, $hasExplicitSelection) {
            $product = $products->get($item['product_id']);

            if (! $product) {
                return null;
            }

            $quantity = (int) $item['quantity'];
            $lineTotal = $product->price * $quantity;
            $isSelected = $hasExplicitSelection ? $selectedIds->contains($product->id) : true;

            return [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'line_total' => $lineTotal,
                'selected' => $isSelected,
            ];
        })->filter();
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $quantity = max(1, $quantity);

        $cartItems = collect(Session::get(self::SESSION_KEY, []));

        $existing = $cartItems->firstWhere('product_id', $product->id);
        $newQuantity = $existing ? $existing['quantity'] + $quantity : $quantity;

        $this->ensureStock($product, $newQuantity);

        $updatedItems = $cartItems->reject(fn ($item) => $item['product_id'] === $product->id)
            ->push([
                'product_id' => $product->id,
                'quantity' => $newQuantity,
            ]);

        Session::put(self::SESSION_KEY, $updatedItems->values()->all());
        $this->setSelected($product, true);
    }

    public function update(Product $product, int $quantity): void
    {
        $quantity = max(1, $quantity);
        $this->ensureStock($product, $quantity);

        $cartItems = collect(Session::get(self::SESSION_KEY, []));

        if (! $cartItems->contains('product_id', $product->id)) {
            $this->add($product, $quantity);
            return;
        }

        $updatedItems = $cartItems->map(function (array $item) use ($product, $quantity) {
            if ($item['product_id'] === $product->id) {
                $item['quantity'] = $quantity;
            }

            return $item;
        });

        Session::put(self::SESSION_KEY, $updatedItems->values()->all());
    }

    public function remove(Product $product): void
    {
        $cartItems = collect(Session::get(self::SESSION_KEY, []))
            ->reject(fn ($item) => $item['product_id'] === $product->id);

        Session::put(self::SESSION_KEY, $cartItems->values()->all());
        $this->setSelected($product, false);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
        Session::forget(self::SESSION_KEY_SELECTED);
    }

    public function totals(): array
    {
        $items = $this->items();
        $selectedItems = $items->filter(fn ($item) => $item['selected']);

        $subtotal = $selectedItems->sum('line_total');
        $itemCount = $items->sum('quantity');

        return [
            'subtotal' => $subtotal,
            'item_count' => $itemCount,
            'selected_item_count' => $selectedItems->sum('quantity'),
            'selected_count' => $selectedItems->count(),
        ];
    }

    public function isEmpty(): bool
    {
        return $this->items()->isEmpty();
    }

    public function selectedItems(): Collection
    {
        return $this->items()->filter(fn ($item) => $item['selected']);
    }

    public function setSelected(Product $product, bool $selected): void
    {
        $cartItems = collect(Session::get(self::SESSION_KEY, []));

        if ($cartItems->isEmpty()) {
            Session::forget(self::SESSION_KEY_SELECTED);
            return;
        }

        $cartProductIds = $cartItems->pluck('product_id')->map(fn ($id) => (int) $id);
        $selectedIds = $this->syncSelectedIds($cartProductIds);

        if ($selected) {
            $selectedIds = $selectedIds->push($product->id)->intersect($cartProductIds)->unique()->values();
        } else {
            $selectedIds = $selectedIds->reject(fn ($id) => $id === $product->id)->values();
        }

        $this->setSelectedIds($selectedIds);
    }

    public function removeProducts(array $productIds): void
    {
        if (empty($productIds)) {
            return;
        }

        $ids = collect($productIds)->map(fn ($id) => (int) $id);

        $cartItems = collect(Session::get(self::SESSION_KEY, []))
            ->reject(fn ($item) => $ids->contains((int) $item['product_id']));

        Session::put(self::SESSION_KEY, $cartItems->values()->all());

        $selectedIds = $this->selectedIds()->reject(fn ($id) => $ids->contains($id))->values();
        $this->setSelectedIds($selectedIds);

        if ($cartItems->isEmpty()) {
            Session::forget(self::SESSION_KEY_SELECTED);
        }
    }

    private function ensureStock(Product $product, int $quantity): void
    {
        if ($product->stock < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => __('Requested quantity exceeds available stock (available: :stock).', ['stock' => $product->stock]),
            ]);
        }
    }

    private function selectedIds(): Collection
    {
        return collect(Session::get(self::SESSION_KEY_SELECTED, []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
    }

    private function setSelectedIds(Collection $ids): void
    {
        if ($ids->isEmpty()) {
            Session::put(self::SESSION_KEY_SELECTED, []);
            return;
        }

        Session::put(self::SESSION_KEY_SELECTED, $ids->values()->all());
    }

    private function syncSelectedIds(Collection $cartProductIds): Collection
    {
        $selectedIds = $this->selectedIds()->intersect($cartProductIds)->values();

        if (! Session::has(self::SESSION_KEY_SELECTED)) {
            $selectedIds = $cartProductIds->values();
        }

        $this->setSelectedIds($selectedIds);

        return $selectedIds;
    }
}
