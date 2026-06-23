@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]" x-data="loginForm()">
    <div class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-xl">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-extrabold font-outfit text-slate-900 dark:text-slate-100">Welcome Back</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">Sign in to resume creating with AI power</p>
        </div>

        <!-- Global Error Alert -->
        <div x-show="errorMessage" x-cloak class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 text-rose-600 dark:text-rose-400 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span x-text="errorMessage"></span>
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-5">
            <x-input 
                label="Email Address" 
                name="email" 
                type="email" 
                placeholder="you@example.com" 
                required="true"
                model="email"
            />

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <a href="/forgot-password" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition">Forgot password?</a>
                </div>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••" 
                    required 
                    x-model="password"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150"
                />
            </div>

            <x-button type="submit" variant="primary" class="w-full mt-2" ::disabled="loading">
                <span x-show="!loading">Sign In</span>
                <span x-show="loading" x-cloak class="flex items-center gap-2">
                    <i class="fa-solid fa-spinner animate-spin"></i> Signing In...
                </span>
            </x-button>
        </form>

        <div class="text-center mt-8 pt-6 border-t border-slate-100 dark:border-slate-850">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Don't have an account? 
                <a href="/register" class="font-bold text-primary-600 hover:text-primary-700 transition">Create Account</a>
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Prevent authenticated users
    requireGuest();

    function loginForm() {
        return {
            email: '',
            password: '',
            loading: false,
            errorMessage: '',
            async submit() {
                this.loading = true;
                this.errorMessage = '';

                try {
                    const response = await fetch('/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: this.email,
                            password: this.password
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Authentication failed. Please check your credentials.');
                    }

                    // Save Sanctum Token
                    localStorage.setItem('auth_token', data.token);
                    window.dispatchEvent(new Event('auth-change'));
                    
                    showToast('Logged in successfully!', 'success');
                    
                    // Simple delay to let session sync
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
