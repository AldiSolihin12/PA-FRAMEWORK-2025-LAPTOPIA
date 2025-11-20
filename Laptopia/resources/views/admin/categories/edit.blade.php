@extends('components.layouts.app')

@section('content')
<section class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 py-12 px-6">
    <div class="reveal mx-auto max-w-3xl space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8 text-slate-100 backdrop-blur-xl">
        <header class="space-y-2">
            <h1 class="text-3xl font-bold text-white">Edit category</h1>
            <p class="text-sm text-slate-400">Rename or adjust existing category.</p>
        </header>

        @if ($errors->any())
            <div class="rounded-xl border border-rose-400/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-slate-200">Name</label>
                <input id="name" name="name" value="{{ old('name', $category->name) }}" required
                    class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-cyan-400 focus:outline-none" />
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 px-5 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-white/10">Cancel</a>
                <button type="submit" class="btn-primary px-6 py-2.5">Update category</button>
            </div>
        </form>
    </div>
</section>
@endsection
