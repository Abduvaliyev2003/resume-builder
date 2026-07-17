@extends('layouts.app')

@section('title', __('app.verify_email_meta'))

@section('content')
<div class="max-w-md mx-auto rounded-3xl border border-slate-200 bg-white p-8 shadow-xl dark:border-slate-800 dark:bg-slate-900">
    <div class="flex items-center gap-3 text-primary-600 mb-6">
        <i class="fa-solid fa-envelope-circle-check text-3xl"></i>
        <h1 class="text-2xl font-extrabold font-outfit text-slate-900 dark:text-slate-100">{{ __('app.verify_email_title') }}</h1>
    </div>

    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
        {!! __('app.verify_email_desc', ['email' => '<span class="font-semibold text-slate-900 dark:text-slate-100">' . e($email) . '</span>']) !!}
    </p>

    @if (session('status') === 'verification-code-sent')
        <div class="mt-4 rounded-xl border border-emerald-250 bg-emerald-50 dark:bg-emerald-950/20 p-3.5 text-sm text-emerald-700 dark:text-emerald-400">
            <i class="fa-solid fa-circle-check mr-1.5"></i> {{ __('app.verify_email_sent') }}
        </div>
    @endif

    @error('code')
        <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 dark:bg-rose-950/20 p-3.5 text-sm text-rose-600 dark:text-rose-400">
            <i class="fa-solid fa-triangle-exclamation mr-1.5"></i> {{ $message }}
        </div>
    @enderror

    <!-- OTP Form -->
    <div x-data="otpForm()" class="mt-6">
        <form method="POST" action="{{ route('verification.verify') }}" @submit="submitForm">
            @csrf
            
            <div class="flex gap-2.5 justify-center mb-6">
                <template x-for="i in 6" :key="i">
                    <input 
                        type="text" 
                        maxlength="1" 
                        inputmode="numeric"
                        pattern="[0-9]*"
                        class="w-12 h-12 text-center text-2xl font-bold border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-150"
                        x-model="code[i-1]"
                        @input="handleInput($event, i-1)"
                        @keydown="handleKeyDown($event, i-1)"
                        @paste="handlePaste($event)"
                        :x-ref="'input_' + (i-1)"
                    />
                </template>
            </div>
            
            <input type="hidden" name="code" :value="code.join('')" />
            
            <button type="submit" class="w-full rounded-xl bg-primary-600 px-4 py-3 text-sm font-semibold text-white hover:bg-primary-700 shadow-lg shadow-primary-500/10 hover:shadow-primary-500/20 transition duration-150" ::disabled="code.join('').length !== 6">
                {{ __('app.verify_button') }}
            </button>
        </form>
    </div>

    <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-850 text-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('app.didnt_receive_code') }} 
                <button type="submit" class="font-bold text-primary-600 hover:text-primary-700 transition">
                    {{ __('app.resend_code') }}
                </button>
            </p>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function otpForm() {
    return {
        code: ['', '', '', '', '', ''],
        handleInput(e, index) {
            const val = e.target.value;
            // Allow only numbers
            if (!/^[0-9]$/.test(val)) {
                this.code[index] = '';
                return;
            }
            this.code[index] = val;
            
            // Auto focus next input
            if (val && index < 5) {
                this.$refs['input_' + (index + 1)].focus();
            }
        },
        handleKeyDown(e, index) {
            // Handle backspace
            if (e.key === 'Backspace') {
                if (!this.code[index] && index > 0) {
                    this.code[index - 1] = '';
                    this.$refs['input_' + (index - 1)].focus();
                } else {
                    this.code[index] = '';
                }
            }
        },
        handlePaste(e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text');
            if (/^[0-9]{6}$/.test(text)) {
                for (let i = 0; i < 6; i++) {
                    this.code[i] = text[i];
                }
                this.$refs['input_5'].focus();
            }
        },
        submitForm(e) {
            if (this.code.join('').length !== 6) {
                e.preventDefault();
                showToast('Please enter all 6 digits of the code.', 'error');
            }
        }
    }
}
</script>
@endsection
