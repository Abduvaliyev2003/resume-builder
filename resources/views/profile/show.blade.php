@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div x-data="profileController()" class="flex flex-col lg:flex-row gap-8 items-start">
    
    <!-- Left Sidebar Navigation -->
    <aside class="w-full lg:w-64 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm flex flex-col gap-1 sticky top-20">
        <div class="px-3 py-2 mb-2">
            <h2 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Settings</h2>
        </div>
        
        <button @click="setActiveSection('profile')" 
                :class="activeSection === 'profile' ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/20 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition text-left w-full">
            <i class="fa-solid fa-user-gear w-5 text-center"></i>
            <span>Personal Info</span>
        </button>

        <button @click="setActiveSection('account')" 
                :class="activeSection === 'account' ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/20 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition text-left w-full">
            <i class="fa-solid fa-sliders w-5 text-center"></i>
            <span>Account Settings</span>
        </button>

        <button @click="setActiveSection('password')" 
                :class="activeSection === 'password' ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/20 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition text-left w-full">
            <i class="fa-solid fa-key w-5 text-center"></i>
            <span>Password & Security</span>
        </button>

        <button @click="setActiveSection('notifications')" 
                :class="activeSection === 'notifications' ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/20 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition text-left w-full">
            <i class="fa-solid fa-bell w-5 text-center"></i>
            <span>Notifications</span>
        </button>

        <button @click="setActiveSection('resumes')" 
                :class="activeSection === 'resumes' ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/20 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition text-left w-full">
            <i class="fa-solid fa-file-lines w-5 text-center"></i>
            <span>Resume Settings</span>
        </button>

        <button @click="setActiveSection('sessions')" 
                :class="activeSection === 'sessions' ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/20 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition text-left w-full">
            <i class="fa-solid fa-shield-halved w-5 text-center"></i>
            <span>Active Sessions</span>
        </button>

        <div class="border-t border-slate-250/30 dark:border-slate-800 my-2"></div>

        <button @click="setActiveSection('danger')" 
                :class="activeSection === 'danger' ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/20 dark:text-rose-400' : 'text-rose-500 hover:bg-rose-50/50 dark:hover:bg-rose-950/10'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition text-left w-full">
            <i class="fa-solid fa-trash-can w-5 text-center"></i>
            <span>Danger Zone</span>
        </button>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 w-full max-w-3xl flex flex-col gap-6">
        
        <!-- Alerts & Success Indicators -->
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/30 rounded-2xl p-4 flex items-center gap-3 text-emerald-800 dark:text-emerald-300">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any() && !session('active_section'))
            <div class="bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/30 rounded-2xl p-4 flex flex-col gap-1 text-rose-800 dark:text-rose-300">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    <span class="text-sm font-bold">Please check the forms for errors.</span>
                </div>
                <ul class="list-disc list-inside text-xs pl-8 mt-1 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Section 1: Personal Info -->
        <div x-show="activeSection === 'profile'" x-cloak class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex flex-col gap-6">
            <div>
                <h3 class="text-xl font-bold font-outfit text-slate-900 dark:text-slate-100">Personal Information</h3>
                <p class="text-sm text-slate-500 mt-1">Update your general details and public profile avatar details.</p>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                @csrf
                
                <!-- Avatar Upload Component -->
                <x-profile.avatar-upload :avatarUrl="$profile?->avatar_url" :userName="$user->name" />

                <!-- Grid Details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-input label="Full Name" name="name" :value="old('name', $user->name)" required />
                    <x-input label="Username" name="username" :value="old('username', $profile?->username)" placeholder="johndoe" />
                    
                    <div class="w-full">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-400 dark:text-slate-500 cursor-not-allowed text-sm">
                        <p class="text-[10px] text-slate-400 mt-1">To change email address, please contact support.</p>
                    </div>

                    <x-input label="Phone Number" name="phone" :value="old('phone', $profile?->phone)" placeholder="+1234567890" />
                    
                    <div class="w-full">
                        <label for="date_of_birth" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $profile?->date_of_birth?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150">
                    </div>

                    <x-input label="Country" name="country" :value="old('country', $profile?->country)" placeholder="United States" />
                    <x-input label="City" name="city" :value="old('city', $profile?->city)" placeholder="New York" />
                </div>
                
                <div class="w-full">
                    <label for="bio" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Biography</label>
                    <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm">{{ old('bio', $profile?->bio) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <x-button type="submit">Save Changes</x-button>
                </div>
            </form>
        </div>

        <!-- Section 2: Account Settings -->
        <div x-show="activeSection === 'account'" x-cloak class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex flex-col gap-6">
            <div>
                <h3 class="text-xl font-bold font-outfit text-slate-900 dark:text-slate-100">Account Preferences</h3>
                <p class="text-sm text-slate-500 mt-1">Configure language, theme, and timezone preferences.</p>
            </div>

            <form action="{{ route('profile.settings') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <input type="hidden" name="settings_section" value="account">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="w-full">
                        <label for="language" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Language</label>
                        <select id="language" name="language" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm">
                            <option value="en" {{ old('language', $profile?->getSetting('language', 'en')) === 'en' ? 'selected' : '' }}>English</option>
                            <option value="uz" {{ old('language', $profile?->getSetting('language')) === 'uz' ? 'selected' : '' }}>O'zbekcha</option>
                            <option value="ru" {{ old('language', $profile?->getSetting('language')) === 'ru' ? 'selected' : '' }}>Русский</option>
                        </select>
                    </div>

                    <div class="w-full">
                        <label for="timezone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Timezone</label>
                        <select id="timezone" name="timezone" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm">
                            <option value="UTC" {{ old('timezone', $profile?->getSetting('timezone', 'UTC')) === 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ old('timezone', $profile?->getSetting('timezone')) === 'America/New_York' ? 'selected' : '' }}>Eastern Time (US & Canada)</option>
                            <option value="Europe/London" {{ old('timezone', $profile?->getSetting('timezone')) === 'Europe/London' ? 'selected' : '' }}>London</option>
                            <option value="Asia/Tashkent" {{ old('timezone', $profile?->getSetting('timezone')) === 'Asia/Tashkent' ? 'selected' : '' }}>Tashkent (GMT+5)</option>
                            <option value="Asia/Tokyo" {{ old('timezone', $profile?->getSetting('timezone')) === 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                        </select>
                    </div>
                </div>

                <div class="w-full">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Interface Theme</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="border rounded-xl p-3 flex flex-col items-center gap-2 cursor-pointer transition text-center hover:bg-slate-50 dark:hover:bg-slate-800"
                               :class="themeVal === 'light' ? 'border-primary-500 bg-primary-50/50 dark:bg-primary-950/10' : 'border-slate-200 dark:border-slate-800'">
                            <input type="radio" name="theme" value="light" class="hidden" x-model="themeVal" @change="setTheme('light')">
                            <i class="fa-solid fa-sun text-lg text-amber-500"></i>
                            <span class="text-xs font-semibold">Light</span>
                        </label>
                        
                        <label class="border rounded-xl p-3 flex flex-col items-center gap-2 cursor-pointer transition text-center hover:bg-slate-50 dark:hover:bg-slate-800"
                               :class="themeVal === 'dark' ? 'border-primary-500 bg-primary-50/50 dark:bg-primary-950/10' : 'border-slate-200 dark:border-slate-800'">
                            <input type="radio" name="theme" value="dark" class="hidden" x-model="themeVal" @change="setTheme('dark')">
                            <i class="fa-solid fa-moon text-lg text-indigo-400"></i>
                            <span class="text-xs font-semibold">Dark</span>
                        </label>

                        <label class="border rounded-xl p-3 flex flex-col items-center gap-2 cursor-pointer transition text-center hover:bg-slate-50 dark:hover:bg-slate-800"
                               :class="themeVal === 'system' ? 'border-primary-500 bg-primary-50/50 dark:bg-primary-950/10' : 'border-slate-200 dark:border-slate-800'">
                            <input type="radio" name="theme" value="system" class="hidden" x-model="themeVal" @change="setTheme('system')">
                            <i class="fa-solid fa-desktop text-lg text-slate-400"></i>
                            <span class="text-xs font-semibold">System</span>
                        </label>
                    </div>
                </div>

                <!-- Hidden notification options values to preserve them when saving account settings -->
                <input type="hidden" name="notify_email" value="{{ $profile?->getSetting('notifications.email', true) ? '1' : '0' }}">
                <input type="hidden" name="notify_resume_updates" value="{{ $profile?->getSetting('notifications.resume_updates', false) ? '1' : '0' }}">
                <input type="hidden" name="notify_security" value="{{ $profile?->getSetting('notifications.security', true) ? '1' : '0' }}">
                <input type="hidden" name="notify_marketing" value="{{ $profile?->getSetting('notifications.marketing', false) ? '1' : '0' }}">
                <input type="hidden" name="resume_visibility" value="{{ $profile?->getSetting('resume_visibility', 'private') }}">

                <div class="flex justify-end">
                    <x-button type="submit">Save Preferences</x-button>
                </div>
            </form>
        </div>

        <!-- Section 3: Password Settings -->
        <div x-show="activeSection === 'password'" x-cloak class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex flex-col gap-6">
            <div>
                <h3 class="text-xl font-bold font-outfit text-slate-900 dark:text-slate-100">Update Password</h3>
                <p class="text-sm text-slate-500 mt-1">Change your secret authentication password.</p>
            </div>

            @if($errors->any() && session('active_section') === 'password')
                <div class="bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/30 rounded-2xl p-4 flex flex-col gap-1 text-rose-800 dark:text-rose-300">
                    <ul class="list-disc list-inside text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.password') }}" method="POST" class="flex flex-col gap-5" x-data="passwordFormController()">
                @csrf

                <!-- Current Password -->
                <div class="w-full">
                    <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Current Password</label>
                    <div class="relative">
                        <input :type="showCurrent ? 'text' : 'password'" id="current_password" name="current_password" required class="w-full px-4 py-2.5 pr-10 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm">
                        <button type="button" @click="showCurrent = !showCurrent" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <i class="fa-solid" :class="showCurrent ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div class="w-full">
                    <label for="new_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">New Password</label>
                    <div class="relative">
                        <input :type="showNew ? 'text' : 'password'" id="new_password" name="new_password" required x-model="newPassword" class="w-full px-4 py-2.5 pr-10 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm">
                        <button type="button" @click="showNew = !showNew" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <i class="fa-solid" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    
                    <!-- Password Strength Bar Component -->
                    <x-profile.password-strength model="newPassword" />
                </div>

                <!-- Confirm Password -->
                <div class="w-full">
                    <label for="new_password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Confirm New Password</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" id="new_password_confirmation" name="new_password_confirmation" required class="w-full px-4 py-2.5 pr-10 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-end">
                    <x-button type="submit">Update Password</x-button>
                </div>
            </form>
        </div>

        <!-- Section 4: Notifications Settings -->
        <div x-show="activeSection === 'notifications'" x-cloak class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex flex-col gap-6">
            <div>
                <h3 class="text-xl font-bold font-outfit text-slate-900 dark:text-slate-100">Notification Channels</h3>
                <p class="text-sm text-slate-500 mt-1">Configure email alerts and newsletter updates.</p>
            </div>

            <form action="{{ route('profile.settings') }}" method="POST" class="flex flex-col gap-2">
                @csrf
                <input type="hidden" name="settings_section" value="notifications">
                
                <!-- Account Settings variables to preserve them when saving notifications -->
                <input type="hidden" name="language" value="{{ $profile?->getSetting('language', 'en') }}">
                <input type="hidden" name="timezone" value="{{ $profile?->getSetting('timezone', 'UTC') }}">
                <input type="hidden" name="theme" value="{{ $profile?->getSetting('theme', 'system') }}">
                <input type="hidden" name="resume_visibility" value="{{ $profile?->getSetting('resume_visibility', 'private') }}">

                <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    <x-profile.toggle-switch name="notify_email" label="Email Notifications" description="Receive product updates and dashboard alerts via email." :checked="$profile?->getSetting('notifications.email', true)" />
                    <x-profile.toggle-switch name="notify_resume_updates" label="Resume Update Alerts" description="Get notified when AI resume scoring reports are compiled." :checked="$profile?->getSetting('notifications.resume_updates', false)" />
                    <x-profile.toggle-switch name="notify_security" label="Security Alerts" description="Crucial alerts regarding login activities and password changes." :checked="$profile?->getSetting('notifications.security', true)" />
                    <x-profile.toggle-switch name="notify_marketing" label="Marketing & Promos" description="Emails about premium tools, features, and offers." :checked="$profile?->getSetting('notifications.marketing', false)" />
                </div>

                <div class="flex justify-end mt-4">
                    <x-button type="submit">Save Changes</x-button>
                </div>
            </form>
        </div>

        <!-- Section 5: Resume Settings -->
        <div x-show="activeSection === 'resumes'" x-cloak class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex flex-col gap-6">
            <div>
                <h3 class="text-xl font-bold font-outfit text-slate-900 dark:text-slate-100">Resume Settings</h3>
                <p class="text-sm text-slate-500 mt-1">Configure default visibility policies and manage resources.</p>
            </div>

            <!-- Stats Card -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-250/20 dark:border-slate-800">
                    <span class="text-xs text-slate-400 uppercase tracking-wider font-bold block">Resumes Count</span>
                    <span class="text-2xl font-black font-outfit mt-1 block">{{ $resumes->count() }}</span>
                </div>
                <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-250/20 dark:border-slate-800">
                    <span class="text-xs text-slate-400 uppercase tracking-wider font-bold block">Last Updated</span>
                    <span class="text-sm font-semibold mt-2 block text-slate-800 dark:text-slate-200">
                        {{ $resumes->first() ? $resumes->first()->updated_at->diffForHumans() : 'No resumes created yet' }}
                    </span>
                </div>
            </div>

            <!-- Default/First Resume Details -->
            @if($resumes->first())
                <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-slate-50/50 dark:bg-slate-950/20">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-400 px-2 py-0.5 rounded-full inline-block">Default Resume</span>
                        <h4 class="font-bold text-slate-900 dark:text-slate-100 mt-1">{{ $resumes->first()->title }}</h4>
                        <p class="text-xs text-slate-500">ATS Score: {{ $resumes->first()->score }}%</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="/resumes/{{ $resumes->first()->id }}/preview" class="px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-250 dark:border-slate-850 hover:bg-slate-50 rounded-lg text-xs font-bold transition">View</a>
                        <a href="/resumes/{{ $resumes->first()->id }}/builder" class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-xs font-bold transition">Edit</a>
                    </div>
                </div>
            @else
                <div class="text-center py-6 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                    <p class="text-sm text-slate-400">You don't have any resumes.</p>
                    <a href="/templates" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold transition mt-2">
                        <i class="fa-solid fa-plus"></i> Create New Resume
                    </a>
                </div>
            @endif

            <form action="{{ route('profile.settings') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="settings_section" value="resumes">

                <!-- Account & Notification Settings to preserve -->
                <input type="hidden" name="language" value="{{ $profile?->getSetting('language', 'en') }}">
                <input type="hidden" name="timezone" value="{{ $profile?->getSetting('timezone', 'UTC') }}">
                <input type="hidden" name="theme" value="{{ $profile?->getSetting('theme', 'system') }}">
                <input type="hidden" name="notify_email" value="{{ $profile?->getSetting('notifications.email', true) ? '1' : '0' }}">
                <input type="hidden" name="notify_resume_updates" value="{{ $profile?->getSetting('notifications.resume_updates', false) ? '1' : '0' }}">
                <input type="hidden" name="notify_security" value="{{ $profile?->getSetting('notifications.security', true) ? '1' : '0' }}">
                <input type="hidden" name="notify_marketing" value="{{ $profile?->getSetting('notifications.marketing', false) ? '1' : '0' }}">

                <div class="w-full">
                    <label for="resume_visibility" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Default Resume Visibility</label>
                    <select id="resume_visibility" name="resume_visibility" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm">
                        <option value="private" {{ old('resume_visibility', $profile?->getSetting('resume_visibility', 'private')) === 'private' ? 'selected' : '' }}>Private (Only you can access)</option>
                        <option value="link_only" {{ old('resume_visibility', $profile?->getSetting('resume_visibility')) === 'link_only' ? 'selected' : '' }}>Shared link only</option>
                        <option value="public" {{ old('resume_visibility', $profile?->getSetting('resume_visibility')) === 'public' ? 'selected' : '' }}>Public (Search engines indexing allowed)</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <x-button type="submit">Save settings</x-button>
                </div>
            </form>
        </div>

        <!-- Section 6: Security and Active Sessions -->
        <div x-show="activeSection === 'sessions'" x-cloak class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex flex-col gap-6">
            <div>
                <h3 class="text-xl font-bold font-outfit text-slate-900 dark:text-slate-100">Security Details</h3>
                <p class="text-sm text-slate-500 mt-1">Review active authentication sessions and log status.</p>
            </div>

            <!-- Login / Password Info logs -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-250/20 dark:border-slate-800">
                    <span class="text-xs text-slate-400 uppercase tracking-wider font-bold block">Last login time</span>
                    <span class="text-sm font-semibold mt-2 block text-slate-700 dark:text-slate-300">
                        {{ $profile?->last_login_at ? $profile->last_login_at->diffForHumans() : 'This session' }}
                    </span>
                </div>
                <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-250/20 dark:border-slate-800">
                    <span class="text-xs text-slate-400 uppercase tracking-wider font-bold block">Password changed</span>
                    <span class="text-sm font-semibold mt-2 block text-slate-700 dark:text-slate-300">
                        {{ $profile?->password_changed_at ? $profile->password_changed_at->diffForHumans() : 'Never changed' }}
                    </span>
                </div>
            </div>

            <!-- Active sessions list -->
            <div class="flex flex-col gap-4">
                <div class="flex justify-between items-center">
                    <h4 class="font-bold text-sm text-slate-850 dark:text-slate-200">Active Device Sessions</h4>
                    @if(count($activeSessions) > 1)
                        <button type="button" @click="$dispatch('open-modal-logout-devices')" class="text-xs font-bold text-rose-500 hover:text-rose-600 transition">Logout Other Devices</button>
                    @endif
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/80 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
                    @forelse($activeSessions as $sess)
                        <div class="p-4 flex items-center justify-between gap-4 bg-white dark:bg-slate-900">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-950 flex items-center justify-center text-slate-400">
                                    @if(str_contains(strtolower($sess['user_agent']), 'mobile') || str_contains(strtolower($sess['user_agent']), 'phone'))
                                        <i class="fa-solid fa-mobile-button text-lg"></i>
                                    @else
                                        <i class="fa-solid fa-desktop text-lg"></i>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate max-w-[200px]" title="{{ $sess['user_agent'] }}">{{ $sess['user_agent'] }}</span>
                                        @if($sess['is_current'])
                                            <span class="text-[9px] font-bold uppercase bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded-full">This Device</span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-0.5">IP: {{ $sess['ip_address'] }} &bull; Active: {{ $sess['last_activity'] }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-xs text-slate-400">
                            Active session tracking requires database session driver configured.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Section 7: Danger Zone -->
        <div x-show="activeSection === 'danger'" x-cloak class="bg-rose-50/30 dark:bg-rose-950/5 border border-rose-200 dark:border-rose-900/30 rounded-2xl p-6 flex flex-col gap-6">
            <div>
                <h3 class="text-xl font-bold font-outfit text-rose-600 dark:text-rose-400">Danger Zone</h3>
                <p class="text-sm text-slate-500 mt-1">Irreversible administrative actions on your personal profile.</p>
            </div>

            <div class="border border-rose-200/50 dark:border-rose-900/30 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white dark:bg-slate-900">
                <div>
                    <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100">Delete Account Permanently</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Destroy all version histories, compiled PDF resources, and account parameters.</p>
                </div>
                <x-button variant="danger" @click="$dispatch('open-modal-delete-account')">Delete Account</x-button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Confirm logout of other sessions -->
<x-modal id="logout-devices" title="Terminate Other Sessions">
    <form action="{{ route('profile.logout-other-devices') }}" method="POST" class="flex flex-col gap-4">
        @csrf
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Please type your current password to terminate all active authentication login sessions on other devices.
        </p>

        <x-input label="Confirm Password" type="password" name="password" required />

        <div class="flex justify-end gap-3 mt-2">
            <x-button variant="outline" @click="$dispatch('close-modal-logout-devices')">Cancel</x-button>
            <x-button type="submit" variant="danger">Terminate Sessions</x-button>
        </div>
    </form>
</x-modal>

<!-- Modal: Confirm Delete Account -->
<x-modal id="delete-account" title="Permanently Delete Account">
    <form action="{{ route('profile.delete') }}" method="POST" class="flex flex-col gap-4">
        @csrf
        @method('DELETE')
        
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Are you absolutely sure you want to delete your account? This action is permanent and cannot be undone. All your resumes, scores, and activity logs will be wiped instantly.
        </p>

        <p class="text-xs text-slate-500 bg-slate-50 dark:bg-slate-950 p-3 rounded-lg border border-slate-200 dark:border-slate-800">
            Type <strong class="text-rose-500 font-bold">DELETE</strong> below and enter your password to proceed.
        </p>

        <div class="flex flex-col gap-3">
            <x-input label='Type "DELETE"' name="confirm_text" required placeholder="DELETE" />
            <x-input label="Confirm Password" type="password" name="password" required />
        </div>

        <div class="flex justify-end gap-3 mt-2">
            <x-button variant="outline" @click="$dispatch('close-modal-delete-account')">Cancel</x-button>
            <x-button type="submit" variant="danger">Delete Permanently</x-button>
        </div>
    </form>
</x-modal>
@endsection

@section('scripts')
<script>
    requireAuth();

    function profileController() {
        return {
            activeSection: '{{ session('success_section', $section) }}',
            themeVal: '{{ $profile?->getSetting('theme', 'system') }}',

            init() {
                // If there are errors for a specific form, show that section
                @if($errors->has('current_password') || $errors->has('new_password'))
                    this.activeSection = 'password';
                @elseif($errors->has('confirm_text') || $errors->has('password'))
                    this.activeSection = 'danger';
                @endif
            },

            setActiveSection(sec) {
                this.activeSection = sec;
                // Add query parameter to URL for consistency on page refresh
                const url = new URL(window.location);
                url.searchParams.set('section', sec);
                window.history.pushState({}, '', url);
            },

            setTheme(theme) {
                this.themeVal = theme;
                
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('darkMode', 'true');
                } else if (theme === 'light') {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('darkMode', 'false');
                } else {
                    // System theme preference
                    const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    if (systemDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    localStorage.removeItem('darkMode');
                }
            }
        }
    }

    function passwordFormController() {
        return {
            newPassword: '',
            showCurrent: false,
            showNew: false,
            showConfirm: false,

            get passwordStrength() {
                if (!this.newPassword) return 0;
                let strength = 0;
                if (this.newPassword.length >= 8) strength++;
                if (/[A-Z]/.test(this.newPassword)) strength++;
                if (/[a-z]/.test(this.newPassword)) strength++;
                if (/[0-9]/.test(this.newPassword)) strength++;
                if (/[^A-Za-z0-9]/.test(this.newPassword)) strength++;
                return strength;
            }
        }
    }
</script>
@endsection
