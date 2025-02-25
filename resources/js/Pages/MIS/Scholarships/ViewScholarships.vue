<template>
    <Head title="Scholarships" />
    <AuthenticatedLayout>
        <div class="w-full h-full px-10 py-5 bg-[#F8F8FA] dark:bg-dprimary">
            <div class="w-full mx-auto p-3 rounded-xl text-white">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li class="hover:text-gray-600">
                        <li>
                            <span class="text-blue-400 font-semibold dark:text-gray-300">Scholarships</span>
                        </li>
                    </ul>
                </div>
                
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                        <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>URS Scholarships</h1>
                </div>

                <div class="mx-auto py-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div v-for="scholarship in scholarships" :key="scholarship.id"
                            class="card border bg-white hover:shadow-xl hover:border-gray-400 dark:bg-dcontainer dark:border-gray-600 dark:hover:border-gray-400">
                            <Link :href="`/scholarships/${scholarship.id}`">
                                <div class="card-body p-5 space-y-2">
                                    <div class="badge badge-yellow text-[14px] font-sora bg-yellow-200 dark:bg-blue-400">Ongoing</div>
                                    
                                    <h2 class="card-title text-4xl text-gray-800 font-sora font-semibold dark:text-dtext">{{
                                        scholarship.name }}</h2>
                                    <div class="badge badge-primary text-[12px] badge-outline">
                                        {{ getSponsorName(scholarship.sponsor_id) }}
                                    </div>
                                    <p class="leading-relaxed text-sm text-gray-400">
                                        <span >Created on: {{ new Date(scholarship.created_at).toLocaleDateString() }}</span><br>
                                        <span >Sponsoring Since: {{ new Date(scholarship.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                                    </p>
                                    <p class="text-md text-gray-600 mb-4 text-justify overflow-hidden text-overflow-truncate line-clamp-4 h-24 max-w-full" style=" display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ scholarship.description }}
                                    </p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="isCreating || isEditing"
                class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40 transition-opacity-ease-in duration-300 ">
                <div class="bg-white rounded-lg shadow-xl w-4/12">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            <p class="text-2xl font-bold">{{ isEditing ? 'Edit Scholarship' : 'Create Scholarship' }}</p>
                        </h3>
                        <button type="button" @click="closeModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                    </div>
                    
                    <form @submit.prevent="submitForm" class="space-y-4 p-4">
                        <div>
                            <label for="sponsor" class="label">Sponsor</label>
                            <select v-model="form.sponsor_id" id="sponsor" class="select select-bordered w-full">
                                <option value="" disabled>Select a Sponsor</option>
                                <option v-for="sponsor in sponsors" :key="sponsor.id" :value="sponsor.id">
                                    {{ sponsor.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="name" class="label">Scholarship Name</label>
                            <input v-model="form.name" type="text" id="name" placeholder="Enter Scholarship Name"
                                class="input input-bordered w-full" />
                        </div>
                        <div>
                            <label for="description" class="label">Description</label>
                            <textarea v-model="form.description" id="description" placeholder="Enter Description"
                                class="textarea textarea-bordered w-full"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-full">
                            {{ isEditing ? 'Update Scholarship' : 'Create Scholarship' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Tooltip } from 'primevue';

const props = defineProps({
    sponsors: {
        type: Array,
        required: true
    },
    scholarships: {
        type: Array,
        required: true
    }
});
const directives = {
    Tooltip,
};

const isCreating = ref(false);
const isEditing = ref(false);
const form = ref({
    id: null,
    name: '',
    description: '',
    sponsor_id: ''
});

const getSponsorName = (sponsorId) => {
    const sponsor = props.sponsors.find(s => s.id === sponsorId);
    return sponsor ? sponsor.name : 'Unknown Sponsor';
};

const toggleCreate = () => {
    isCreating.value = !isCreating.value;
    if (isCreating.value) {
        resetForm();
    }
};

const closeModal = () => {
    isCreating.value = false;
    isEditing.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = { 
        id: null, 
        name: '', 
        description: '', 
        sponsor_id: '' 
    };
};

const submitForm = async () => {
    try {
        if (isEditing.value) {
            await useForm(form.value).put(`/scholarships/${form.value.id}`);
        } else {
            await useForm(form.value).post('/scholarships');
        }
        closeModal();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};
</script>

<style scoped>
.p-tooltip-text {
  margin-left: 0px;
  font-size: 13px !important;
}
</style>