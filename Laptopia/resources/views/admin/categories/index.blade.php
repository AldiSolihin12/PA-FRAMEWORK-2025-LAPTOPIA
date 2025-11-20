@extends('components.layouts.app')

@section('content')
<section class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 py-12 px-6">
    <div class="mx-auto max-w-5xl space-y-8 text-slate-100">
        <header class="reveal flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Categories</h1>
                <p class="text-sm text-slate-400">Organise products into clear collections.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn-primary reveal delay-75">
                Create category
            </a>
        </header>

        @if (session('status'))
            <div class="reveal rounded-3xl border border-emerald-400/30 bg-emerald-500/10 px-5 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="reveal overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10">
                    <thead class="bg-white/10">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-300">Products</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-white/5">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-100">{{ $category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">{{ $category->products_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-cyan-300 transition hover:text-cyan-200">Edit</a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline-block" onsubmit="return confirm('Delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-300 transition hover:text-rose-200">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-6 text-center text-sm text-slate-400">No categories yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-white/10 px-6 py-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
