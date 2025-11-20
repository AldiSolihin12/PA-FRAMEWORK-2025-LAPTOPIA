@extends('components.layouts.app')

@section('content')
    <section class="flex min-h-[70vh] items-center justify-center bg-slate-950 py-24">
        <div class="mx-auto max-w-xl text-center">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl border border-white/10 bg-indigo-500/10 text-indigo-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-10 w-10">
                    <path d="M12 2.25a.75.75 0 0 1 .705.485l1.221 3.124 3.344.176a.75.75 0 0 1 .421 1.343l-2.63 2.276.786 3.259a.75.75 0 0 1-1.116.81L12 12.871l-2.731 1.854a.75.75 0 0 1-1.116-.81l.786-3.259-2.63-2.276a.75.75 0 0 1 .422-1.343l3.343-.176 1.221-3.124a.75.75 0 0 1 .705-.485Z" />
                </svg>
            </div>
            <h1 class="text-4xl font-semibold text-white">503 · Sedang Pemeliharaan</h1>
            <p class="mt-3 text-sm text-slate-400">Laptopia sedang melakukan pemeliharaan sistem untuk meningkatkan pengalaman Anda. Mohon kembali lagi beberapa saat lagi.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('welcome') }}" class="rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-5 py-2.5 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02]">Beranda</a>
                <a href="mailto:support@laptopia.id" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-white/5">Butuh Bantuan?</a>
            </div>
        </div>
    </section>
@endsection
