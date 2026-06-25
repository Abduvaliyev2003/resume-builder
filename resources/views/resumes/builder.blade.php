@extends('layouts.app')

@section('title', 'Resume Builder')

@section('styles')
<style>
    /* Styling specifically for split screen layout */
    .builder-container {
        height: calc(100vh - 4.5rem);
    }
</style>
@endsection

@section('content')
<div x-data="resumeBuilder()" data-resume-builder class="grid grid-cols-1 lg:grid-cols-12 gap-8 builder-container -mt-6">
    
    <!-- LEFT COLUMN: Forms and Inputs (lg:col-span-6) -->
    <div class="lg:col-span-6 flex flex-col h-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
        
        <!-- Header Bar -->
        <div class="p-5 border-b border-slate-200 dark:border-slate-850 flex items-center justify-between gap-4">
            <div class="flex-1 min-w-0">
                <!-- Editable Resume Title -->
                <input 
                    type="text" 
                    x-model="title" 
                    @input="triggerAutoSave()" 
                    class="text-lg font-extrabold font-outfit text-slate-900 dark:text-slate-100 bg-transparent border-b border-transparent hover:border-slate-300 focus:border-primary-500 focus:outline-none w-full pb-0.5 truncate"
                />
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs font-semibold text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid" :class="saveStatus === 'Saved' ? 'fa-cloud-arrow-up text-emerald-500' : 'fa-spinner animate-spin text-primary-500'"></i>
                        <span x-text="saveStatus">Saved</span>
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <!-- Template Selector Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                            <span x-text="getTemplateName()">Template</span>
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute left-0 mt-1.5 w-48 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-705 rounded-xl shadow-lg z-50 py-1.5 text-xs text-slate-700 dark:text-slate-350">
                            @foreach($templates as $tpl)
                                <button @click="switchTemplate(@js($tpl->id), @js($tpl->style)); open = false" 
                                        :class="templateId === '{{ $tpl->id }}' ? 'bg-primary-50 dark:bg-primary-950/20 text-primary-600 font-bold' : ''"
                                        class="w-full text-left px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-750 transition">
                                    {{ $tpl->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs">
                    <span class="font-semibold text-slate-500">Draft:</span>
                    <span x-text="saveStatus" :class="hasUnsavedChanges ? 'text-amber-600' : 'text-emerald-600'" class="font-bold"></span>
                    <span class="text-slate-300">|</span>
                    <span class="font-semibold text-slate-500">Complete:</span>
                    <span x-text="completionPercent + '%'" class="font-bold text-primary-600"></span>
                    <span class="text-slate-300">|</span>
                    <span class="font-semibold text-slate-500">Score:</span>
                    <span x-text="resumeScore + '%'" class="font-bold text-emerald-600"></span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex items-center gap-2">
                <button @click="openAIModal()" class="flex items-center gap-1.5 px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>AI Assistant</span>
                </button>
                <a href="/resumes/{{ $resume->id }}/preview" class="p-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl transition" title="Full Preview">
                    <i class="fa-solid fa-expand"></i>
                </a>
            </div>
        </div>

        <!-- Form Navigation Tabs -->
        <div class="flex border-b border-slate-200 dark:border-slate-850 overflow-x-auto scrollbar-none px-4 bg-slate-50/50 dark:bg-slate-900/50">
            <template x-for="tab in tabs" :key="tab.id">
                <button 
                    @click="activeTab = tab.id"
                    :class="activeTab === tab.id ? 'border-primary-500 text-primary-600 dark:text-primary-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:hover:text-slate-300'"
                    class="whitespace-nowrap py-3.5 px-3 border-b-2 font-medium text-xs transition duration-150 flex items-center gap-1.5"
                >
                    <i :class="tab.icon"></i>
                    <span x-text="tab.name"></span>
                </button>
            </template>
        </div>

        <!-- Tab Contents Wrapper (Scrollable area) -->
        <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 rounded-2xl border border-slate-100 dark:border-slate-850 bg-slate-50/60 dark:bg-slate-900/40 p-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Target Job Role</label>
                    <select x-model="jobRole" @change="applyJobRole()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150">
                        <option value="">Select role</option>
                        <template x-for="role in jobRoles" :key="role.name">
                            <option :value="role.name" x-text="role.name"></option>
                        </template>
                    </select>
                </div>
                <div x-show="selectedRole" x-cloak class="text-xs text-slate-600 dark:text-slate-400">
                    <div class="font-bold text-slate-800 dark:text-slate-200 mb-1">AI Suggestions</div>
                    <p x-text="selectedRole?.summary"></p>
                    <div class="flex flex-wrap gap-1 mt-2">
                        <template x-for="skill in (selectedRole?.skills || [])">
                            <button type="button" @click="addSuggestedSkill(skill)" class="px-2 py-1 rounded-md bg-primary-50 text-primary-700 font-bold" x-text="skill"></button>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- CONTACT DETAILS TAB -->
            <div x-show="activeTab === 'contact'" class="flex flex-col gap-5">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-850 pb-2">Personal Contact Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <x-input label="Full Name" name="c_name" placeholder="John Doe" model="contact.name" @input="triggerAutoSave()" />
                    <x-input label="Professional Title" name="c_title" placeholder="Backend Engineer" model="contact.title" @input="triggerAutoSave()" />
                </div>
                <div class="flex items-center gap-4 p-4 rounded-2xl border border-slate-100 dark:border-slate-850 bg-slate-50/50 dark:bg-slate-900/40">
                    <div class="w-16 h-16 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden flex items-center justify-center text-slate-400 shrink-0">
                        <template x-if="contact.photo">
                            <img :src="contact.photo" alt="Profile photo" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!contact.photo">
                            <i class="fa-solid fa-user text-xl"></i>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <label for="c_photo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Profile Photo</label>
                        <div class="flex flex-wrap items-center gap-2">
                            <input id="c_photo" type="file" accept="image/*" @change="handlePhotoUpload($event)" class="block w-full text-xs text-slate-500 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-700 file:font-bold hover:file:bg-primary-100">
                            <button type="button" x-show="contact.photo" x-cloak @click="removePhoto()" class="text-xs font-bold text-rose-600 hover:text-rose-700">Remove photo</button>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <x-input label="Email Address" name="c_email" type="email" placeholder="john@example.com" model="contact.email" @input="triggerAutoSave()" />
                    <div>
                        <label for="c_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Phone Number</label>
                        <div class="flex gap-2">
                            <select x-model="contact.phone_country" @change="applyPhoneCountry()" class="w-32 px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm">
                                <template x-for="country in phoneCountries" :key="country.code">
                                    <option :value="country.code" x-text="country.label"></option>
                                </template>
                            </select>
                            <input id="c_phone" name="c_phone" type="tel" x-model="contact.phone" @input="triggerAutoSave()" placeholder="+998 90 123 45 67" class="flex-1 min-w-0 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150">
                        </div>
                    </div>
                </div>
                <x-input label="Location/Address" name="c_address" placeholder="San Francisco, CA" model="contact.address" @input="triggerAutoSave()" />
            </div>

            <!-- SUMMARY TAB -->
            <div x-show="activeTab === 'summary'" class="flex flex-col gap-4">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-2">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Professional Summary</h3>
                    <button @click="improveText('summary.text')" class="text-xs font-semibold text-purple-600 hover:text-purple-700 flex items-center gap-1">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> AI Improve
                    </button>
                </div>
                <x-textarea label="About Me" name="s_text" placeholder="Write a brief summary of your achievements and expertise..." model="summary.text" rows="8" @input="triggerAutoSave()" />
            </div>

            <!-- SKILLS TAB -->
            <div x-show="activeTab === 'skills'" class="flex flex-col gap-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-850 pb-2">Core Skills & Competencies</h3>
                
                <!-- Tag Input Controller -->
                <div x-data="{ newSkill: '' }" class="flex flex-col gap-3">
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            x-model="newSkill" 
                            @keydown.enter.prevent="if(newSkill.trim()) { skills.list.push(newSkill.trim()); newSkill = ''; triggerAutoSave(); }"
                            placeholder="Add skill (e.g. PHP, Kubernetes) and press Enter"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm"
                        />
                        <button type="button" @click="if(newSkill.trim()) { skills.list.push(newSkill.trim()); newSkill = ''; triggerAutoSave(); }" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-sm font-bold">
                            Add
                        </button>
                    </div>

                    <!-- Skills List -->
                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(skill, index) in skills.list" :key="index">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-lg text-xs font-semibold">
                                <span x-text="skill"></span>
                                <button type="button" @click="skills.list.splice(index, 1); triggerAutoSave();" class="text-slate-400 hover:text-rose-500 transition">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </span>
                        </template>
                        <template x-if="skills.list.length === 0">
                            <p class="text-xs text-slate-400 italic">No skills added yet.</p>
                        </template>
                    </div>
                </div>
            </div>

            <!-- EXPERIENCE TAB -->
            <div x-show="activeTab === 'experience'" class="flex flex-col gap-5">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-2">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Work Experience</h3>
                    <button type="button" @click="addJob()" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Job
                    </button>
                </div>

                <div class="flex flex-col gap-6">
                    <template x-for="(job, index) in experience.items" :key="index">
                        <div class="p-5 border border-slate-100 dark:border-slate-850 bg-slate-50/30 dark:bg-slate-900/30 rounded-2xl flex flex-col gap-4 relative">
                            <!-- Remove button -->
                            <button type="button" @click="experience.items.splice(index, 1); triggerAutoSave();" class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>

                            <div class="grid grid-cols-2 gap-4 pr-6">
                                <x-input label="Company Name" name="job_company" placeholder="Google" x-model="job.company" @input="triggerAutoSave()" />
                                <x-input label="Job Role / Position" name="job_role" placeholder="Software Engineer" x-model="job.role" @input="triggerAutoSave()" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-input label="Duration (Dates)" name="job_duration" placeholder="Jan 2024 - Present" x-model="job.duration" @input="triggerAutoSave()" />
                                <div></div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Description / Achievements</label>
                                    <button type="button" @click="improveText('experience.items[' + index + '].description')" class="text-xs text-purple-600 hover:text-purple-700 font-semibold flex items-center gap-0.5">
                                        <i class="fa-solid fa-wand-magic-sparkles"></i> AI Improve
                                    </button>
                                </div>
                                <textarea x-model="job.description" @input="triggerAutoSave()" rows="3" placeholder="Briefly write your day-to-day responsibilities and impact..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150 text-sm"></textarea>
                            </div>
                        </div>
                    </template>
                    <template x-if="experience.items.length === 0">
                        <div class="text-center py-6 text-slate-400 text-xs italic">No experience records added yet. Click 'Add Job' to begin.</div>
                    </template>
                </div>
            </div>

            <!-- EDUCATION TAB -->
            <div x-show="activeTab === 'education'" class="flex flex-col gap-5">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-2">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Education Details</h3>
                    <button type="button" @click="addEducation()" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Education
                    </button>
                </div>

                <div class="flex flex-col gap-6">
                    <template x-for="(edu, index) in education.items" :key="index">
                        <div class="p-5 border border-slate-100 dark:border-slate-850 bg-slate-50/30 dark:bg-slate-900/30 rounded-2xl flex flex-col gap-4 relative">
                            <!-- Remove button -->
                            <button type="button" @click="education.items.splice(index, 1); triggerAutoSave();" class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>

                            <div class="grid grid-cols-2 gap-4 pr-6">
                                <x-input label="School / University" name="edu_school" placeholder="Stanford University" x-model="edu.school" @input="triggerAutoSave()" />
                                <x-input label="Degree / Program" name="edu_degree" placeholder="B.S. Computer Science" x-model="edu.degree" @input="triggerAutoSave()" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <x-input label="Graduation Year" name="edu_year" placeholder="2024" x-model="edu.year" @input="triggerAutoSave()" />
                                <div></div>
                            </div>
                        </div>
                    </template>
                    <template x-if="education.items.length === 0">
                        <div class="text-center py-6 text-slate-400 text-xs italic">No education records added yet.</div>
                    </template>
                </div>
            </div>

            <!-- CERTIFICATIONS TAB -->
            <div x-show="activeTab === 'certifications'" class="flex flex-col gap-5">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-2">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Certifications</h3>
                    <button type="button" @click="addCertificate()" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Certificate
                    </button>
                </div>

                <div class="flex flex-col gap-6">
                    <template x-for="(cert, index) in certifications.items" :key="index">
                        <div class="p-5 border border-slate-100 dark:border-slate-850 bg-slate-50/30 dark:bg-slate-900/30 rounded-2xl flex flex-col gap-4 relative">
                            <!-- Remove button -->
                            <button type="button" @click="certifications.items.splice(index, 1); triggerAutoSave();" class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>

                            <div class="grid grid-cols-2 gap-4 pr-6">
                                <x-input label="Certificate Name" name="cert_name" placeholder="AWS Certified Developer" x-model="cert.name" @input="triggerAutoSave()" />
                                <x-input label="Organization" name="cert_org" placeholder="Amazon Web Services" x-model="cert.organization" @input="triggerAutoSave()" />
                            </div>
                            <div class="grid grid-cols-2 gap-4 pr-6">
                                <x-input label="Issue Date" name="cert_issue_date" type="month" x-model="cert.issue_date" @input="triggerAutoSave()" />
                                <x-input label="Credential ID" name="cert_credential_id" placeholder="Optional" x-model="cert.credential_id" @input="triggerAutoSave()" />
                            </div>
                        </div>
                    </template>
                    <template x-if="certifications.items.length === 0">
                        <div class="text-center py-6 text-slate-400 text-xs italic">No certificate records added yet.</div>
                    </template>
                </div>
            </div>

            <!-- LANGUAGES TAB -->
            <div x-show="activeTab === 'languages'" class="flex flex-col gap-5">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-850 pb-2">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Languages</h3>
                    <button type="button" @click="addLanguage()" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Language
                    </button>
                </div>

                <div class="flex flex-col gap-4">
                    <template x-for="(lang, index) in languages.items" :key="index">
                        <div class="p-5 border border-slate-100 dark:border-slate-850 bg-slate-50/30 dark:bg-slate-900/30 rounded-2xl grid grid-cols-2 gap-4 relative">
                            <button type="button" @click="languages.items.splice(index, 1); triggerAutoSave();" class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                            <x-input label="Language" name="language" placeholder="English" x-model="lang.language" @input="triggerAutoSave()" />
                            <div class="pr-6">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Level</label>
                                <select x-model="lang.level" @change="triggerAutoSave()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition duration-150">
                                    <option>Native</option>
                                    <option>Fluent</option>
                                    <option>Advanced</option>
                                    <option>Intermediate</option>
                                    <option>Beginner</option>
                                </select>
                            </div>
                        </div>
                    </template>
                    <template x-if="languages.items.length === 0">
                        <div class="text-center py-6 text-slate-400 text-xs italic">No languages added yet.</div>
                    </template>
                </div>
            </div>

        </div>
    </div>

    <!-- RIGHT COLUMN: Live Resume Preview (lg:col-span-6) -->
    <div class="lg:col-span-6 flex flex-col h-full bg-slate-200/50 dark:bg-slate-950 border border-slate-350 dark:border-slate-850/80 rounded-3xl overflow-hidden shadow-inner p-6 relative">
        <div class="absolute top-3 right-5 z-20 text-[10px] uppercase font-extrabold tracking-wider bg-slate-800 text-white px-2 py-0.5 rounded-md shadow-sm">
            Live Preview (Scrollable)
        </div>

        <!-- Renderable Resume Box -->
        <div class="w-full flex-1 overflow-y-auto bg-white text-slate-900 shadow-xl rounded-2xl border border-slate-300/40 p-8 flex flex-col gap-6 text-[13px] leading-relaxed select-text"
             :style="getPreviewFontStyle()">
            
            <!-- LAYOUTS STYLING ENGINE -->
            <!-- 1. CIRCULAR -->
            <template x-if="selectedStyle === 'circular'">
                <div class="flex h-full gap-6">
                    <!-- Left circular header / Accent Column -->
                    <div class="w-1/3 border-r border-slate-100 pr-4 flex flex-col gap-4">
                        <div class="pb-4 border-b border-slate-100">
                            <!-- Circular styled curved block -->
                            <div class="w-14 h-14 rounded-full bg-blue-900 text-white flex items-center justify-center font-extrabold text-lg mb-3 overflow-hidden">
                                <template x-if="contact.photo">
                                    <img :src="contact.photo" alt="Profile photo" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!contact.photo">
                                    <span x-text="contact.name ? contact.name.charAt(0) : 'J'"></span>
                                </template>
                            </div>
                            <h2 class="font-extrabold text-blue-950 text-base" x-text="contact.name || 'Your Name'"></h2>
                            <p class="text-xs text-blue-700 font-semibold mt-0.5" x-text="contact.title || 'Job Title'"></p>
                        </div>
                        <div class="flex flex-col gap-2 text-[11px] text-slate-600">
                            <p><i class="fa-regular fa-envelope mr-1.5 text-blue-900"></i><span x-text="contact.email || 'email@example.com'"></span></p>
                            <p><i class="fa-solid fa-phone mr-1.5 text-blue-900"></i><span x-text="contact.phone || 'Phone Number'"></span></p>
                            <p><i class="fa-solid fa-location-dot mr-1.5 text-blue-900"></i><span x-text="contact.address || 'Location'"></span></p>
                        </div>
                        
                        <!-- Skills (Dot representations) -->
                        <div class="mt-4">
                            <h4 class="font-extrabold text-[12px] text-blue-950 uppercase mb-2">Skills</h4>
                            <div class="flex flex-col gap-1.5">
                                <template x-for="skill in skills.list">
                                    <div class="flex items-center justify-between">
                                        <span x-text="skill" class="text-[11px]"></span>
                                        <div class="flex gap-0.5">
                                            <span class="w-1.5 h-1.5 bg-blue-900 rounded-full"></span>
                                            <span class="w-1.5 h-1.5 bg-blue-900 rounded-full"></span>
                                            <span class="w-1.5 h-1.5 bg-blue-900 rounded-full"></span>
                                            <span class="w-1.5 h-1.5 bg-blue-300 rounded-full"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right details column -->
                    <div class="flex-1 flex flex-col gap-5">
                        <div>
                            <h4 class="font-extrabold text-blue-950 uppercase border-b pb-1 mb-2 text-[12px]">Profile</h4>
                            <p class="text-slate-650" x-text="summary.text || 'Write your professional profile summary...'"></p>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-blue-950 uppercase border-b pb-1 mb-2 text-[12px]">Experience</h4>
                            <div class="flex flex-col gap-4">
                                <template x-for="job in experience.items">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <strong class="text-blue-950 text-xs" x-text="job.role"></strong>
                                            <span class="text-[10px] text-slate-500" x-text="job.duration"></span>
                                        </div>
                                        <p class="text-[11px] font-semibold text-blue-800" x-text="job.company"></p>
                                        <p class="text-slate-650 text-[11px] mt-1" x-text="job.description"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-blue-950 uppercase border-b pb-1 mb-2 text-[12px]">Education</h4>
                            <div class="flex flex-col gap-3">
                                <template x-for="edu in education.items">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <strong class="text-blue-950 text-[11px]" x-text="edu.degree"></strong>
                                            <span class="text-[10px] text-slate-500" x-text="edu.year"></span>
                                        </div>
                                        <p class="text-[11px] text-blue-800" x-text="edu.school"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-blue-950 uppercase border-b pb-1 mb-2 text-[12px]">Certifications</h4>
                            <div class="flex flex-col gap-3">
                                <template x-for="certificate in certifications.items">
                                    <div>
                                        <strong class="text-blue-950 text-[11px]" x-text="certificate.name"></strong>
                                        <p class="text-[10px] font-semibold text-blue-800" x-text="certificate.organization"></p>
                                        <p class="text-slate-650 text-[11px] mt-1" x-text="certificate.issue_date"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- 2. PROFESSIONAL -->
            <template x-if="selectedStyle === 'professional'">
                <div class="flex flex-col gap-5">
                    <!-- Dark Slate Header -->
                    <div class="bg-slate-900 text-white p-6 rounded-xl flex justify-between items-center gap-4">
                        <div>
                            <h2 class="text-xl font-extrabold" x-text="contact.name || 'Your Name'"></h2>
                            <p class="text-slate-300 font-semibold" x-text="contact.title || 'Job Title'"></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-right text-[11px] text-slate-300 flex flex-col gap-0.5">
                                <p><i class="fa-regular fa-envelope mr-1.5"></i><span x-text="contact.email"></span></p>
                                <p><i class="fa-solid fa-phone mr-1.5"></i><span x-text="contact.phone"></span></p>
                                <p><i class="fa-solid fa-location-dot mr-1.5"></i><span x-text="contact.address"></span></p>
                            </div>
                            <template x-if="contact.photo">
                                <img :src="contact.photo" alt="Profile photo" class="w-14 h-14 rounded-xl object-cover border border-white/20">
                            </template>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div>
                        <h4 class="font-bold text-slate-900 border-b pb-1.5 mb-2 uppercase text-xs">Profile Summary</h4>
                        <p class="text-slate-600" x-text="summary.text"></p>
                    </div>

                    <!-- Experience / Education side-by-side -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-bold text-slate-900 border-b pb-1.5 mb-2 uppercase text-xs">Experience</h4>
                            <div class="flex flex-col gap-4">
                                <template x-for="job in experience.items">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <strong class="text-slate-900 text-[12px]" x-text="job.role"></strong>
                                            <span class="text-[9px] text-slate-400" x-text="job.duration"></span>
                                        </div>
                                        <p class="text-[10px] text-slate-500" x-text="job.company"></p>
                                        <p class="text-slate-600 text-[11px] mt-1" x-text="job.description"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="flex flex-col gap-5">
                            <div>
                                <h4 class="font-bold text-slate-900 border-b pb-1.5 mb-2 uppercase text-xs">Education</h4>
                                <div class="flex flex-col gap-3">
                                    <template x-for="edu in education.items">
                                        <div>
                                            <div class="flex justify-between">
                                                <strong class="text-slate-900 text-[11px]" x-text="edu.degree"></strong>
                                                <span class="text-[9px] text-slate-400" x-text="edu.year"></span>
                                            </div>
                                            <p class="text-[10px] text-slate-500" x-text="edu.school"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-bold text-slate-900 border-b pb-1.5 mb-2 uppercase text-xs">Skills</h4>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="skill in skills.list">
                                        <span class="px-2 py-1 bg-slate-100 text-slate-700 font-bold rounded-md text-[10px]" x-text="skill"></span>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-bold text-slate-900 border-b pb-1.5 mb-2 uppercase text-xs">Certifications</h4>
                                <div class="flex flex-col gap-3">
                                    <template x-for="certificate in certifications.items">
                                        <div>
                                            <strong class="text-slate-900 text-[11px]" x-text="certificate.name"></strong>
                                            <p class="text-[10px] text-slate-500" x-text="certificate.organization"></p>
                                            <p class="text-slate-600 text-[11px] mt-1" x-text="certificate.issue_date"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- 3. VERTICAL -->
            <template x-if="selectedStyle === 'vertical'">
                <div class="flex gap-6 h-full">
                    <!-- Left stripe band -->
                    <div class="w-2.5 h-full rounded-full bg-red-850 flex-shrink-0"></div>
                    
                    <div class="flex-1 flex flex-col gap-5">
                        <div class="pb-4 border-b flex justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-black text-slate-900" x-text="contact.name || 'Your Name'"></h2>
                                <p class="text-red-700 font-bold text-sm tracking-wide" x-text="contact.title || 'Job Title'"></p>
                            </div>
                            <template x-if="contact.photo">
                                <img :src="contact.photo" alt="Profile photo" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                            </template>
                            
                            <div class="flex flex-wrap gap-4 mt-3 text-[11px] text-slate-500">
                                <span><i class="fa-regular fa-envelope mr-1.5 text-red-700"></i><span x-text="contact.email"></span></span>
                                <span><i class="fa-solid fa-phone mr-1.5 text-red-700"></i><span x-text="contact.phone"></span></span>
                                <span><i class="fa-solid fa-location-dot mr-1.5 text-red-700"></i><span x-text="contact.address"></span></span>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider text-red-800 mb-2">Professional Summary</h4>
                            <p class="text-slate-650" x-text="summary.text"></p>
                        </div>

                        <div>
                            <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider text-red-800 mb-3">Employment Details</h4>
                            <div class="flex flex-col gap-4">
                                <template x-for="job in experience.items">
                                    <div class="pl-3 border-l-2 border-slate-200">
                                        <div class="flex justify-between items-start">
                                            <strong class="text-slate-900 text-xs" x-text="job.role"></strong>
                                            <span class="text-[10px] text-slate-400" x-text="job.duration"></span>
                                        </div>
                                        <p class="text-[11px] text-red-700 font-semibold" x-text="job.company"></p>
                                        <p class="text-slate-600 text-[11px] mt-1" x-text="job.description"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider text-red-800 mb-2">Education</h4>
                                <div class="flex flex-col gap-3">
                                    <template x-for="edu in education.items">
                                        <div class="pl-3 border-l-2 border-slate-200">
                                            <strong class="text-slate-900 text-[11px]" x-text="edu.degree"></strong>
                                            <p class="text-[11px] text-red-700 font-semibold" x-text="edu.school"></p>
                                            <p class="text-[10px] text-slate-400" x-text="edu.year"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 uppercase text-xs tracking-wider text-red-800 mb-2">Certifications</h4>
                                <div class="flex flex-col gap-3">
                                    <template x-for="certificate in certifications.items">
                                        <div class="pl-3 border-l-2 border-slate-200">
                                            <strong class="text-slate-900 text-[11px]" x-text="certificate.name"></strong>
                                            <p class="text-[10px] text-red-700 font-semibold" x-text="certificate.organization"></p>
                                            <p class="text-slate-600 text-[11px] mt-1" x-text="certificate.issue_date"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- FALLBACK/OTHER STYLES RENDER AS GENERIC DOUBLE COLUMN -->
            <template x-if="['circular', 'professional', 'vertical'].indexOf(selectedStyle) === -1">
                <div class="flex flex-col gap-6">
                    <div class="border-b pb-4 flex justify-between items-start gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900" x-text="contact.name || 'Your Name'"></h2>
                            <p class="text-primary-600 font-bold" x-text="contact.title || 'Job Title'"></p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="text-right text-[11px] text-slate-500">
                                <p x-text="contact.email"></p>
                                <p x-text="contact.phone"></p>
                                <p x-text="contact.address"></p>
                            </div>
                            <template x-if="contact.photo">
                                <img :src="contact.photo" alt="Profile photo" class="w-14 h-14 rounded-xl object-cover border border-slate-200">
                            </template>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 uppercase border-b pb-1 mb-2">Summary</h4>
                        <p class="text-slate-600" x-text="summary.text"></p>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 uppercase border-b pb-1 mb-3">Experience</h4>
                        <div class="flex flex-col gap-4">
                            <template x-for="job in experience.items">
                                <div>
                                    <div class="flex justify-between font-bold text-slate-900">
                                        <span x-text="job.role"></span>
                                        <span class="text-xs text-slate-400 font-normal" x-text="job.duration"></span>
                                    </div>
                                    <p class="text-xs text-primary-600" x-text="job.company"></p>
                                    <p class="text-slate-600 mt-1" x-text="job.description"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <h4 class="font-bold text-slate-900 uppercase border-b pb-1 mb-3">Education</h4>
                            <div class="flex flex-col gap-3">
                                <template x-for="edu in education.items">
                                    <div>
                                        <div class="flex justify-between font-bold text-slate-900">
                                            <span x-text="edu.degree"></span>
                                            <span class="text-xs text-slate-400 font-normal" x-text="edu.year"></span>
                                        </div>
                                        <p class="text-xs text-primary-600" x-text="edu.school"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 uppercase border-b pb-1 mb-3">Certifications</h4>
                            <div class="flex flex-col gap-3">
                                <template x-for="certificate in certifications.items">
                                    <div>
                                        <strong class="text-slate-900" x-text="certificate.name"></strong>
                                        <p class="text-xs text-primary-600" x-text="certificate.organization"></p>
                                        <p class="text-slate-600 mt-1" x-text="certificate.issue_date"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

        </div>
    </div>

    <!-- AI ASSISTANT MODAL / PANEL -->
    <x-modal id="ai-assistant" title="AI Resume Copilot">
        <div x-data="aiCopilot()" class="flex flex-col gap-5">
            <!-- Tabs -->
            <div class="flex border-b border-slate-200 dark:border-slate-800">
                <button @click="subTab = 'ats'" :class="subTab === 'ats' ? 'border-primary-500 text-primary-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-2.5 text-xs font-semibold border-b-2">ATS Score Card</button>
                <button @click="subTab = 'grammar'" :class="subTab === 'grammar' ? 'border-primary-500 text-primary-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-2.5 text-xs font-semibold border-b-2">Grammar & Typos</button>
                <button @click="subTab = 'job'" :class="subTab === 'job' ? 'border-primary-500 text-primary-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 py-2.5 text-xs font-semibold border-b-2">Job Description Match</button>
            </div>

            <!-- ATS Score Card Tab -->
            <div x-show="subTab === 'ats'" class="flex flex-col gap-4">
                <div class="flex justify-between items-center bg-slate-50 dark:bg-slate-850 p-4 rounded-xl">
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-slate-100">AI ATS Scoring Check</h4>
                        <p class="text-xs text-slate-500">Evaluates content complexity and section representation.</p>
                    </div>
                    <button @click="runATS()" class="px-3.5 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-xs font-bold transition" :disabled="loading">
                        <span x-show="!loading">Analyze</span>
                        <span x-show="loading"><i class="fa-solid fa-spinner animate-spin"></i></span>
                    </button>
                </div>

                <div x-show="atsResult" x-cloak class="flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <div class="text-3xl font-black font-outfit" :class="atsResult.score >= 70 ? 'text-emerald-500' : 'text-amber-500'" x-text="atsResult.score + '%'"></div>
                        <div class="text-xs text-slate-500">Recommended score: 75% for enterprise ATS engines.</div>
                    </div>
                    
                    <!-- Suggestions list -->
                    <div>
                        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 mb-1">ATS Optimization Suggestions:</h5>
                        <ul class="text-xs text-slate-500 flex flex-col gap-1 list-disc pl-4">
                            <template x-for="item in (atsResult.feedback_data?.suggestions || atsResult.feedback_data?.recommendations || [])">
                                <li x-text="item"></li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Grammar Tab -->
            <div x-show="subTab === 'grammar'" class="flex flex-col gap-4">
                <div class="flex justify-between items-center bg-slate-50 dark:bg-slate-850 p-4 rounded-xl">
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-slate-100">AI Grammar Audit</h4>
                        <p class="text-xs text-slate-500 font-semibold">Flags spelling issues and stylistic suggestions.</p>
                    </div>
                    <button @click="runGrammar()" class="px-3.5 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-xs font-bold transition" :disabled="loading">
                        <span x-show="!loading">Audit</span>
                        <span x-show="loading"><i class="fa-solid fa-spinner animate-spin"></i></span>
                    </button>
                </div>

                <div x-show="grammarResult" x-cloak class="flex flex-col gap-3 text-xs text-slate-500">
                    <h5 class="font-bold text-slate-800 dark:text-slate-200">Issues Flagged:</h5>
                    <ul class="flex flex-col gap-2">
                        <template x-for="issue in (grammarResult.feedback_data?.corrections || [])">
                            <li class="bg-rose-50 dark:bg-rose-950/20 p-2.5 rounded-lg border border-rose-100 dark:border-rose-900/35">
                                <p class="font-bold text-rose-700">Original: "<span x-text="issue.original"></span>"</p>
                                <p class="font-semibold text-emerald-600 mt-0.5">Suggestion: "<span x-text="issue.suggestion || issue.replacement"></span>"</p>
                                <p class="text-[10px] text-slate-400 mt-0.5" x-text="issue.reason"></p>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <!-- Job Description Match Tab -->
            <div x-show="subTab === 'job'" class="flex flex-col gap-4">
                <div class="flex flex-col gap-3">
                    <x-input label="Target Job Title" name="job_title_target" placeholder="Senior Backend Developer" model="jobTitle" />
                    <x-textarea label="Paste Job Description" name="job_desc_target" placeholder="Paste the job listing requirements here..." model="jobDesc" rows="4" />
                    
                    <button @click="runJobMatch()" class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition" :disabled="loading || !jobTitle || !jobDesc">
                        <span x-show="!loading">Analyze Alignment</span>
                        <span x-show="loading"><i class="fa-solid fa-spinner animate-spin"></i> Analyzing...</span>
                    </button>
                </div>

                <div x-show="jobResult" x-cloak class="flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <div class="text-3xl font-black font-outfit text-purple-600" x-text="jobResult.match_score + '%'"></div>
                        <div class="text-xs text-slate-500 font-bold">Matching alignment with job description properties.</div>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 mb-1">Keywords Missing:</h5>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="kw in (jobResult.analysis_data?.missing_keywords || [])">
                                <span class="px-2 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-md text-[10px]" x-text="kw"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </x-modal>

</div>
@endsection

@section('scripts')
@php
    $builderSelectedStyle = $resume->template?->style ?? 'professional';
    $builderContact = $sections->get('contact')?->content ?? ['name' => '', 'title' => '', 'email' => '', 'phone' => '', 'phone_country' => '+998', 'address' => '', 'photo' => ''];
    $builderSummary = $sections->get('summary')?->content ?? ['text' => ''];
    $builderSkills = $sections->get('skills')?->content ?? ['list' => []];
    $builderExperience = $sections->get('experience')?->content ?? ['items' => []];
    $builderEducation = $sections->get('education')?->content ?? ['items' => []];
    $builderCertifications = $sections->get('certifications')?->content ?? ['items' => []];
    $builderLanguages = $sections->get('languages')?->content ?? ['items' => []];
@endphp
<script>
    requireAuth();

    function getBuilderState() {
        const builder = document.querySelector('[data-resume-builder]');
        return builder ? Alpine.$data(builder) : null;
    }

    function buildResumePlainText(state) {
        if (!state) return '';

        const parts = [
            state.title,
            state.contact?.name,
            state.contact?.title,
            state.contact?.email,
            state.contact?.phone,
            state.contact?.address,
            state.summary?.text,
            ...(state.skills?.list || []),
            ...(state.experience?.items || []).flatMap((job) => [job.company, job.role, job.duration, job.description]),
            ...(state.education?.items || []).flatMap((edu) => [edu.school, edu.degree, edu.year]),
            ...(state.certifications?.items || []).flatMap((certificate) => [certificate.name, certificate.organization, certificate.issue_date, certificate.credential_id]),
            ...(state.languages?.items || []).flatMap((language) => [language.language, language.level]),
        ];

        return parts.filter(Boolean).join('\n');
    }

    function resumeBuilder() {
        return {
            resumeId: @js($resume->id),
            title: @js($resume->title),
            templateId: @js($resume->template_id),
            selectedStyle: @js($builderSelectedStyle),
            
            // Sections data
            contact: @js($builderContact),
            summary: @js($builderSummary),
            skills: @js($builderSkills),
            experience: @js($builderExperience),
            education: @js($builderEducation),
            certifications: @js($builderCertifications),
            languages: @js($builderLanguages),

            activeTab: 'contact',
            saveStatus: 'Saved',
            saveTimeout: null,
            autoSaveInterval: null,
            hasUnsavedChanges: false,
            jobRole: '',
            selectedRole: null,
            jobRoles: [
                { name: 'Backend Developer', skills: ['PHP', 'Laravel', 'PostgreSQL', 'Redis', 'Docker', 'REST API'], summary: 'Backend Developer with experience building scalable web applications using Laravel and PostgreSQL.', experience: 'Built REST APIs, optimized database queries, and integrated Redis caching for high-traffic services.' },
                { name: 'Frontend Developer', skills: ['JavaScript', 'Vue', 'React', 'Tailwind CSS', 'API Integration'], summary: 'Frontend Developer focused on responsive interfaces, reusable components, and accessible user experiences.', experience: 'Developed interactive dashboards and integrated frontend workflows with REST APIs.' },
                { name: 'Full Stack Developer', skills: ['Laravel', 'Vue', 'PostgreSQL', 'Docker', 'CI/CD'], summary: 'Full Stack Developer experienced in delivering complete web products from database design to polished UI.', experience: 'Delivered end-to-end features across backend APIs, database schemas, and frontend components.' },
                { name: 'Mobile Developer', skills: ['Flutter', 'React Native', 'Firebase', 'REST API', 'App Store'], summary: 'Mobile Developer building performant cross-platform apps with clean architecture and API integrations.', experience: 'Implemented mobile authentication, offline storage, and push notification workflows.' },
                { name: 'DevOps Engineer', skills: ['Docker', 'Kubernetes', 'CI/CD', 'AWS', 'Linux', 'Monitoring'], summary: 'DevOps Engineer experienced in deployment automation, cloud infrastructure, and reliable production systems.', experience: 'Built CI/CD pipelines and improved deployment reliability with containerized infrastructure.' },
                { name: 'QA Engineer', skills: ['Manual Testing', 'Automation', 'Selenium', 'API Testing', 'Bug Tracking'], summary: 'QA Engineer focused on test planning, automation, and improving release quality.', experience: 'Created regression test suites and validated API behavior across release cycles.' },
                { name: 'Data Analyst', skills: ['SQL', 'Excel', 'Power BI', 'Python', 'Data Visualization'], summary: 'Data Analyst turning business data into actionable reports and clear visual insights.', experience: 'Built dashboards and analyzed operational datasets to support business decisions.' },
                { name: 'Data Scientist', skills: ['Python', 'Machine Learning', 'Pandas', 'SQL', 'Statistics'], summary: 'Data Scientist experienced in modeling, experimentation, and insight generation from complex datasets.', experience: 'Built predictive models and evaluated performance using statistical validation.' },
                { name: 'Product Manager', skills: ['Roadmap', 'User Research', 'Agile', 'Analytics', 'Stakeholder Management'], summary: 'Product Manager aligning user needs, business goals, and engineering execution.', experience: 'Led product discovery, prioritized roadmap items, and coordinated cross-functional delivery.' },
                { name: 'UI/UX Designer', skills: ['Figma', 'Wireframing', 'User Research', 'Design Systems', 'Prototyping'], summary: 'UI/UX Designer crafting user-centered flows, polished interfaces, and scalable design systems.', experience: 'Designed prototypes and improved usability through research-driven iteration.' },
                { name: 'Graphic Designer', skills: ['Adobe Photoshop', 'Illustrator', 'Branding', 'Typography', 'Layout'], summary: 'Graphic Designer creating visual identities, marketing assets, and consistent brand systems.', experience: 'Produced campaign assets and refined brand visuals across digital channels.' },
                { name: 'Cybersecurity Specialist', skills: ['Network Security', 'SIEM', 'Vulnerability Assessment', 'Incident Response'], summary: 'Cybersecurity Specialist protecting systems through monitoring, assessment, and incident response.', experience: 'Performed vulnerability assessments and improved security monitoring workflows.' },
                { name: 'System Administrator', skills: ['Linux', 'Windows Server', 'Networking', 'Backups', 'Monitoring'], summary: 'System Administrator maintaining secure, stable, and well-monitored IT infrastructure.', experience: 'Managed servers, backups, user access, and operational monitoring.' },
                { name: 'Business Analyst', skills: ['Requirements', 'Process Mapping', 'SQL', 'Stakeholder Interviews', 'Documentation'], summary: 'Business Analyst translating business needs into clear requirements and actionable improvements.', experience: 'Mapped workflows, documented requirements, and supported delivery teams with analysis.' },
            ],
            phoneCountries: [
                { code: '+998', label: 'UZ +998' },
                { code: '+1', label: 'US +1' },
                { code: '+44', label: 'UK +44' },
                { code: '+49', label: 'DE +49' },
                { code: '+33', label: 'FR +33' },
                { code: '+7', label: 'RU/KZ +7' },
                { code: '+82', label: 'KR +82' },
                { code: '+86', label: 'CN +86' },
                { code: '+91', label: 'IN +91' },
                { code: '+971', label: 'AE +971' },
                { code: '+966', label: 'SA +966' },
                { code: '+90', label: 'TR +90' },
            ],
            
            tabs: [
                { id: 'contact', name: 'Contact', icon: 'fa-regular fa-address-card' },
                { id: 'summary', name: 'Summary', icon: 'fa-regular fa-file-text' },
                { id: 'skills', name: 'Skills', icon: 'fa-solid fa-code' },
                { id: 'experience', name: 'Experience', icon: 'fa-solid fa-briefcase' },
                { id: 'education', name: 'Education', icon: 'fa-solid fa-graduation-cap' },
                { id: 'certifications', name: 'Certificates', icon: 'fa-solid fa-certificate' },
                { id: 'languages', name: 'Languages', icon: 'fa-solid fa-language' }
            ],

            init() {
                // Ensure arrays are initialized
                if (!this.skills.list) this.skills.list = [];
                if (!this.experience.items) this.experience.items = [];
                if (!this.education.items) this.education.items = [];
                if (!this.certifications.items) this.certifications.items = [];
                if (!this.languages.items) this.languages.items = [];
                if (typeof this.contact.photo === 'undefined') this.contact.photo = '';
                if (!this.contact.phone_country) this.contact.phone_country = this.detectPhoneCountry(this.contact.phone);
                this.autoSaveInterval = setInterval(() => {
                    if (this.hasUnsavedChanges) this.saveData();
                }, 10000);
                window.addEventListener('beforeunload', (event) => {
                    if (!this.hasUnsavedChanges) return;
                    event.preventDefault();
                    event.returnValue = '';
                });
            },

            detectPhoneCountry(phone) {
                const value = (phone || '').trim();
                const match = this.phoneCountries.find((country) => value.startsWith(country.code));
                return match ? match.code : '+998';
            },

            applyPhoneCountry() {
                const code = this.contact.phone_country || '+998';
                const current = (this.contact.phone || '').trim();
                const withoutCode = current.replace(/^\+\d{1,4}\s*/, '');
                this.contact.phone = `${code}${withoutCode ? ' ' + withoutCode : ' '}`;
                this.triggerAutoSave();
            },

            handlePhotoUpload(event) {
                const file = event.target.files?.[0];

                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    showToast('Please choose an image file.', 'warning');
                    event.target.value = '';
                    return;
                }

                if (file.size > 1024 * 1024) {
                    showToast('Photo must be under 1 MB.', 'warning');
                    event.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = () => {
                    this.contact.photo = reader.result;
                    this.triggerAutoSave();
                };
                reader.readAsDataURL(file);
            },

            removePhoto() {
                this.contact.photo = '';
                const input = document.getElementById('c_photo');
                if (input) input.value = '';
                this.triggerAutoSave();
            },

            getTemplateName() {
                const templates = {
                    circular: 'Circular Layout',
                    professional: 'Professional Layout',
                    vertical: 'Vertical Stripe Layout',
                    horizontal: 'Horizontal Accent Layout',
                    elegant: 'Elegant Crimson Layout',
                    modern: 'Terracotta Modern Layout',
                    casual: 'Casual Gold Layout',
                    chrono: 'Chrono Timeline Layout',
                    luxurious: 'Rose Luxurious Layout'
                };
                return templates[this.selectedStyle] || 'Default Layout';
            },

            getPreviewFontStyle() {
                const fonts = {
                    circular: 'font-family: Inter, sans-serif',
                    professional: 'font-family: Outfit, sans-serif',
                    vertical: 'font-family: Roboto, sans-serif',
                    elegant: 'font-family: Merriweather, serif',
                    modern: 'font-family: Nunito, sans-serif',
                    luxurious: 'font-family: Playfair Display, serif'
                };
                return fonts[this.selectedStyle] || 'font-family: Inter, sans-serif';
            },

            addJob() {
                this.experience.items.push({ company: '', role: '', duration: '', description: '' });
                this.triggerAutoSave();
            },

            addEducation() {
                this.education.items.push({ school: '', degree: '', year: '' });
                this.triggerAutoSave();
            },

            addCertificate() {
                this.certifications.items.push({ name: '', organization: '', issue_date: '', credential_id: '' });
                this.triggerAutoSave();
            },

            addLanguage() {
                this.languages.items.push({ language: '', level: 'Intermediate' });
                this.triggerAutoSave();
            },

            addSuggestedSkill(skill) {
                if (!this.skills.list.includes(skill)) {
                    this.skills.list.push(skill);
                    this.triggerAutoSave();
                }
            },

            applyJobRole() {
                this.selectedRole = this.jobRoles.find((role) => role.name === this.jobRole) || null;
                if (!this.selectedRole) return;
                if (!this.summary.text) this.summary.text = this.selectedRole.summary;
                this.selectedRole.skills.forEach((skill) => {
                    if (!this.skills.list.includes(skill)) this.skills.list.push(skill);
                });
                if (this.experience.items.length === 0) {
                    this.experience.items.push({ company: '', role: this.selectedRole.name, duration: '', start_date: '', end_date: '', is_present: false, description: this.selectedRole.experience });
                }
                this.triggerAutoSave();
            },

            switchTemplate(id, style) {
                this.templateId = id;
                this.selectedStyle = style;
                this.triggerAutoSave();
            },

            triggerAutoSave() {
                this.saveStatus = 'Unsaved';
                this.hasUnsavedChanges = true;
                
                if (this.saveTimeout) clearTimeout(this.saveTimeout);
                
                this.saveTimeout = setTimeout(() => {
                    this.saveData();
                }, 1000);
            },

            async saveData() {
                try {
                    const payload = {
                        title: this.title,
                        template_id: this.templateId,
                        sections: [
                            { section_type: 'contact', content: this.contact, order_index: 1 },
                            { section_type: 'summary', content: this.summary, order_index: 2 },
                            { section_type: 'skills', content: this.skills, order_index: 3 },
                            { section_type: 'experience', content: this.experience, order_index: 4 },
                            { section_type: 'education', content: this.education, order_index: 5 },
                            { section_type: 'certifications', content: this.certifications, order_index: 6 },
                            { section_type: 'languages', content: this.languages, order_index: 7 }
                        ]
                    };

                    const response = await fetch(`/api/resumes/${this.resumeId}`, {
                        method: 'PUT',
                        headers: getAuthHeaders(),
                        body: JSON.stringify(payload)
                    });

                    if (!response.ok) throw new Error('Auto-save failed.');
                    
                    this.saveStatus = 'Saved';
                    this.hasUnsavedChanges = false;
                } catch (e) {
                    this.saveStatus = 'Failed to save';
                }
            },

            get completionPercent() {
                const checks = [
                    this.contact.name,
                    this.contact.email,
                    this.contact.phone,
                    this.summary.text,
                    this.skills.list.length > 0,
                    this.experience.items.length > 0,
                    this.education.items.length > 0,
                    this.certifications.items.length > 0,
                    this.languages.items.length > 0,
                    this.templateId,
                ];
                return Math.round((checks.filter(Boolean).length / checks.length) * 100);
            },

            get resumeScore() {
                return Math.min(100, Math.round(this.completionPercent * 0.75 + Math.min(this.skills.list.length * 3, 15) + Math.min(this.experience.items.length * 5, 10)));
            },

            openAIModal() {
                this.$dispatch('open-modal-ai-assistant');
            },

            async improveText(fieldPath) {
                // Get current value
                let val = '';
                try {
                    val = eval('this.' + fieldPath);
                } catch (e) {}

                if (!val.trim()) {
                    showToast('Please type some text first before optimizing.', 'warning');
                    return;
                }

                showToast('AI is optimizing text...', 'info');

                try {
                    const response = await fetch(`/api/resumes/${this.resumeId}/grammar-check`, {
                        method: 'POST',
                        headers: getAuthHeaders(),
                        body: JSON.stringify({ text: val })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.review && data.review.feedback_data) {
                        const improved = data.review.feedback_data.improved_text || val + " (AI-Optimized)";
                        // Set value
                        eval('this.' + fieldPath + ' = improved');
                        this.triggerAutoSave();
                        showToast('Text optimized with AI!', 'success');
                    } else {
                        throw new Error('AI Service unavailable.');
                    }
                } catch (e) {
                    showToast('AI improvement failed. Using mock enhancement.', 'info');
                    eval('this.' + fieldPath + ' = val + " (Refined, professional, and optimized for key performance metrics)"');
                    this.triggerAutoSave();
                }
            }
        }
    }

    // AI copilot controller
    function aiCopilot() {
        return {
            resumeId: @js($resume->id),
            subTab: 'ats',
            loading: false,
            atsResult: null,
            grammarResult: null,
            jobResult: null,
            jobTitle: '',
            jobDesc: '',

            async runATS() {
                this.loading = true;
                try {
                    const res = await fetch(`/api/resumes/${this.resumeId}/ats-analyze`, {
                        method: 'POST',
                        headers: getAuthHeaders()
                    });
                    const data = await res.json();
                    if (res.ok) {
                        this.atsResult = data.review;
                        showToast('ATS Scan complete!', 'success');
                    } else {
                        throw new Error(data.message || 'ATS check failed.');
                    }
                } catch (e) {
                    showToast(e.message, 'error');
                } finally {
                    this.loading = false;
                }
            },

            async runGrammar() {
                this.loading = true;
                try {
                    const text = buildResumePlainText(getBuilderState());

                    if (!text.trim()) {
                        throw new Error('Please add resume content before running grammar audit.');
                    }

                    const res = await fetch(`/api/resumes/${this.resumeId}/grammar-check`, {
                        method: 'POST',
                        headers: getAuthHeaders(),
                        body: JSON.stringify({ text })
                    });
                    const data = await res.json();
                    if (res.ok) {
                        this.grammarResult = data.review;
                        showToast('Grammar Audit complete!', 'success');
                    } else {
                        throw new Error(data.message || 'Grammar check failed.');
                    }
                } catch (e) {
                    showToast(e.message, 'error');
                } finally {
                    this.loading = false;
                }
            },

            async runJobMatch() {
                this.loading = true;
                try {
                    const res = await fetch(`/api/resumes/${this.resumeId}/job-match`, {
                        method: 'POST',
                        headers: getAuthHeaders(),
                        body: JSON.stringify({
                            job_title: this.jobTitle,
                            job_description: this.jobDesc
                        })
                    });
                    const data = await res.json();
                    if (res.ok) {
                        this.jobResult = data.target;
                        showToast('Job Alignment Check Complete!', 'success');
                    } else {
                        throw new Error(data.message || 'Job match check failed.');
                    }
                } catch (e) {
                    showToast(e.message, 'error');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endsection
