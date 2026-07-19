@extends('layouts.app')

@section('title', __('app.create_account'))

@section('content')
<div class="flex items-center justify-center min-h-[75vh]" x-data="registerForm()">
    <div class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-xl">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-extrabold font-outfit text-slate-900 dark:text-slate-100">{{ __('app.register_title') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">{{ __('app.register_subtitle') }}</p>
        </div>

        <!-- Global Error Alert -->
        <div x-show="errorMessage" x-cloak class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 text-rose-600 dark:text-rose-400 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span x-text="errorMessage"></span>
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-5">
            <x-input 
                label="{{ __('app.full_name') }}" 
                name="name" 
                placeholder="John Doe" 
                required="true"
                model="name"
            />

            <x-input 
                label="{{ __('app.email_address') }}" 
                name="email" 
                type="email" 
                placeholder="john@example.com" 
                required="true"
                model="email"
            />

            <x-input 
                label="{{ __('app.password') }}" 
                name="password" 
                type="password" 
                placeholder="{{ __('app.min_characters') }}" 
                required="true"
                model="password"
            />

            <x-input 
                label="{{ __('app.confirm_password') }}" 
                name="password_confirmation" 
                type="password" 
                placeholder="{{ __('app.retype_password') }}" 
                required="true"
                model="password_confirmation"
            />

            <x-button type="submit" variant="primary" class="w-full mt-2" ::disabled="loading">
                <span x-show="!loading">{{ __('app.create_free_account') }}</span>
                <span x-show="loading" x-cloak class="flex items-center gap-2">
                    <i class="fa-solid fa-spinner animate-spin"></i> {{ __('app.creating_account') }}
                </span>
            </x-button>
        </form>

        <!-- Social OAuth Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-white dark:bg-slate-900 px-3 text-slate-400 dark:text-slate-500 font-medium tracking-wider">
                    {{ __('app.or_continue_with') }}
                </span>
            </div>
        </div>

        <!-- Social Buttons Grid -->
        <div class="grid grid-cols-2 gap-3">
            <!-- Google -->
            <a href="{{ route('oauth.redirect', 'google') }}"
               class="flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-150 shadow-sm hover:shadow group">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Google
            </a>

            <!-- GitHub -->
            <a href="{{ route('oauth.redirect', 'github') }}"
               class="flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-150 shadow-sm hover:shadow group">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                </svg>
                GitHub
            </a>

            <!-- Facebook -->
            <a href="{{ route('oauth.redirect', 'facebook') }}"
               class="col-span-2 flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-150 shadow-sm hover:shadow group">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="#1877F2">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Facebook
            </a>

        </div>

        <div class="text-center mt-6 pt-5 border-t border-slate-100 dark:border-slate-800">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('app.already_have_account') }} 
                <a href="/login" class="font-bold text-primary-600 hover:text-primary-700 transition">{{ __('app.sign_in') }}</a>
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Prevent authenticated users
    requireGuest();

    function registerForm() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            loading: false,
            errorMessage: '',
            async submit() {
                if (this.password !== this.password_confirmation) {
                    this.errorMessage = "Passwords do not match.";
                    return;
                }
                
                this.loading = true;
                this.errorMessage = '';

                try {
                    const response = await fetch('/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            name: this.name,
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Registration failed. Please resolve errors.');
                    }

                    // Save token
                    localStorage.setItem('auth_token', data.token);
                    window.dispatchEvent(new Event('auth-change'));
                    
                    showToast('Account registered successfully!', 'success');
                    
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 500);

                } catch (err) {
                    this.errorMessage = err.message;
                    showToast(err.message, 'error');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endsection
