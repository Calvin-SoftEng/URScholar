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
                    <div class="flex flex-row justify-between w-full">
                        <h1 class="text-xl font-semibold font-quicksand text-primary mb-4">
                            Branding Settings
                        </h1>
                        <button @click="saveBranding" class="btn bg-primary text-white px-5 py-1 rounded-lg">
                            Save Branding
                        </button>
                    </div>

                    <div>
                        <!-- Light Mode Logo Upload -->
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Portal Logo (Light Mode)</label>
                        <div class="mb-5 flex items-center gap-4">
                        <!-- Light Mode Preview Box -->
                        <div class="w-40 h-32 p-2 border rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                            <img v-if="logoLight" :src="logoLight" alt="Light Mode Logo" class="object-cover w-full h-full">
                        </div>

                        <div class="w-full"> 
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="light_file_input">
                            Upload Light Mode Logo
                            </label>
                            <input type="file" @change="uploadLogo('light')" accept="image/*"
                            class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            id="light_file_input">
                        </div>
                        </div>

                        <!-- Light Mode Full Preview -->
                        <div v-if="logoLight" class="mb-5">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Light Mode Logo Preview</p>
                        <div class="border rounded-lg overflow-hidden p-2 flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                            <img :src="logoLight" alt="Full Light Logo Preview" class="object-contain w-64 h-40">
                        </div>
                        </div>

                        <!-- Dark Mode Logo Upload -->
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Portal Logo (Dark Mode)</label>
                        <div class="mb-5 flex items-center gap-4">
                        <!-- Dark Mode Preview Box -->
                        <div class="w-40 h-32 p-2 border rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                            <img v-if="logoDark" :src="logoDark" alt="Dark Mode Logo" class="object-cover w-full h-full">
                        </div>

                        <div class="w-full"> 
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="dark_file_input">
                            Upload Dark Mode Logo
                            </label>
                            <input type="file" @change="uploadLogo('dark')" accept="image/*"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            id="dark_file_input">
                        </div>
                        </div>

                        <!-- Dark Mode Full Preview -->
                        <div v-if="logoDark" class="mb-5">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Dark Mode Logo Preview</p>
                        <div class="border rounded-lg overflow-hidden p-2 flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                            <img :src="logoDark" alt="Full Dark Logo Preview" class="object-contain w-64 h-40">
                        </div>
                        </div>
                    </div>


                   <!-- Portal Branding Name Input -->
                    <div class="mb-5">
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Portal Branding Name</label>
                        <input 
                        type="text" 
                        v-model="portalBrandingName" 
                        placeholder="Enter branding name" 
                        class="w-full h-10 border rounded-lg p-2 text-gray-900 dark:text-white dark:bg-gray-700 dark:border-gray-600"
                        >
                    </div>

                    <!-- Favicon Upload -->
                    <div class="mb-5">
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Favicon</label>
                        <input type="file" @change="uploadFavicon" accept="image/*" class="border rounded-lg p-2 w-full">
                    </div>

                    <!-- Preview Section -->
                    <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg mt-5">
                        <h2 class="text-lg font-semibold mb-3" :style="{ color: primaryColor }">Preview</h2>
                        <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gray-200 border rounded-lg flex items-center justify-center">
                            <img v-if="logoLight" :src="logoLight" class="w-full h-full object-contain">
                            <span v-else class="text-gray-500">No Logo</span>
                        </div>
                        <div>
                            <p class="text-lg font-bold" :style="{ color: primaryColor }">{{ portalBrandingName || "Portal Name" }}</p>
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
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'

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

const logoLight = ref(null);
const logoDark = ref(null);

const uploadLogo = (mode) => (event) => {
  const file = event.target.files[0];
  if (file) {
    const newUrl = URL.createObjectURL(file);

    if (mode === "light") {
      if (logoLight.value) URL.revokeObjectURL(logoLight.value);
      logoLight.value = newUrl;
    } else if (mode === "dark") {
      if (logoDark.value) URL.revokeObjectURL(logoDark.value);
      logoDark.value = newUrl;
    }
  }
};

// Light and Dark Mode Logo Previews
const lightLogoPreview = ref(null);
const darkLogoPreview = ref(null);

const uploadLightLogo = (event) => {
  const file = event.target.files[0];
  if (file) {
    lightLogoPreview.value = URL.createObjectURL(file);
  }
};

const uploadDarkLogo = (event) => {
  const file = event.target.files[0];
  if (file) {
    darkLogoPreview.value = URL.createObjectURL(file);
  }
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