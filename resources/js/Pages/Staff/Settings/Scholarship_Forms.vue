<template>
    <SettingsLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <h1 class="text-2xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                    <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>Scholarship Forms
                </h1>

                <div class="w-full mt-5">
                    <div class="relative overflow-x-auto border border-gray-200 rounded-lg w-full p-5 bg-white">
                        

                        <!-- Family Information -->
                        <div class="mb-5">
                            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Scholarship Criteria</h2>
                            <div class="mt-3 space-y-5">
                                
                                <!-- Marital Selection -->
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-gray-600">Marital Status of Parents:</span>
                                        <div class="flex gap-3 mt-1">
                                            <div class="flex items-center gap-2 border rounded-md px-2 py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">Married</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEdit" class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']" class="text-primary"/>
                                                    </button>
                                                    <button class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'box-archive']" class="text-primary"/>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 border rounded-md px-2 py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">Not Married</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEdit" class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']" class="text-primary"/>
                                                    </button>
                                                    <button class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'box-archive']" class="text-primary"/>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 border rounded-md px-2 py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">Living Together</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEdit" class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']" class="text-primary"/>
                                                    </button>
                                                    <button class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'box-archive']" class="text-primary"/>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 border rounded-md px-2 py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">Separated</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEdit" class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']" class="text-primary"/>
                                                    </button>
                                                    <button class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'box-archive']" class="text-primary"/>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <button @click="toggleAdd" class="text-blue-600 text-sm hover:underline">Add Marital Status</button>
                                </div>

                                <!-- Monthly Family Income Status -->
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-gray-600">Monthly Family Income:</span>
                                        <div class="flex gap-3 mt-1">
                                            <div class="flex items-center gap-2 border rounded-md px-2 py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">10,000 and below</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEdit" class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']" class="text-primary"/>
                                                    </button>
                                                    <button class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'box-archive']" class="text-primary"/>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 border rounded-md px-2 py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">10,001 - 20,000</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEdit" class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']" class="text-primary"/>
                                                    </button>
                                                    <button class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'box-archive']" class="text-primary"/>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 border rounded-md px-2 py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">20,001 - 30,000</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEdit" class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']" class="text-primary"/>
                                                    </button>
                                                    <button class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'box-archive']" class="text-primary"/>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 border rounded-md px-2 py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">30,001 and above</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEdit" class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']" class="text-primary"/>
                                                    </button>
                                                    <button class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'box-archive']" class="text-primary"/>
                                                    </button>
                                                </div>
                                            </div>
                                            

                                        </div>
                                    </div>
                                    <button @click="toggleAdd" class="text-blue-600 text-sm hover:underline">Add Family Income</button>
                                </div>

                                <!-- Family Type of Housing Status -->
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-gray-600">Family Type of Housing:</span>
                                        <div class="flex gap-3 mt-1">
                                            <div class="flex items-center gap-2 border rounded-md px-2 py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">Owned</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEdit" class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']" class="text-primary"/>
                                                    </button>
                                                    <button class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'box-archive']" class="text-primary"/>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 border rounded-md px-2 py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">Settler</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEdit" class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']" class="text-primary"/>
                                                    </button>
                                                    <button class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'box-archive']" class="text-primary"/>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 border rounded-md px-2 py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">Rental</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEdit" class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']" class="text-primary"/>
                                                    </button>
                                                    <button class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'box-archive']" class="text-primary"/>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button @click="toggleAdd" class="text-blue-600 text-sm hover:underline">Add Housing type</button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- creating a sponsor --> 
        <div v-if="isAdding || isEditing"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                        Add mo name nung iaadd mo
                        </h2>
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

                <form @submit.prevent="submitForm" class="p-4 flex flex-col gap-3">
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                        Add What    
                        </h3>
                        <input v-model="form.name" type="text" id="name"
                            placeholder="Enter what"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </SettingsLayout>

</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SettingsLayout from '@/Layouts/Settings_Layout.vue';
import { usePage } from "@inertiajs/vue3";
import { Tooltip } from 'primevue';
import { DatePicker } from 'primevue';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'


defineProps({
    sponsors: Array,
    students: Array,
});

const directives = {
    Tooltip,
    DatePicker,
};

const form = ref({
    file: null,
    fileName: null,
    filePreview: null,
});

const scholarships = ref({
    name: null,
    scholarshipType: null,
    school_year: null,
    semester: null,
    application: null,
    deadline: null,
});

const isAdding = ref(false);
const isEditing = ref(false);

const toggleAdd = () => {
    isAdding.value = !isAdding.value;
    if (isAdding.value) {
        resetForm();
    }
};

const closeModal = () => {
    isAdding.value = false;
    isEditing.value = false;
    resetForm();
};

const toggleEdit = () => {
    isEditing.value = !isEditing.value;
};

const resetForm = () => {
    form.value = {
        file: null,
        fileName: null,
        filePreview: null,
    };
};

const isFileDragging = ref(false);

const previewFile = (event) => {
    const file = event.target.files[0];
    handleFile(file);
};

const handleFileDragOver = () => {
    isFileDragging.value = true;
};
const handleFileDragLeave = () => {
    isFileDragging.value = false;
};

const handleFileDrop = (event) => {
    isFileDragging.value = false;
    const file = event.dataTransfer.files[0];
    handleFile(file);
};

const handleFile = (file) => {
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


const submitForm = async () => {
    const formData = new FormData();
    formData.append("file", form.value.file);

    try {
        // Send the request only when user confirms
        router.post(`/settings/adding-students/store`, formData, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                usePage().props.flash = { success: "Scholars added to the scholarship!" };
                headers.value = [];
                previewData.value = [];
                error.value = "";
                fileReadyToUpload.value = false;
                document.getElementById("dropzone-file").value = null; // Clear file input


            },
        });
    } catch (err) {
        error.value = "An error occurred while uploading the file.";
        console.error("Error during file upload:", err);
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


// radix vue testing

const toastVisible = ref(false);
const toastMessage = ref("");

watchEffect(() => {
    const flashMessage = usePage().props.flash?.success;

    if (flashMessage) {
        console.log("Showing toast with message:", flashMessage);
        toastMessage.value = flashMessage;
        toastVisible.value = true;

        setTimeout(() => {
            console.log("Hiding toast...");
            toastVisible.value = false;
        }, 3000);
    }
});

</script>