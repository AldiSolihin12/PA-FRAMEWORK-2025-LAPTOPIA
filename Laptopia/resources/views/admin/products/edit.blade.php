@extends('components.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $currentImage = Str::startsWith($product->image, ['http://', 'https://'])
        ? $product->image
        : asset('storage/' . $product->image);
@endphp

@section('content')
<section class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 py-12 px-6">
    <div class="mx-auto max-w-4xl space-y-8 rounded-3xl border border-white/10 bg-white/5 p-8 text-slate-100 shadow-2xl shadow-slate-950/40 backdrop-blur-xl">
        <header class="space-y-2">
            <h1 class="text-3xl font-bold text-white">Edit product</h1>
            <p class="text-sm text-slate-400">Update product details or replace its media.</p>
        </header>

        @if ($errors->any())
            <div class="rounded-xl border border-rose-400/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-slate-200">Name</label>
                    <input id="name" name="name" value="{{ old('name', $product->name) }}" required
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>

                <div>
                    <label for="brand" class="mb-1 block text-sm font-medium text-slate-200">Brand</label>
                    <input id="brand" name="brand" value="{{ old('brand', $product->brand) }}" required
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>

                <div>
                    <label for="price" class="mb-1 block text-sm font-medium text-slate-200">Price (USD)</label>
                    <input id="price" name="price" value="{{ old('price', $product->price) }}" type="number" step="0.01" min="0" required
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>

                <div>
                    <label for="stock" class="mb-1 block text-sm font-medium text-slate-200">Inventory</label>
                    <input id="stock" name="stock" value="{{ old('stock', $product->stock) }}" type="number" min="0" required
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>

                <div>
                    <label for="category_id" class="mb-1 block text-sm font-medium text-slate-200">Category</label>
                    <select id="category_id" name="category_id" required
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm focus:border-cyan-400 focus:outline-none cursor-pointer">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="image" class="mb-1 block text-sm font-medium text-slate-200">Image</label>
                    <input id="image" name="image" type="file" accept="image/*"
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-2 text-slate-100 shadow-sm focus:border-cyan-400 focus:outline-none cursor-pointer" />
                    <p class="mt-2 text-xs text-slate-500">Upload high-resolution JPG or PNG up to 4MB. Leave empty to keep current image.</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <img src="{{ $currentImage }}" alt="{{ $product->name }}" class="h-24 w-24 rounded-xl border border-white/10 object-cover" />
                <span class="text-sm text-slate-400">Current image preview</span>
            </div>

            <div>
                <label for="description" class="mb-1 block text-sm font-medium text-slate-200">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none">{{ old('description', $product->details->description ?? '') }}</textarea>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="processor" class="mb-1 block text-sm font-medium text-slate-200">Processor</label>
                    <input id="processor" name="processor" value="{{ old('processor', $product->details->processor ?? '') }}" required
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>
                <div>
                    <label for="graphics" class="mb-1 block text-sm font-medium text-slate-200">Graphics</label>
                    <input id="graphics" name="graphics" value="{{ old('graphics', $product->details->graphics ?? '') }}"
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>
                <div>
                    <label for="ram" class="mb-1 block text-sm font-medium text-slate-200">Memory (RAM)</label>
                    <input id="ram" name="ram" value="{{ old('ram', $product->details->ram ?? '') }}" required
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>
                <div>
                    <label for="storage" class="mb-1 block text-sm font-medium text-slate-200">Storage</label>
                    <input id="storage" name="storage" value="{{ old('storage', $product->details->storage ?? '') }}" required
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>
                <div>
                    <label for="display" class="mb-1 block text-sm font-medium text-slate-200">Display</label>
                    <input id="display" name="display" value="{{ old('display', $product->details->display ?? '') }}"
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>
                <div>
                    <label for="battery" class="mb-1 block text-sm font-medium text-slate-200">Battery</label>
                    <input id="battery" name="battery" value="{{ old('battery', $product->details->battery ?? '') }}"
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>
                <div>
                    <label for="weight" class="mb-1 block text-sm font-medium text-slate-200">Weight</label>
                    <input id="weight" name="weight" value="{{ old('weight', $product->details->weight ?? '') }}"
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>
                <div>
                    <label for="ports" class="mb-1 block text-sm font-medium text-slate-200">Ports</label>
                    <input id="ports" name="ports" value="{{ old('ports', $product->details->ports ?? '') }}"
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>
                <div class="md:col-span-2">
                    <label for="operating_system" class="mb-1 block text-sm font-medium text-slate-200">Operating system</label>
                    <input id="operating_system" name="operating_system" value="{{ old('operating_system', $product->details->operating_system ?? '') }}"
                        class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 px-5 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-white/10 cursor-pointer">Cancel</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-6 py-2.5 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/30 hover:scale-[1.02] transition cursor-pointer">Update product</button>
            </div>
        </form>
    </div>
</section>
@endsection
