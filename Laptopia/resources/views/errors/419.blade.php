@extends('components.layouts.app')

@section('content')
    <section class="flex min-h-[70vh] items-center justify-center bg-slate-950 py-24">
        <div class="mx-auto max-w-xl text-center">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl border border-white/10 bg-amber-500/10 text-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-10 w-10">
                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .66.39l8.25 15a.75.75 0 0 1-.66 1.11H3.75a.75.75 0 0 1-.66-1.11l8.25-15a.75.75 0 0 1 .66-.39Zm0 5.25a.75.75 0 0 0-.75.75v4.5a.75.75 0 0 0 1.5 0V8.25A.75.75 0 0 0 12 7.5Zm0 8.25a1.125 1.125 0 1 0 1.125 1.125A1.125 1.125 0 0 0 12 15.75Z" clip-rule="evenodd" />
                </svg>
            </div>
            <h1 class="text-4xl font-semibold text-white">419 · Sesi Berakhir</h1>
            <p class="mt-3 text-sm text-slate-400">Permintaan Anda kedaluwarsa. Muat ulang halaman dan coba lagi untuk melanjutkan proses.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ url()->previous() }}" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-white/5">Muat Ulang</a>
                <a href="{{ route('welcome') }}" class="rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-5 py-2.5 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02]">Beranda</a>
            </div>
        </div>
    </section>
@endsection
