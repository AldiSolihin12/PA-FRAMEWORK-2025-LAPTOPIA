@extends('components.layouts.app')

@section('content')
    <section class="flex min-h-[70vh] items-center justify-center bg-slate-950 py-24">
        <div class="mx-auto max-w-xl text-center">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl border border-white/10 bg-sky-500/10 text-sky-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-10 w-10">
                    <path d="M12 2a9 9 0 1 0 9 9 9.01 9.01 0 0 0-9-9Zm0 13a1.25 1.25 0 1 1 1.25-1.25A1.25 1.25 0 0 1 12 15Zm1.5-5.25a1.5 1.5 0 0 1-3 0V7a1.5 1.5 0 0 1 3 0Z" />
                </svg>
            </div>
            <h1 class="text-4xl font-semibold text-white">404 · Halaman Tidak Ditemukan</h1>
            <p class="mt-3 text-sm text-slate-400">Kami tidak dapat menemukan halaman yang Anda cari. Periksa kembali alamat URL atau jelajahi katalog Laptopia.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('welcome') }}#products" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-white/5">Lihat Katalog</a>
                <a href="{{ route('welcome') }}" class="rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-5 py-2.5 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02]">Beranda</a>
            </div>
        </div>
    </section>
@endsection
