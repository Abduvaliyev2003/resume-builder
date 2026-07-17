@extends('layouts.app')

@section('title', __('app.reset_password'))

@section('content')
<div class="flex items-center justify-center min-h-[60vh]" x-data="forgotPasswordForm()">
    <div class="w-full max-w-md bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-xl">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-extrabold font-outfit text-slate-900 dark:text-slate-100">{{ __('app.reset_password') }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">{{ __('app.forgot_password_subtitle') }}</p>
        </div>

        <div x-show="successMessage" x-cloak class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            <span x-text="successMessage"></span>
        </div>

        <form @submit.prevent="submit" x-show="!successMessage" class="flex flex-col gap-5">
            <x-input 
                label="{{ __('app.email_address') }}" 
                name="email" 
                type="email" 
                placeholder="you@example.com" 
                required="true"
                model="email"
            />

            <x-button type="submit" variant="primary" class="w-full mt-2" ::disabled="loading">
                <span x-show="!loading">{{ __('app.send_reset_link') }}</span>
                <span x-show="loading" x-cloak class="flex items-center gap-2">
                    <i class="fa-solid fa-spinner animate-spin"></i> {{ __('app.sending_link') }}
                </span>
            </x-button>
        </form>

        <div class="text-center mt-8 pt-6 border-t border-slate-100 dark:border-slate-850">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('app.remember_password') }} 
                <a href="/login" class="font-bold text-primary-600 hover:text-primary-700 transition">{{ __('app.sign_in') }}</a>
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    requireGuest();

    function forgotPasswordForm() {
        return {
            email: '',
            loading: false,
            successMessage: '',
            async submit() {
                this.loading = true;
                
                // Simulate delay and send response
                setTimeout(() => {
                    this.loading = false;
                    this.successMessage = "A recovery link has been simulated & sent to " + this.email;
                    showToast('Simulation: Reset link sent successfully!', 'success');
                }, 1000);
            }
        }
    }
</script>
@endsection
