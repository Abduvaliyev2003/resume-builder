<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 dark:bg-slate-900" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AI Resume Builder') - SaaS Platform</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Merriweather:wght@400;700;900&family=Nunito:wght@400;600;700;800&family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#2563eb', // Core blue
                            600: '#1d4ed8',
                            650: '#1e48c6',
                            700: '#1e40af',
                            800: '#1e3a8a',
                            900: '#1e3a8a',
                        },
                        slate: {
                            250: '#d8dee8',
                            350: '#aab5c4',
                            650: '#55657a',
                            705: '#334155',
                            750: '#293548',
                            850: '#172033',
                        },
                        red: {
                            850: '#7f1d1d',
                        }
                    },
                    transitionDuration: {
                        250: '250ms',
                        350: '350ms',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom scrollbar for modern feel */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
    </style>
    @yield('styles')
</head>
<body class="h-full text-slate-800 dark:text-slate-200 antialiased transition-colors duration-200">

    <!-- Toast Notification Container -->
    <div x-data="{ toasts: [] }" 
         @toast.window="const id = Date.now(); toasts.push({ id, message: $event.detail.message, type: $event.detail.type || 'info' }); setTimeout(() => toasts = toasts.filter(t => t.id !== id), 4000)"
         class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="transform translate-y-2 opacity-0"
                 x-transition:enter-end="transform translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 :class="{
                     'bg-blue-600': toast.type === 'info',
                     'bg-emerald-600': toast.type === 'success',
                     'bg-rose-600': toast.type === 'error',
                     'bg-amber-600': toast.type === 'warning'
                 }"
                 class="text-white px-4 py-3 rounded-xl shadow-lg flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <template x-if="toast.type === 'success'">
                        <i class="fa-solid fa-circle-check"></i>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <i class="fa-solid fa-circle-info"></i>
                    </template>
                    <span class="text-sm font-medium" x-text="toast.message"></span>
                </div>
                <button @click="show = false" class="text-white/80 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </template>
    </div>

    <!-- Main Navigation -->
    <nav class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 sticky top-0 z-40 transition-colors duration-200"
         x-data="{ open: false, isAuthenticated: @json(auth()->check()) }"
         x-init="window.addEventListener('auth-change', () => isAuthenticated = @json(auth()->check()) || localStorage.getItem('auth_token') !== null)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/dashboard" class="flex items-center gap-2 text-xl font-extrabold font-outfit text-primary-600 dark:text-primary-400">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>ResumAI</span>
                    </a>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">
                    <!-- Language Switcher Dropdown -->
                    <div class="relative" x-data="{ langOpen: false }" @click.outside="langOpen = false">
                        <button @click="langOpen = !langOpen" 
                                class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition flex items-center gap-1.5 text-sm font-semibold">
                            <i class="fa-solid fa-globe"></i>
                            <span class="uppercase">{{ app()->getLocale() }}</span>
                        </button>
                        <div x-show="langOpen" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="transform opacity-0 scale-95 translate-y-1"
                             class="absolute right-0 top-full mt-2 w-32 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-lg py-1 z-50">
                            <button onclick="changeLanguage('en')" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition text-left">
                                🇺🇸 {{ __('app.lang_en') }}
                            </button>
                            <button onclick="changeLanguage('uz')" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition text-left">
                                🇺🇿 {{ __('app.lang_uz') }}
                            </button>
                            <button onclick="changeLanguage('ru')" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition text-left">
                                🇷🇺 {{ __('app.lang_ru') }}
                            </button>
                        </div>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                            class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    <!-- Authenticated Links -->
                    <div x-show="isAuthenticated" class="hidden md:flex items-center gap-3">
                        <a href="/dashboard" class="text-sm font-medium hover:text-primary-600 transition">{{ __('app.nav_dashboard') }}</a>
                        <a href="/templates" class="text-sm font-medium hover:text-primary-600 transition">{{ __('app.nav_templates') }}</a>
                        <!-- User Avatar Dropdown -->
                        <div class="relative" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false">
                            <button @click="userMenuOpen = !userMenuOpen"
                                    class="flex items-center gap-2 p-1 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                <div id="nav-avatar-wrapper" class="w-8 h-8 rounded-full overflow-hidden bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                    <i class="fa-solid fa-user text-primary-600 dark:text-primary-400 text-sm"></i>
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }"></i>
                            </button>
                            <div x-show="userMenuOpen" x-cloak
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="transform opacity-0 scale-95 translate-y-1"
                                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="transform opacity-0 scale-95 translate-y-1"
                                 class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-lg py-1 z-50">
                                <a href="/profile" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <i class="fa-solid fa-user-circle text-slate-400 w-4 text-center"></i> {{ __('app.nav_profile') }}
                                </a>
                                <a href="/dashboard" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <i class="fa-solid fa-gauge text-slate-400 w-4 text-center"></i> {{ __('app.nav_dashboard') }}
                                </a>
                                <div class="border-t border-slate-100 dark:border-slate-800 my-1"></div>
                                <button onclick="logoutUser()" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-rose-500 hover:bg-rose-50/50 dark:hover:bg-rose-950/20 transition text-left">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> {{ __('app.nav_logout') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Links -->
                    <div x-show="!isAuthenticated" class="hidden md:flex items-center gap-4">
                        <a href="/login" class="text-sm font-medium hover:text-primary-600 transition">{{ __('app.nav_login') }}</a>
                        <a href="/register" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition">{{ __('app.nav_register') }}</a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="open = !open" class="md:hidden p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-cloak class="md:hidden border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 pt-2 pb-4 flex flex-col gap-2">
            <div x-show="isAuthenticated" class="flex flex-col gap-2">
                <a href="/dashboard" class="px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition text-sm">{{ __('app.nav_dashboard') }}</a>
                <a href="/templates" class="px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition text-sm">{{ __('app.nav_templates') }}</a>
                <a href="/profile" class="px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition text-sm">{{ __('app.nav_profile') }}</a>
                <button onclick="logoutUser()" class="px-3 py-2 rounded-lg text-rose-500 hover:bg-rose-50/50 dark:hover:bg-rose-950/20 text-left text-sm font-medium">{{ __('app.nav_logout') }}</button>
            </div>
            <div x-show="!isAuthenticated" class="flex flex-col gap-2">
                <a href="/login" class="px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition text-sm">{{ __('app.nav_login') }}</a>
                <a href="/register" class="px-3 py-2 bg-primary-600 text-white rounded-lg text-center text-sm font-semibold transition">{{ __('app.nav_register') }}</a>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- JavaScript Helper Functions -->
    <script>
        // Global AJAX setup to append Bearer token
        function getAuthHeaders() {
            const token = localStorage.getItem('auth_token');
            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }
            return headers;
        }

        // Global check auth helper
        function requireAuth() {
            const serverAuthenticated = @json(auth()->check());

            if (!serverAuthenticated && !localStorage.getItem('auth_token')) {
                window.location.href = '/login';
            }
        }

        function requireGuest() {
            const serverAuthenticated = @json(auth()->check());

            if (serverAuthenticated) {
                window.location.href = '/dashboard';
                return;
            }

            if (localStorage.getItem('auth_token')) {
                localStorage.removeItem('auth_token');
                window.dispatchEvent(new Event('auth-change'));
            }
        }

        async function logoutUser() {
            try {
                await fetch('/logout', {
                    method: 'POST',
                    headers: getAuthHeaders()
                });
            } catch (e) {
                // Ignore failure of logout endpoint, clear local storage anyway
            }
            localStorage.removeItem('auth_token');
            window.dispatchEvent(new Event('auth-change'));
            window.location.href = '/login';
        }

        async function changeLanguage(locale) {
            try {
                const response = await fetch('/language', {
                    method: 'POST',
                    headers: getAuthHeaders(),
                    body: JSON.stringify({ locale })
                });
                if (response.ok) {
                    window.location.reload();
                }
            } catch (e) {
                showToast('Error changing language', 'error');
            }
        }

        // Toast dispatch helper
        function showToast(message, type = 'info') {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message, type }
            }));
        }
    </script>
    @yield('scripts')
</body>
</html>
