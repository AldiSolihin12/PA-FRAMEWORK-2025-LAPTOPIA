@extends('components.layouts.app')

@section('content')
    <section class="flex min-h-[70vh] items-center justify-center bg-slate-950 py-24">
        <div class="mx-auto max-w-xl text-center">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl border border-white/10 bg-red-500/10 text-red-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-10 w-10">
                    <path fill-rule="evenodd" d="M4.47 3.97a.75.75 0 0 1 1.06 0L12 10.44l6.47-6.47a.75.75 0 1 1 1.06 1.06L13.06 11.5l6.47 6.44a.75.75 0 0 1-1.06 1.06L12 12.56l-6.47 6.44a.75.75 0 0 1-1.06-1.06L10.94 11.5 4.47 5.03a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </div>
            <h1 class="text-4xl font-semibold text-white">403 · Akses Ditolak</h1>
            <p class="mt-3 text-sm text-slate-400">Anda tidak memiliki izin untuk membuka halaman ini. Jika Anda merasa ini sebuah kesalahan, hubungi tim Laptopia.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ url()->previous() }}" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-white/5">Kembali</a>
                <a href="{{ route('welcome') }}" class="rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-5 py-2.5 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02]">Beranda</a>
            </div>
        </div>
    </section>
@endsection
