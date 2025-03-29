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
            <form>
                <div class="w-full mt-5">
                    <div class="bg-white relative overflow-x-auto border border-gray-200 rounded-lg p-6">
                    <div class="flex flex-row justify-between w-full">
                        <h1 class="text-xl font-semibold font-quicksand text-primary mb-4">Branding Settings</h1>
                        <button @click="saveChanges" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Apply Changes
                        </button>
                    </div>

                    <div>
                        <!-- Light Mode Logo Upload -->
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Portal Logo (Light Mode)</label>
                            <div class="mb-5 flex items-center gap-4">
                            <div class="w-40 h-32 p-2 border rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                <img v-if="tempLogoLight" :src="tempLogoLight" alt="Light Mode Logo" class="object-cover w-full h-full">
                            </div>
                            <div class="w-full"> 
                                <input type="file" @change="previewLogo('light', $event)" accept="image/*"
                                class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            </div>
                        </div>

                        <!-- Dark Mode Logo Upload -->
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Portal Logo (Dark Mode)</label>
                            <div class="mb-5 flex items-center gap-4">
                            <div class="w-40 h-32 p-2 border rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                <img v-if="tempLogoDark" :src="tempLogoDark" alt="Dark Mode Logo" class="object-cover w-full h-full">
                            </div>
                            <div class="w-full"> 
                                <input type="file" @change="previewLogo('dark', $event)" accept="image/*"
                                class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            </div>
                        </div>

                        <!-- Portal Branding Name Input -->
                        <div class="mb-5">
                            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Portal Branding Name</label>
                            <input type="text" v-model="tempBrandingName" placeholder="Enter branding name"
                                class="w-full h-10 border rounded-lg p-2 text-gray-900 dark:text-white dark:bg-gray-700 dark:border-gray-600">
                        </div>

                        <!-- Favicon Upload -->
                        <div class="mb-5">
                            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Favicon</label>
                            <input type="file" @change="uploadFavicon" accept="image/*" class="border rounded-lg w-full">
                        </div>

                        <!-- Preview Section -->
                        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg mt-5">
                        <h2 class="text-lg font-semibold mb-3">Overall Preview</h2>
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-200 border rounded-lg flex items-center justify-center">
                            <img v-if="finalLogoLight" :src="finalLogoLight" class="w-full h-full object-contain">
                            <span v-else class="text-gray-500">No Logo</span>
                            </div>
                            <div>
                            <p class="text-lg font-bold">{{ finalBrandingName || "Portal Name" }}</p>
                            <p class="text-sm text-gray-500">Your portal branding preview.</p>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </form>
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

// States for both editing and display
const tempLogoLight = ref(null);
const tempBrandingName = ref('');
const finalLogoLight = ref(null);
const finalBrandingName = ref('');

// Function to handle logo preview
const previewLogo = (mode, event) => {
  const file = event.target.files[0];
  if (!file) return;
  
  // Validate file type
  if (!file.type.match('image.*')) {
    alert('Please select an image file');
    return;
  }
  
  // Create a URL for the image
  const reader = new FileReader();
  reader.onload = (e) => {
    if (mode === 'light') {
      tempLogoLight.value = e.target.result;
    }
  };
  reader.readAsDataURL(file);
};

// Function to save changes (and keep the preview)
const saveChanges = () => {
  // Update the final values to match the temporary ones
  finalLogoLight.value = tempLogoLight.value;
  finalBrandingName.value = tempBrandingName.value;
  
  // Important: Do NOT reset the temp values here
  // This ensures the preview remains visible
};

// Function to reset changes
const resetChanges = () => {
  // Reset the temporary values to match the saved final values
  tempLogoLight.value = finalLogoLight.value;
  tempBrandingName.value = finalBrandingName.value;
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