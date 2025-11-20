@extends('components.layouts.app')

@section('content')
<section class="relative flex min-h-[85vh] items-center justify-center px-6 py-20 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">

    <div class="w-full max-w-6xl grid gap-10 lg:grid-cols-[1.1fr_1fr]">

        {{-- LEFT PANEL --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl px-10 py-12 text-white shadow-[0_0_40px_-10px_rgba(56,189,248,0.3)]">

            {{-- Background: image or fallback gradient --}}
            <div class="absolute inset-0 bg-cover bg-center"
                 style="background-image: url('{{ asset('images/login-bg.jpg') }}');">
            </div>

            {{-- Dark overlay --}}
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-slate-900/80 to-slate-950/60"></div>

            <div class="relative flex h-full flex-col justify-between gap-10">

                {{-- Title --}}
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 px-3 py-1 rounded-full backdrop-blur-lg text-sm text-cyan-300">
                        <span class="h-2 w-2 bg-cyan-400 rounded-full animate-pulse"></span>
                        Laptopia System
                    </span>

                    <div>
                        <h1 class="text-3xl font-extrabold">Welcome Back</h1>
                        <p class="mt-3 max-w-sm text-slate-300 leading-relaxed">
                            Sign in to manage your account, view your orders, and continue your Laptopia shopping experience.
                        </p>
                    </div>
                </div>

                {{-- Features --}}
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="rounded-3xl border border-white/10 bg-white/5 px-5 py-6 backdrop-blur-lg shadow-xl">
                        <p class="text-xs font-semibold uppercase tracking-wider text-cyan-300">Order Tracking</p>
                        <p class="mt-3 text-2xl font-bold text-white">Real-time</p>
                        <p class="mt-2 text-xs text-slate-300">Monitor your Laptopia orders anytime.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/5 px-5 py-6 backdrop-blur-lg shadow-xl">
                        <p class="text-xs font-semibold uppercase tracking-wider text-cyan-300">Support</p>
                        <p class="mt-3 text-2xl font-bold text-white">24 / 7</p>
                        <p class="mt-2 text-xs text-slate-300">We’re here whenever you need help.</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- RIGHT PANEL — FORM --}}
        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl px-10 py-12 shadow-[0_0_30px_-10px_rgba(56,189,248,0.25)]">

            <div class="mb-8 space-y-3 text-center">
                <h2 class="text-2xl font-bold text-white">Sign in to your account</h2>
                <p class="text-sm text-slate-400">Manage orders, wishlist, and account settings.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-300 bg-red-500/10 px-5 py-4 text-sm font-medium text-red-300">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                {{-- EMAIL --}}
                <div class="space-y-1.5">
                    <label for="email" class="text-sm font-semibold text-slate-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500/40"
                        placeholder="you@example.com">
                </div>

                {{-- PASSWORD --}}
                <div class="space-y-1.5">
                    <label for="password" class="text-sm font-semibold text-slate-300">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500/40"
                        placeholder="••••••••">
                </div>

                {{-- REMEMBER --}}
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-400 cursor-pointer">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 rounded border-white/20 bg-white/10 text-cyan-400 focus:ring-cyan-500 cursor-pointer">
                        Remember me
                    </label>

                    <a href="{{ route('register') }}" class="font-semibold text-cyan-300 hover:text-cyan-200 transition">
                        Create account
                    </a>
                </div>

                {{-- BUTTON --}}
                <button type="submit"
                    class="w-full flex justify-center rounded-xl bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-500 px-6 py-3 text-slate-950 font-semibold shadow-lg shadow-cyan-500/40 hover:scale-[1.02] transition">
                    Sign In
                </button>
            </form>

            <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 px-5 py-4 text-xs font-medium uppercase tracking-[0.25em] text-cyan-300 text-center">
                LAPTOPIA SECURE LOGIN
            </div>

        </div>

    </div>
</section>
@endsection
