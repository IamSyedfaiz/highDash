<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 transition-colors duration-300"
    x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
          sidebarOpen: false 
      }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Lead CRM</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body
    class="h-full antialiased text-slate-900 bg-slate-50 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300">
    <div class="min-h-full">
        <!-- Sidebar Navigation -->
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true" x-cloak>
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" x-show="sidebarOpen"
                x-transition:enter="duration-300" x-transition:leave="duration-300"></div>
            <div class="fixed inset-0 flex">
                <div class="relative mr-16 flex w-full max-w-xs flex-1 transform transition duration-300"
                    x-show="sidebarOpen" x-transition:enter="-translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="translate-x-0" x-transition:leave-end="-translate-x-full">
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white dark:bg-slate-900 px-6 pb-4">
                        <div class="flex h-16 shrink-0 items-center">
                            <span
                                class="text-2xl font-bold bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent">LeadPro
                                CRM</span>
                        </div>
                        <nav class="flex flex-1 flex-col">
                            <ul role="list" class="-mx-2 space-y-1">
                                @include('layouts.sidebar-links')
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div
                class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-6 pb-4 shadow-xl">
                <div class="flex h-16 shrink-0 items-center">
                    <span
                        class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">LeadPro
                        CRM</span>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                @include('layouts.sidebar-links')
                            </ul>
                        </li>
                        <li class="mt-auto">
                            <div
                                class="flex items-center gap-x-4 py-3 text-sm font-semibold leading-6 text-slate-700 dark:text-slate-200 border-t border-slate-100 dark:border-slate-800">
                                <img class="h-10 w-10 rounded-xl shadow-sm ring-1 ring-slate-900/10 dark:ring-white/10"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF"
                                    alt="">
                                <div class="flex-1 truncate">
                                    <h4 class="truncate font-semibold">{{ Auth::user()->name }}</h4>
                                    <p class="text-xs text-slate-500 font-normal truncate uppercase">
                                        {{ Auth::user()->roles->first()->name ?? 'User' }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="lg:pl-72">
            <!-- Header -->
            <div
                class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md px-4 sm:px-6 lg:px-8 shadow-sm">
                <button @click="sidebarOpen = true" type="button"
                    class="-m-2.5 p-2.5 text-slate-700 dark:text-slate-300 lg:hidden text-indigo-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 items-center">
                    <div class="relative flex flex-1">
                        @if ($currentSession = Auth::user()->currentSession)
                            <div
                                class="hidden md:flex items-center gap-4 bg-slate-50 dark:bg-slate-800/50 px-4 py-2 rounded-2xl border border-slate-100 dark:border-slate-800">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Live
                                        Session</span>
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span class="text-xs font-black text-slate-900 dark:text-white"
                                            id="global-live-timer">00:00:00</span>
                                    </div>
                                </div>
                                <div class="h-8 w-px bg-slate-200 dark:bg-slate-700"></div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Started
                                        At</span>
                                    <span
                                        class="text-xs font-black text-slate-600 dark:text-slate-300">{{ $currentSession->login_at->format('h:i A') }}</span>
                                </div>
                            </div>
                            <script>
                                (function () {
                                    const loginTime = new Date("{{ $currentSession->login_at->toIso8601String() }}").getTime();
                                    const timerLabel = document.getElementById('global-live-timer');

                                    function updateTimer() {
                                        const now = new Date().getTime();
                                        const diff = now - loginTime;
                                        if (diff < 0) return;
                                        const hours = Math.floor(diff / (1000 * 60 * 60));
                                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                                        timerLabel.innerText =
                                            `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                                    }
                                    setInterval(updateTimer, 1000);
                                    updateTimer();
                                })();
                            </script>
                        @endif
                    </div>

                    <!-- Theme Switcher -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                        type="button"
                        class="p-2.5 text-slate-500 dark:text-slate-400 hover:text-indigo-600 transition-colors">
                        <svg x-show="!darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                        <svg x-show="darkMode" class="h-6 w-6 text-amber-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 3v2.25m0 13.5V21m8.966-8.966h-2.25m-13.5 0h-2.25m15.364-7.364l-1.591 1.591M6.742 17.258l-1.591 1.591m12.728 0l-1.591-1.591M6.742 6.742L5.151 5.151M12 7.5a4.5 4.5 0 110 9 4.5 4.5 0 010-9z" />
                        </svg>
                    </button>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button"
                            class="p-2.5 text-slate-500 dark:text-slate-400 hover:text-indigo-600 transition-colors relative">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span
                                    class="absolute top-2 right-2 block h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-900 animate-bounce"></span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 z-50 mt-2.5 w-80 origin-top-right rounded-3xl bg-white dark:bg-slate-900 shadow-2xl border border-slate-100 dark:border-slate-800 focus:outline-none overflow-hidden">
                            <div
                                class="px-6 py-4 border-b border-slate-50 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                                <h2 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">
                                    Alerts Center</h2>
                                <span
                                    class="text-[9px] font-bold text-slate-400">{{ Auth::user()->unreadNotifications->count() }}
                                    New</span>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                @forelse(Auth::user()->unreadNotifications as $notification)
                                    <a href="{{ isset($notification->data['lead_id']) ? route('leads.show', $notification->data['lead_id']) : '#' }}"
                                        class="block px-6 py-5 hover:bg-slate-50 dark:hover:bg-indigo-900/10 transition-colors border-b border-slate-50 dark:border-slate-800 last:border-0"
                                        @click="open = false">
                                        <div class="flex gap-4">
                                            <div
                                                class="mt-1 h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p
                                                    class="text-xs font-black text-slate-900 dark:text-white leading-tight mb-1">
                                                    {{ $notification->data['company_name'] ?? 'System Notice' }}</p>
                                                <p class="text-[10px] text-slate-500 line-clamp-2 leading-relaxed">
                                                    {{ $notification->data['message'] }}</p>
                                                <p
                                                    class="text-[8px] text-slate-400 mt-2 uppercase font-black tracking-tighter">
                                                    {{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-6 py-12 text-center">
                                        <div
                                            class="h-12 w-12 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <svg class="h-6 w-6 text-slate-300" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Inbox is
                                            Clear</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="h-6 w-px bg-slate-200 dark:border-slate-800"></div>

                    <!-- User menu drop -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="-m-1.5 flex items-center p-1.5">
                            <span class="sr-only">Open user menu</span>
                            <img class="h-9 w-9 rounded-full bg-slate-50 ring-2 ring-indigo-500"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF"
                                alt="">
                            <span class="hidden lg:flex lg:items-center">
                                <span
                                    class="ml-4 text-sm font-semibold leading-6 text-slate-900 dark:text-slate-100">{{ Auth::user()->name }}</span>
                                <svg class="ml-2 h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 z-10 mt-2.5 w-44 origin-top-right rounded-xl bg-white dark:bg-slate-800 py-2 shadow-2xl ring-1 ring-slate-900/5 dark:ring-white/5 focus:outline-none"
                            x-cloak>
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm leading-6 text-slate-900 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-700">Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm leading-6 text-rose-600 font-semibold hover:bg-rose-50 dark:hover:bg-rose-900/20">Sign
                                    out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div
                            class="mb-8 p-4 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-2xl font-black text-sm uppercase tracking-widest flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif
                    @if(session('error'))
                        <div
                            class="mb-8 p-4 bg-rose-100 border border-rose-200 text-rose-800 rounded-2xl font-black text-sm uppercase tracking-widest flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>

</html>