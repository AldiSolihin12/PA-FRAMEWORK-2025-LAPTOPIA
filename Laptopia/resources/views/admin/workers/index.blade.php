@extends('components.layouts.app')

@section('content')
    <section class="bg-slate-950 py-16">
        <div class="mx-auto max-w-6xl space-y-8 px-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold text-white">Worker management</h1>
                    <p class="text-sm text-slate-400">Manage worker accounts to handle confirmations and deliveries.</p>
                </div>
                <a href="{{ route('admin.workers.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    Add worker
                </a>
            </div>

            @if (session('status'))
                <div class="rounded-2xl border border-emerald-400/40 bg-emerald-500/10 px-5 py-3 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5">
                <table class="min-w-full divide-y divide-white/10">
                    <thead class="bg-white/5">
                        <tr class="text-left text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Creation date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm text-slate-300">
                        @forelse ($workers as $worker)
                            <tr class="transition hover:bg-white/5">
                                <td class="px-6 py-4 font-semibold text-white">{{ $worker->name }}</td>
                                <td class="px-6 py-4">{{ $worker->email }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500">{{ $worker->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500">There are no workers registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-slate-400">
                {{ $workers->links() }}
            </div>
        </div>
    </section>
@endsection
