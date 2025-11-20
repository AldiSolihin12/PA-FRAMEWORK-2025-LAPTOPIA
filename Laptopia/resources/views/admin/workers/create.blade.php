@extends('components.layouts.app')

@section('content')
    <section class="bg-slate-950 py-16">
        <div class="mx-auto max-w-3xl space-y-8 px-6">
            <div>
                <a href="{{ route('admin.workers.index') }}" class="text-sm font-semibold text-cyan-300 hover:text-cyan-200">← Kembali ke daftar worker</a>
                <h1 class="mt-3 text-3xl font-semibold text-white">Tambah worker baru</h1>
                <p class="mt-2 text-sm text-slate-400">Worker bertanggung jawab untuk konfirmasi pesanan dan proses pengiriman.</p>
            </div>

            <form method="POST" action="{{ route('admin.workers.store') }}" class="space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="name" class="text-xs uppercase tracking-[0.3em] text-slate-500">Nama</label>
                        <input id="name" name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" />
                        @error('name')
                            <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="text-xs uppercase tracking-[0.3em] text-slate-500">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" />
                        @error('email')
                            <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="text-xs uppercase tracking-[0.3em] text-slate-500">Password</label>
                        <input id="password" type="password" name="password" required class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" />
                        @error('password')
                            <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="text-xs uppercase tracking-[0.3em] text-slate-500">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none" />
                    </div>
                </div>

                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 px-6 py-3 text-sm font-semibold text-slate-950 shadow hover:scale-[1.02]">Simpan worker</button>
            </form>
        </div>
    </section>
@endsection
