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

        <div class="text-center mt-8 pt-6 border-t border-slate-100 dark:border-slate-850">
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
