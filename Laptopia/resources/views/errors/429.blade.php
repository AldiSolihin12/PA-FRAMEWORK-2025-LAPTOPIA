@extends('components.layouts.app')

@section('content')
    <section class="flex min-h-[70vh] items-center justify-center bg-slate-950 py-24">
        <div class="mx-auto max-w-xl text-center">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl border border-white/10 bg-purple-500/10 text-purple-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-10 w-10">
                    <path fill-rule="evenodd" d="M5.25 4.5A2.25 2.25 0 0 0 3 6.75v10.5A2.25 2.25 0 0 0 5.25 19.5h13.5A2.25 2.25 0 0 0 21 17.25V6.75A2.25 2.25 0 0 0 18.75 4.5H5.25ZM6 12a.75.75 0 0 1 .75-.75H9a.75.75 0 0 1 0 1.5H6.75A.75.75 0 0 1 6 12Zm4.5 0a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 0 1.5H11.25a.75.75 0 0 1-.75-.75Zm4.5 0a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5h-1.5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                </svg>
            </div>
            <h1 class="text-4xl font-semibold text-white">429 · Terlalu Banyak Permintaan</h1>
            <p class="mt-3 text-sm text-slate-400">Anda melakukan permintaan terlalu cepat. Mohon tunggu beberapa saat sebelum mencoba lagi.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ url()->previous() }}" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-white/5">Coba Lagi</a>
                <a href="{{ route('welcome') }}" class="rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-5 py-2.5 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02]">Beranda</a>
            </div>
        </div>
    </section>
@endsection
