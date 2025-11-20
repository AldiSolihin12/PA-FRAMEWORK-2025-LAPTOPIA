<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->user()->hasAnyRole(['admin', 'worker'])) {
            $orders = Order::with(['items.product.details', 'user'])
                ->latest()
                ->paginate(15)
                ->withQueryString();
        } else {
            $orders = $request->user()
                ->orders()
                ->with(['items.product.details'])
                ->latest()
                ->paginate(10)
                ->withQueryString();
        }

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorizeAccess($request->user(), $order);

        $order->load(['items.product.details', 'user']);

        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeAccess($request->user(), $order);

        $validated = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
            'tracking_number' => ['nullable', 'string', 'max:120'],
        ]);

        $status = $validated['status'];
        $trackingNumber = $validated['tracking_number'] ?? null;

        if (! $this->canChangeToStatus($request->user(), $order, $status)) {
            abort(403);
        }

        $updates = ['status' => $status];

        if ($status === Order::STATUS_CONFIRMED) {
            $updates['confirmed_at'] = $order->confirmed_at ?? Carbon::now();
        }

        if ($status === Order::STATUS_SHIPPED) {
            if (blank($trackingNumber)) {
                return back()->withErrors([
                    'tracking_number' => __('Masukkan nomor resi sebelum menandai pesanan sebagai dikirim.'),
                ])->withInput();
            }

            if (! $order->confirmed_at) {
                $updates['confirmed_at'] = Carbon::now();
            }

            $updates['shipped_at'] = Carbon::now();
            $updates['tracking_number'] = $trackingNumber;
        } elseif (! blank($trackingNumber)) {
            $updates['tracking_number'] = $trackingNumber;
        }

        if ($status === Order::STATUS_DELIVERED) {
            $updates['delivered_at'] = $order->delivered_at ?? Carbon::now();
        }

        if ($status === Order::STATUS_PENDING) {
            $updates['confirmed_at'] = null;
            $updates['shipped_at'] = null;
            $updates['delivered_at'] = null;
            if ($trackingNumber === null && ! $request->filled('tracking_number')) {
                $updates['tracking_number'] = null;
            }
        }

        $order->update($updates);

        return back()->with('status', __('Order status updated.'));
    }

    private function authorizeAccess($user, Order $order): void
    {
        if ($user->hasAnyRole(['admin', 'worker'])) {
            return;
        }

        if ($order->user_id !== $user->id) {
            abort(403);
        }
    }

    private function canChangeToStatus($user, Order $order, string $status): bool
    {
        if ($user->hasAnyRole(['admin', 'worker'])) {
            return match ($status) {
                Order::STATUS_PENDING => true,
                Order::STATUS_CONFIRMED => $order->status === Order::STATUS_PENDING,
                Order::STATUS_SHIPPED => in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_CONFIRMED], true),
                Order::STATUS_DELIVERED => $order->status === Order::STATUS_SHIPPED,
                default => false,
            };
        }

        if ($order->user_id !== $user->id) {
            return false;
        }

        return $status === Order::STATUS_DELIVERED && $order->status === Order::STATUS_SHIPPED;
    }
}
