@extends('components.layouts.app')

@section('content')
<section class="relative flex min-h-[85vh] items-center justify-center px-6 py-20 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">

    <div class="w-full max-w-6xl grid gap-10 lg:grid-cols-[1.1fr_1fr]">

        {{-- LEFT PANEL (sama gaya dengan login) --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl px-10 py-12 text-white shadow-[0_0_40px_-10px_rgba(56,189,248,0.3)]">

            {{-- Background: image or fallback --}}
            <div class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('{{ asset('images/register-bg.jpg') }}');">
            </div>

            {{-- Overlay gelap --}}
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-slate-900/80 to-slate-950/60"></div>

            <div class="relative flex h-full flex-col justify-between gap-10">

                {{-- Header --}}
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 px-3 py-1 rounded-full backdrop-blur-lg text-sm text-cyan-300">
                        <span class="h-2 w-2 bg-cyan-400 rounded-full animate-pulse"></span>
                        Join Laptopia
                    </span>

                    <div>
                        <h1 class="text-3xl font-extrabold">Create Your Account</h1>
                        <p class="mt-3 max-w-sm text-slate-300 leading-relaxed">
                            Register to explore laptops, manage your wishlist, and track your orders in one modern dashboard.
                        </p>
                    </div>
                </div>

                {{-- Features --}}
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="rounded-3xl border border-white/10 bg-white/5 px-5 py-6 backdrop-blur-lg shadow-xl">
                        <p class="text-xs font-semibold uppercase tracking-wider text-cyan-300">Secure Access</p>
                        <p class="mt-3 text-2xl font-bold text-white">Encrypted</p>
                        <p class="mt-2 text-xs text-slate-300">Your account and data are fully protected.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/5 px-5 py-6 backdrop-blur-lg shadow-xl">
                        <p class="text-xs font-semibold uppercase tracking-wider text-cyan-300">Smart Tools</p>
                        <p class="mt-3 text-2xl font-bold text-white">Intuitive</p>
                        <p class="mt-2 text-xs text-slate-300">Compare laptop specs quickly and easily.</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- RIGHT PANEL — REGISTER FORM --}}
        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl px-10 py-12 shadow-[0_0_30px_-10px_rgba(56,189,248,0.25)]">

            <div class="mb-8 space-y-3 text-center">
                <h2 class="text-2xl font-bold text-white">Create a new account</h2>
                <p class="text-sm text-slate-400">Fill in your details to get started.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-300 bg-red-500/10 px-5 py-4 text-sm font-medium text-red-300">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                {{-- FULL NAME --}}
                <div class="space-y-1.5">
                    <label for="name" class="text-sm font-semibold text-slate-300">Full name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500/40"
                        placeholder="Your full name">
                </div>

                {{-- EMAIL --}}
                <div class="space-y-1.5">
                    <label for="email" class="text-sm font-semibold text-slate-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
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

                {{-- CONFIRM PASSWORD --}}
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="text-sm font-semibold text-slate-300">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-500/40"
                        placeholder="••••••••">
                </div>

                {{-- LOGIN LINK --}}
                <div class="text-sm text-slate-400">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-cyan-300 hover:text-cyan-200 transition">
                        Sign in
                    </a>
                </div>

                {{-- REGISTER BUTTON --}}
                <button type="submit"
                    class="w-full flex justify-center rounded-xl bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-500 px-6 py-3 text-slate-950 font-semibold shadow-lg shadow-cyan-500/40 hover:scale-[1.02] transition cursor-pointer">
                    Create Account
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
