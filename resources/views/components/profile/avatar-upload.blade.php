{{--
  <x-profile.avatar-upload> — Avatar upload with live preview.

  Props:
    - avatarUrl: current avatar URL string
    - userName: user name for initials fallback
--}}
@props([
    'avatarUrl' => null,
    'userName'  => 'User',
])

<div x-data="{
    previewUrl: '{{ $avatarUrl ?? '' }}',
    hasAvatar: {{ $avatarUrl ? 'true' : 'false' }},
    removeAvatar: false,

    handleFileChange(event) {
        const file = event.target.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Image must be under 2MB.', type: 'error' } }));
            event.target.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            this.previewUrl = e.target.result;
            this.hasAvatar = true;
            this.removeAvatar = false;
        };
        reader.readAsDataURL(file);
    },

    triggerRemove() {
        this.previewUrl = '';
        this.hasAvatar = false;
        this.removeAvatar = true;
        this.$refs.fileInput.value = '';
    }
}" class="flex flex-col sm:flex-row items-center gap-5">

    <!-- Avatar Preview -->
    <div class="relative flex-shrink-0">
        <div class="w-24 h-24 rounded-2xl overflow-hidden bg-primary-100 dark:bg-primary-900 border-2 border-slate-200 dark:border-slate-700 shadow-sm">
            <template x-if="hasAvatar && previewUrl">
                <img :src="previewUrl" alt="Profile photo" class="w-full h-full object-cover">
            </template>
            <template x-if="!hasAvatar || !previewUrl">
                <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-primary-600 dark:text-primary-400 font-outfit">
                    {{ strtoupper(substr($userName, 0, 1)) }}
                </div>
            </template>
        </div>
        <!-- Camera overlay on hover -->
        <label for="avatar-upload-input"
               class="absolute inset-0 flex items-center justify-center bg-slate-900/40 rounded-2xl opacity-0 hover:opacity-100 transition-opacity cursor-pointer">
            <i class="fa-solid fa-camera text-white text-xl"></i>
        </label>
    </div>

    <!-- Upload Controls -->
    <div class="flex flex-col gap-2 text-center sm:text-left">
        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">Profile Photo</p>
        <p class="text-xs text-slate-500 dark:text-slate-400">JPG, PNG, GIF or WEBP. Max 2MB.</p>
        <div class="flex items-center gap-2 flex-wrap justify-center sm:justify-start">
            <label for="avatar-upload-input"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-50 dark:bg-primary-950/30 text-primary-600 dark:text-primary-400 text-xs font-semibold hover:bg-primary-100 dark:hover:bg-primary-950/50 cursor-pointer transition">
                <i class="fa-solid fa-upload"></i> Upload Photo
            </label>
            <button type="button"
                    x-show="hasAvatar"
                    x-cloak
                    @click="triggerRemove()"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/20 text-rose-500 dark:text-rose-400 text-xs font-semibold hover:bg-rose-100 transition">
                <i class="fa-solid fa-trash-can"></i> Remove
            </button>
        </div>
    </div>

    <!-- Hidden inputs -->
    <input x-ref="fileInput" id="avatar-upload-input" name="avatar" type="file" accept="image/*" class="hidden" @change="handleFileChange">
    <input type="hidden" name="remove_avatar" :value="removeAvatar ? '1' : '0'">
</div>
