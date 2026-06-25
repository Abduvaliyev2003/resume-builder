@extends('layouts.app')

@section('title', 'Select Template')

@section('content')
<div x-data="templateSelector()" class="flex flex-col gap-8">
    
    <!-- Header -->
    <div class="text-center max-w-2xl mx-auto mb-4">
        <h1 class="text-3xl font-extrabold font-outfit text-slate-900 dark:text-slate-100">Choose a Layout Structure</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
            Select one of our professionally curated layout structures. All templates are fully responsive and optimized for ATS parsers and LaTeX renderers.
        </p>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($templates as $template)
            <x-template-card :template="$template" />
        @endforeach
    </div>

    <!-- Create Resume Modal -->
    <x-modal id="create-resume" title="Name Your Resume">
        <form @submit.prevent="createResume" class="flex flex-col gap-4">
            <x-input 
                label="Resume Title" 
                name="title" 
                placeholder="e.g. Senior Software Engineer Resume" 
                required="true"
                model="resumeTitle"
            />
            
            <p class="text-xs text-slate-500">
                You can change this title at any time. We will initialize the workspace using the structure properties of this template.
            </p>

            <div class="mt-4 flex justify-end gap-3">
                <x-button variant="outline" type="button" @click="$dispatch('close-modal-create-resume')">Cancel</x-button>
                <x-button variant="primary" type="submit" ::disabled="loading">
                    <span x-show="!loading">Create & Continue</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-1.5">
                        <i class="fa-solid fa-spinner animate-spin"></i> Initializing...
                    </span>
                </x-button>
            </div>
        </form>
    </x-modal>

</div>
@endsection

@section('scripts')
<script>
    requireAuth();

    function templateSelector() {
        return {
            selectedTemplateId: null,
            resumeTitle: 'My Resume',
            loading: false,

            init() {
                window.addEventListener('select-template', (e) => {
                    this.selectedTemplateId = e.detail.id;
                    this.resumeTitle = 'My Resume';
                    this.$dispatch('open-modal-create-resume');
                });
            },

            async createResume() {
                if (!this.selectedTemplateId) return;
                
                this.loading = true;
                try {
                    const response = await fetch('/api/resumes', {
                        method: 'POST',
                        headers: getAuthHeaders(),
                        body: JSON.stringify({
                            title: this.resumeTitle,
                            template_id: this.selectedTemplateId,
                            // Send default blank sections so the database creates them
                            sections: [
                                {
                                    section_type: 'contact',
                                    content: { name: '', title: '', email: '', phone: '', phone_country: '+998', address: '', photo: '' },
                                    order_index: 1
                                },
                                {
                                    section_type: 'summary',
                                    content: { text: '' },
                                    order_index: 2
                                },
                                {
                                    section_type: 'skills',
                                    content: { list: [] },
                                    order_index: 3
                                },
                                {
                                    section_type: 'experience',
                                    content: { items: [] },
                                    order_index: 4
                                },
                                {
                                    section_type: 'education',
                                    content: { items: [] },
                                    order_index: 5
                                },
                                {
                                    section_type: 'certifications',
                                    content: { items: [] },
                                    order_index: 6
                                },
                                {
                                    section_type: 'languages',
                                    content: { items: [] },
                                    order_index: 7
                                }
                            ]
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to initialize resume.');
                    }

                    showToast('Resume initialized! Redirecting to builder...', 'success');
                    this.$dispatch('close-modal-create-resume');

                    setTimeout(() => {
                        window.location.href = `/resumes/${data.resume.id}/builder`;
                    }, 500);

                } catch (err) {
                    showToast(err.message, 'error');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endsection
