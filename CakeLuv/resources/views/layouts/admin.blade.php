<!DOCTYPE html>
<html lang="id" class="font-inter">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'CakeLuv') }} Admin</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind CSS with CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#0f172a',
                            hover: '#1e293b',
                            foreground: '#ffffff',
                        },
                        darkPrimary: {
                            DEFAULT: '#f8fafc',
                            hover: '#e2e8f0',
                            foreground: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Theme & Anti-FOUC Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'system';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (savedTheme === 'system' && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.5);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-[#090d16] text-slate-900 dark:text-slate-100 antialiased flex flex-col md:flex-row" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs md:hidden" 
         style="display: none;"></div>

    <!-- Sidebar (16rem / 256px wide) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-[#0c1222] border-r border-slate-200/80 dark:border-white/10 flex flex-col transition-transform duration-200 ease-in-out md:static md:h-screen shrink-0">
        
        <!-- Brand Header / Team Switcher Style -->
        <div class="h-16 px-4 flex items-center justify-between border-b border-slate-200/80 dark:border-white/10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="size-9 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center font-bold shadow-xs transition-transform group-hover:scale-105">
                    <i data-lucide="cake" class="size-5"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-semibold tracking-tight text-slate-900 dark:text-white leading-tight">CakeLuv</span>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-tight">Admin Workspace</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg">
                <i data-lucide="x" class="size-5"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-6 overflow-y-auto">
            <!-- General Group -->
            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Menu Utama</p>
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i data-lucide="layout-dashboard" class="size-4 shrink-0"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Pesanan -->
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i data-lucide="shopping-bag" class="size-4 shrink-0"></i>
                        <span class="flex-1">Pesanan</span>
                    </a>

                    <!-- Produk -->
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.products.*') ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i data-lucide="package" class="size-4 shrink-0"></i>
                        <span>Katalog Produk</span>
                    </a>

                    <!-- Pengaturan Web -->
                    <a href="{{ route('admin.settings.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i data-lucide="settings" class="size-4 shrink-0"></i>
                        <span>Pengaturan Web</span>
                    </a>
                </div>
            </div>

            <!-- External / Quick Links -->
            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Toko & Preview</p>
                <div class="space-y-1">
                    <a href="{{ route('home') }}" target="_blank" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100 transition-all">
                        <i data-lucide="external-link" class="size-4 shrink-0"></i>
                        <span>Kunjungi Toko</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- User Profile & Logout in Sidebar Footer -->
        <div class="p-3 border-t border-slate-200/80 dark:border-white/10 bg-slate-50/50 dark:bg-slate-900/30">
            <div class="flex items-center gap-3 px-2 py-1.5 mb-2">
                <div class="size-8 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 flex items-center justify-center font-semibold text-xs border border-slate-300 dark:border-white/10">
                    {{ substr(auth()->user()->name ?? 'Admin', 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-900 dark:text-slate-100 truncate">{{ auth()->user()->name ?? 'Admin CakeLuv' }}</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@cakeluv.com' }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center gap-2 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg transition-colors border border-transparent hover:border-rose-200 dark:hover:border-rose-900/40">
                    <i data-lucide="log-out" class="size-3.5"></i>
                    <span>Keluar dari Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        <!-- Sticky Header (h-16) -->
        <header id="top-header" class="sticky top-0 z-30 h-16 bg-white/90 dark:bg-[#0c1222]/90 backdrop-blur-md border-b border-slate-200/80 dark:border-white/10 px-4 sm:px-6 flex items-center justify-between gap-4">
            
            <div class="flex items-center gap-3">
                <!-- Mobile Menu Button -->
                <button @click="sidebarOpen = true" class="md:hidden text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white p-2 rounded-lg border border-slate-200 dark:border-white/10">
                    <i data-lucide="menu" class="size-4"></i>
                </button>

                <!-- Page Breadcrumbs -->
                <div class="hidden sm:flex items-center gap-2 text-sm">
                    <span class="text-slate-400 dark:text-slate-500">CakeLuv</span>
                    <span class="text-slate-300 dark:text-slate-700">/</span>
                    <span class="font-medium text-slate-800 dark:text-slate-200">@yield('title', 'Dashboard')</span>
                </div>
            </div>

            <!-- Header Right Section -->
            <div class="flex items-center gap-3">
                <!-- Search Button Mockup (Cmd+K style) -->
                <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-white/10 bg-slate-100/60 dark:bg-slate-900/60 text-xs text-slate-500 dark:text-slate-400 shadow-2xs">
                    <i data-lucide="search" class="size-3.5 text-slate-400"></i>
                    <span>Cari pesanan atau produk...</span>
                    <kbd class="ms-3 px-1.5 py-0.5 text-[10px] font-mono bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 rounded text-slate-500 dark:text-slate-400">⌘K</kbd>
                </div>

                <!-- 2-Mode Theme Switcher: Light vs Sistem -->
                <div x-data="{
                    currentTheme: (localStorage.getItem('theme') === 'light') ? 'light' : 'system',
                    setTheme(val) {
                        this.currentTheme = val;
                        localStorage.setItem('theme', val);
                        const isDark = (val === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                        if (isDark) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: val, isDark: isDark } }));
                    }
                }" 
                x-init="
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                        if (currentTheme === 'system') {
                            if (e.matches) {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: 'system', isDark: e.matches } }));
                        }
                    });
                "
                class="flex items-center rounded-lg border border-slate-200 dark:border-white/10 bg-slate-100/90 dark:bg-slate-900/90 p-0.5 shadow-2xs">
                    <!-- Light Button -->
                    <button type="button" 
                            @click="setTheme('light')" 
                            :class="currentTheme === 'light' ? 'bg-white text-slate-900 shadow-xs dark:bg-slate-800 dark:text-slate-100 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200'"
                            class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-md transition-all cursor-pointer"
                            title="Mode Terang (Light)">
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        <span class="font-medium text-xs">Light</span>
                    </button>

                    <!-- System Button -->
                    <button type="button" 
                            @click="setTheme('system')" 
                            :class="currentTheme === 'system' ? 'bg-white text-slate-900 shadow-xs dark:bg-slate-800 dark:text-slate-100 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200'"
                            class="flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-md transition-all cursor-pointer"
                            title="Bawaan Sistem (Mengikuti setelan perangkat)">
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3" />
                        </svg>
                        <span class="font-medium text-xs">Sistem</span>
                    </button>
                </div>

                <!-- Vertical divider -->
                <div class="h-6 w-px bg-slate-200 dark:bg-white/10 hidden sm:block"></div>

                <!-- Admin Avatar pill -->
                <div class="flex items-center gap-2">
                    <div class="size-8 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center font-bold text-xs shadow-xs">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body Scroll Area -->
        <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
            
            <!-- Success Notification Alert -->
            @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-xl text-sm shadow-xs" role="alert">
                <i data-lucide="check-circle-2" class="size-4 shrink-0 text-emerald-600 dark:text-emerald-400"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Error Notification Alert -->
            @if(session('error'))
            <div class="mb-6 flex items-center gap-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/50 text-rose-800 dark:text-rose-200 px-4 py-3 rounded-xl text-sm shadow-xs" role="alert">
                <i data-lucide="alert-circle" class="size-4 shrink-0 text-rose-600 dark:text-rose-400"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Initialize Lucide icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
