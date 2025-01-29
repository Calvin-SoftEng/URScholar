<template>

    <Head title="Scholarships" />
    <AuthenticatedLayout>
        <div class="w-full h-full px-10 py-5 bg-[#F8F8FA] overflow-auto">
            <div class="w-full mx-auto p-3 rounded-xl text-white">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li>
                            <a>
                                <span class="material-symbols-rounded mr-2" style="color: #0D47A1; font-size: 20px;">
                                    dashboard
                                </span>
                                Home
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="text-blue-400 font-semibold">View Sponsors</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <h1
                        class="text-3xl font-poppins font-extrabold text-[darkblue] text-left underline underline-offset-4">
                        URS Partnered Scholarships</h1>

                    <button class="btn" @click="toggleCreate">
                        <span class="material-symbols-rounded">
                            library_add
                        </span>
                        New Sponsor
                    </button>
                </div>

                <!-- List of Scholarships -->
                <div v-if="!Showcase">
                    <div class="container mx-auto py-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div v-for="sponsor in sponsors" :key="sponsor.id"
                                class="card border bg-white hover:shadow-xl hover:border-gray-400">
                                <div class="card-body p-5 space-y-2">
                                    <!-- logo -->
                                    <div>
                                        <img :src="`/storage/sponsor/logo/${sponsor.logo}`" alt="logo" class="w-16 h-16 rounded-full" />
                                    </div>

                                    <p class="text-xs text-gray-500">Created on: {{ new
                                        Date(sponsor.created_at).toLocaleDateString() }}</p>
                                    <p class="text-xs text-gray-500">Sponsoring Since: {{ new
                                        Date(sponsor.created_at).toLocaleDateString('en-US', {
                                            year: 'numeric', month:
                                        'long', day: 'numeric' }) }}</p>
                                    <h2 class="card-title text-3xl text-gray-800 font-sora font-semibold">{{
                                        sponsor.name }}</h2>
                                    <!-- <div class="badge badge-primary text-[12px] badge-outline">DEPED</div> -->
                                    <p class="text-md text-gray-600 mb-4 text-justify overflow-hidden text-overflow-truncate line-clamp-4 h-24 max-w-full"
                                        style=" display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ sponsor.description }}
                                    </p>
                                    <!-- <div class="card-actions justify-end">
                                        <Link :href="`/scholarships/${scholarship.id}`" class="btn btn-primary btn-sm">
                                        View</Link>
                                    </div>
                                    <div class="card-actions justify-end">
                                        <Link :href="`/scholarships/${scholarship.id}/applicants`" class="btn btn-primary btn-sm">
                                            Applicants</Link>
                                    </div>
                                    <button @click="editScholarship(scholarship)"
                                        class="btn btn-warning btn-sm">Edit</button> -->
                                    <div class="flex justify-end space-x-4">
                                        <Link :href="`/sponsors/${sponsor.id}`">
                                        <div class="text-sm text-gray-500 cursor-pointer">
                                            <span
                                                class="material-symbols-rounded text-blue-900 bg-blue-100 p-3 border rounded-lg">
                                                open_in_browser
                                            </span>
                                        </div>
                                        </Link >
                                        <div class="text-sm text-gray-500">
                                            <span
                                                class="material-symbols-rounded text-blue-900 bg-blue-100 p-3 border rounded-lg">
                                                cancel
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 cursor-pointer"
                                            @click="editScholarship(scholarship)" type="button">
                                            <span
                                                class="material-symbols-rounded text-blue-900 bg-blue-100 p-3 border rounded-lg">
                                                settings
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- creating a sponsor -->
            <div v-if="isCreating || isEditing"
                class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40 transition-opacity-ease-in duration-300 ">
                <div class="bg-white rounded-lg shadow-xl w-4/12">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <span class="text-xl font-semibold text-gray-900 dark:text-white">
                            <h2 class="text-2xl font-bold">{{ isEditing ? 'Edit Sponsor Information' : 'Add New Sponsor'
                                }}</h2>
                        </span>
                        <button type="button" @click="closeModal"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="submitForm" class="space-y-4 p-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor</h3>
                            <input v-model="form.name" type="text" id="name"
                                placeholder="Enter a Partnership or Sponsor"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full" />
                        </div>
                        <!-- file input -->
                        <div class="w-full h-full flex flex-col">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Attach Memorandum of Agreement</h3>
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                @dragover.prevent="handleFileDragOver" @dragleave="handleFileDragLeave"
                                @drop.prevent="handleFileDrop">
                                <div v-if="!form.file" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG, DOCX (MAX. 2MB-4MB)</p>
                                </div>
                                <div v-else class="flex flex-col items-center justify-center">
                                    <img :src="form.filePreview" alt="Uploaded Preview" class="max-h-24 mb-2 rounded-lg" />
                                    <p class="text-sm text-gray-500">{{ form.fileName }}</p>
                                </div>
                                <input id="dropzone-file" type="file" class="hidden" accept=".svg, .png, .jpg, .docx"
                                    @change="(e) => handleFile(e.target.files[0])" />
                            </label>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Background Information</h3>
                            <textarea v-model="form.description" id="description" placeholder="Enter Description"
                                class="textarea textarea-bordered w-full"></textarea>
                        </div>
                        <!-- photo input -->
                        <div class="w-full h-full flex flex-col">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Upload Photo (Optional for
                                Displaying)</h3>
                                <label for="dropzone-img"
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                    :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                    @dragover.prevent="handleImgDragOver" @dragleave="handleImgDragLeave"
                                    @drop.prevent="handleImgDrop">
                                    <div v-if="!form.img" class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG (MAX. 800x400px - 2MB-4MB)</p>
                                    </div>
                                    <div v-else class="flex flex-col items-center justify-center">
                                        <img :src="form.imgPreview" alt="Uploaded Preview" class="max-h-24 mb-2 rounded-lg" />
                                        <p class="text-sm text-gray-500">{{ form.imgName }}</p>
                                    </div>
                                    <input id="dropzone-img" type="file" class="hidden" accept=".svg, .png, .jpg, .jpeg"
                                        @change="(e) => handleImg(e.target.files[0])" />
                                </label>
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
import { ref, onMounted } from 'vue';
import { Head, useForm, Link, } from '@inertiajs/vue3';
import { Tooltip } from 'primevue';
import { set } from 'date-fns';
import { DatePicker } from 'primevue';


defineProps({
    sponsors: Array,
});

const directives = {
    Tooltip,
    DatePicker,
};

const isCreating = ref(false);
const isEditing = ref(false);
const Showcase = ref(false);

const form = ref({
    id: null,
    name: null,
    description: null,
    file: null,
    fileName: null,
    filePreview: null,
    img: null,
    imgName: null,
    imgPreview: null,
});

const scholarships = ref({
    name: null,
    scholarshipType: null,
    school_year: null,
    semester: null,
    application: null,
    deadline: null,
});

const isFileDragging = ref(false);
const isImgDragging = ref(false);

const previewFile = (event) => {
    const file = event.target.files[0];
    handleFile(file);
};

const previewImg = (event) => {
    const img = event.target.files[0];
    handleImg(img);
};

const handleFileDragOver = () => {
    isFileDragging.value = true;
};

const handleImgDragOver = () => {
    isImgDragging.value = true;
};

const handleFileDragLeave = () => {
    isFileDragging.value = false;
};

const handleImgDragLeave = () => {
    isImgDragging.value = false;
};

const handleFileDrop = (event) => {
    isFileDragging.value = false;
    const file = event.dataTransfer.files[0];
    handleFile(file);
};

const handleImgDrop = (event) => {
    isImgDragging.value = false;
    const img = event.dataTransfer.files[0];
    handleImg(img);
};

const handleFile = (file) => {
    
    // if (file.size > 4 * 1024 * 1024) { // 4MB limit
    // alert("File size exceeds the 4MB limit.");
    // return;
    // }
    
    if (file) {
        form.value.file = file;
        form.value.fileName = file.name;
        const reader = new FileReader();
        reader.onload = (e) => {
            form.value.filePreview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// if (file.size > 4 * 1024 * 1024) { // 4MB limit
    // alert("File size exceeds the 4MB limit.");
    // return;
    // }
const handleImg = (img) => {
    if (img) {
        // Generate a unique filename using timestamp
        const timestamp = new Date().getTime();
        const uniqueFileName = `${timestamp}_${img.name}`;
        
        form.value.img = img;
        form.value.imgName = uniqueFileName; // Store the generated unique filename
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            form.value.imgPreview = e.target.result;
        };
        reader.readAsDataURL(img);
    }
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
    isPublishing.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = { id: null, name: '', description: '' };
};

const editScholarship = (scholarship) => {
    isEditing.value = true;
    isCreating.value = false;
    form.value = { ...scholarship };
};

const viewApplicants = (scholarshipId) => {
    Inertia.visit(`/scholarships/${scholarshipId}/applicants`);
};


const submitForm = async () => {
    try {
        if (isEditing.value) {
            await useForm(form.value).put(`/sponsors/${form.value.id}`);
        } else {
            await useForm(form.value).post('/sponsors');
        }

        closeModal();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const activeateForm = async () => {
    try {
        await useForm(scholarships.value).post(route('scholarships.store'));
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