{{--
  <x-profile.password-strength> — Password strength indicator bar.

  Props:
    - model: Alpine.js x-model expression to watch
--}}
@props(['model' => 'newPassword'])

<div x-show="{{ $model }}.length > 0" x-cloak class="mt-2 space-y-1.5">
    <div class="flex gap-1">
        <template x-for="i in 5">
            <div class="h-1 flex-1 rounded-full transition-all duration-300"
                 :class="{
                     'bg-rose-500':   passwordStrength <= 1,
                     'bg-amber-500':  passwordStrength === 2,
                     'bg-yellow-400': passwordStrength === 3,
                     'bg-emerald-400': passwordStrength === 4,
                     'bg-emerald-500': passwordStrength === 5,
                 }"
                 :style="i <= passwordStrength ? 'opacity:1' : 'opacity:0.15'">
            </div>
        </template>
    </div>
    <p class="text-xs font-medium transition-colors"
       :class="{
           'text-rose-500':    passwordStrength <= 1,
           'text-amber-500':   passwordStrength === 2,
           'text-yellow-500':  passwordStrength === 3,
           'text-emerald-500': passwordStrength >= 4,
       }"
       x-text="['', 'Very Weak', 'Weak', 'Fair', 'Strong', 'Very Strong'][passwordStrength]">
    </p>
</div>
