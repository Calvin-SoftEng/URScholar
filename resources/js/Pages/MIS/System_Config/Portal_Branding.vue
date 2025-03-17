<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite p-6 h-full w-full space-y-2">
            <!-- Branding Header -->
            <div>
                <h1 class="text-2xl font-bold mb-5">Portal Branding</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Configure your portal branding by uploading logos, customizing colors, and setting typography.
            </p>

            <!-- Branding Configuration -->
            <div class="w-full mt-5">
                <div class="bg-white relative overflow-x-auto border border-gray-200 rounded-lg p-6">
                    <h1 class="text-xl font-semibold font-quicksand text-primary mb-4">
                        Branding Settings
                    </h1>

                    <!-- Logo Upload -->
                    <div class="mb-5">
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Portal Logo (Light Mode)</label>
                        <input type="file" @change="uploadLogo('light')" accept="image/*" class="border rounded-lg p-2 w-full">
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Portal Logo (Dark Mode)</label>
                        <input type="file" @change="uploadLogo('dark')" accept="image/*" class="border rounded-lg p-2 w-full">
                    </div>

                    <!-- Color Picker -->
                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Primary Color</label>
                            <input type="color" v-model="primaryColor" class="w-full h-10 border rounded-lg">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Secondary Color</label>
                            <input type="color" v-model="secondaryColor" class="w-full h-10 border rounded-lg">
                        </div>
                    </div>

                    <!-- Typography -->
                    <div class="mb-5">
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Typography</label>
                        <select v-model="fontStyle" class="w-full border rounded-lg p-2">
                            <option value="quicksand">Quicksand</option>
                            <option value="poppins">Poppins</option>
                            <option value="roboto">Roboto</option>
                            <option value="lato">Lato</option>
                        </select>
                    </div>

                    <!-- Favicon Upload -->
                    <div class="mb-5">
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Favicon</label>
                        <input type="file" @change="uploadFavicon" accept="image/*" class="border rounded-lg p-2 w-full">
                    </div>

                    <!-- Preview Section -->
                    <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg mt-5">
                        <h2 class="text-lg font-semibold text-primary mb-3">Preview</h2>
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-200 border rounded-lg flex items-center justify-center">
                                <img v-if="logoLight" :src="logoLight" class="w-full h-full object-contain">
                                <span v-else class="text-gray-500">No Logo</span>
                            </div>
                            <div>
                                <p class="text-lg font-bold" :style="{ color: primaryColor, fontFamily: fontStyle }">Portal Name</p>
                                <p class="text-sm text-gray-500">Your portal branding preview.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Save Branding Settings -->
                    <div class="flex justify-end mt-5">
                        <button @click="saveBranding" class="btn bg-primary text-white px-5 py-2 rounded-lg">
                            Save Branding
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- adding roles -->
        <div v-if="isCreating || isEditing"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                            {{ isEditing ? 'Edit Role' : 'Add New Role'}}
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
                            Role Name</h3>
                        <input v-model="form.name" type="text" id="name"
                            placeholder="Enter Scholarship Name"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            {{ isEditing ? 'Update Role' : 'Add Role' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Tooltip } from 'primevue';
import { User } from 'lucide-vue-next';

defineProps({
    campuses: Array,
    coor: Array,
    cashier: Array,
});


const isCreating = ref(false);
const isEditing = ref(false);


const toggleAddRole = () => {
    isCreating.value = !isCreating.value;
};

const closeModal = () => {
    isCreating.value = false;
    isEditing.value = false;
    resetForm();
};

const toggleEditRole = () => {
    isEditing.value = true;
    isCreating.value = false;
    form.value = { ...scholarship };
};

const resetForm = () => {
    form.value = { id: null, name: '', description: '' };
};


const form = ref({
    name: '',
    location: '',
});

const assign = ref({
    campus_id: null,
    coor_id: null,
    cashier_id: null,
});

const submitForm = async () => {
    try {
        router.post("/mis/univ-settings/campuses/store", form.value);
        closeModal();
    } catch (error) {
        console.error("Error submitting form:", error);
    }
};

const submitAssign = async () => {
    try {
        router.post("/mis/univ-settings/campuses/assign", assign.value);
        closeModal();
    } catch (error) {
        console.error("Error submitting form:", error);
    }
};

</script>