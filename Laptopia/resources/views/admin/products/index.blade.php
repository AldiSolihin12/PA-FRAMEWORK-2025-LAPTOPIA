@extends('components.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@section('content')
<section class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 py-12 px-6">
    <div class="mx-auto max-w-7xl space-y-8 text-slate-100">
        <header class="reveal flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Products</h1>
                <p class="text-sm text-slate-400">Manage catalogue, images, and specifications.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn-primary reveal delay-75">
                <span>Create product</span>
            </a>
        </header>

        @if (session('status'))
            <div class="reveal rounded-3xl border border-emerald-400/30 bg-emerald-500/10 px-5 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <form method="GET" class="reveal flex flex-col gap-4 rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl md:flex-row md:items-center md:justify-between">
            <div class="flex-1">
                <label for="search" class="sr-only">Search</label>
                <input id="search" name="search" value="{{ $search }}" placeholder="Search by name, brand, or category"
                    class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                Search
            </button>
        </form>

        <div class="reveal overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10">
                    <thead class="bg-white/10">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Brand</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Updated</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-white/5">
                        @forelse ($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $image = Str::startsWith($product->image, ['http://', 'https://'])
                                            ? $product->image
                                            : asset('storage/' . $product->image);
                                    @endphp
                                    <img src="{{ $image }}" alt="{{ $product->name }}" class="h-14 w-14 rounded-xl border border-white/10 object-cover" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-slate-100">{{ $product->name }}</div>
                                    <div class="text-xs text-slate-400 line-clamp-1 max-w-[280px]">
                                        {{ $product->details->description ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                    {{ $product->brand }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                    {{ $product->category?->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                                    ${{ number_format($product->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                    {{ $product->stock }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                                    {{ $product->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="text-cyan-300 transition hover:text-cyan-200">Edit</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline-block" onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-300 transition hover:text-rose-200">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-6 text-center text-sm text-slate-400">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-white/10 px-6 py-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
