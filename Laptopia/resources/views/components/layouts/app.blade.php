<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{'Laptopia' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @php
        $isTesting = app()->environment('testing');
        $hasHotReload = file_exists(public_path('hot'));
        $hasViteManifest = file_exists(public_path('build/manifest.json'));
    @endphp

    @if (! $isTesting && ($hasHotReload || $hasViteManifest))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        /* Floating labels */
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1rem 0.25rem 1rem;
            border: 1.5px solid #d1d5db;
            /* gray-300 */
            border-radius: 0.5rem;
            background: transparent;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            /* indigo-500 */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }

        .form-label {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: #6b7280;
            /* gray-500 */
            font-size: 1rem;
            font-weight: 500;
            pointer-events: none;
            transition: all 0.3s ease;
            background: white;
            padding: 0 0.25rem;
            border-radius: 0.25rem;
        }

        .form-input:focus+.form-label,
        .form-input:not(:placeholder-shown)+.form-label {
            top: -0.5rem;
            left: 0.75rem;
            font-size: 0.75rem;
            color: #4f46e5;
            /* indigo-600 */
            font-weight: 600;
            letter-spacing: 0.05em;
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-900 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 relative overflow-x-hidden">

    <div class="pointer-events-none fixed inset-0 -z-10">
        <div class="absolute -top-32 left-1/2 h-[420px] w-[420px] -translate-x-1/2 rounded-full bg-gradient-to-br from-indigo-400/40 via-sky-400/30 to-transparent blur-3xl animate-slow-rotate"></div>
        <div class="absolute bottom-0 right-[-120px] h-[360px] w-[360px] rounded-full bg-gradient-to-br from-cyan-400/40 via-indigo-400/30 to-transparent blur-3xl"></div>
        <div class="absolute top-1/3 left-[-140px] h-[280px] w-[280px] rounded-full bg-gradient-to-br from-blue-500/25 via-indigo-300/30 to-transparent blur-3xl animate-float"></div>
    </div>

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main class="pt-24 md:pt-28">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navToggle = document.getElementById('nav-toggle');
            const navMenu = document.getElementById('nav-menu');

            if (navToggle && navMenu) {
                navToggle.addEventListener('click', () => {
                    navMenu.classList.toggle('hidden');
                });
            }

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', event => {
                    const targetId = anchor.getAttribute('href');
                    if (!targetId || targetId === '#') {
                        return;
                    }

                    const target = document.querySelector(targetId);
                    if (target) {
                        event.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        if (navMenu && !navMenu.classList.contains('hidden')) {
                            navMenu.classList.add('hidden');
                        }
                    }
                });
            });

            const revealTargets = document.querySelectorAll('.reveal');
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                revealTargets.forEach(el => el.classList.add('is-visible'));
                return;
            }

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.2,
                rootMargin: '0px 0px -60px 0px'
            });

            revealTargets.forEach(target => observer.observe(target));
        });
    </script>
</body>


</html>
