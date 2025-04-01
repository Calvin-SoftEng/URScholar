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
            <form @submit.prevent="">
                <div class="w-full mt-5">
                <div class="bg-white relative overflow-x-auto border border-gray-200 rounded-lg p-6">
                    <div class="flex flex-row justify-between w-full">
                    <h1 class="text-xl font-semibold font-quicksand text-primary mb-4">Branding Settings</h1>
                    <div class="space-x-2">
                        <button @click="cancelChanges" type="button" 
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                        </button>
                        <button @click="saveChanges" type="button"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Save Changes
                        </button>
                        <button @click="applyChanges" type="button"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Apply Changes
                        </button>
                    </div>
                    </div>

                    <div>
                    <!-- Light Mode Logo Upload -->
                    <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Portal Logo (Light Mode)</label>
                    <div class="mb-5 flex items-center gap-4">
                        <div class="w-40 h-32 p-2 border rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                        <img v-if="tempLogoLight" :src="tempLogoLight" alt="Light Mode Logo" class="object-cover w-full h-full">
                        <span v-else class="text-gray-500">No Logo</span>
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
                        <span v-else class="text-gray-500">No Logo</span>
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
                        <div class="flex items-center gap-4">
                        <div class="w-10 h-10 p-1 border rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                            <img v-if="tempFavicon" :src="tempFavicon" alt="Favicon" class="object-cover w-full h-full">
                            <span v-else class="text-gray-500 text-xs">No Icon</span>
                        </div>
                        <div class="w-full">
                            <input type="file" @change="uploadFavicon" accept="image/*" 
                            class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        </div>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg mt-5">
                        <h2 class="text-lg font-semibold mb-3">Overall Preview</h2>
                        <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gray-200 border rounded-lg flex items-center justify-center overflow-hidden">
                            <img v-if="finalLogoLight" :src="finalLogoLight" class="w-full h-full object-contain" alt="Preview Logo">
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
import { Head } from '@inertiajs/vue3';
import { ref, reactive, computed } from 'vue';
import { Tooltip } from 'primevue';
import { User } from 'lucide-vue-next';
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'

defineProps({
    campuses: Array,
    coor: Array,
    cashier: Array,
});

// Temporary state for form inputs
const tempLogoLight = ref(null);
const tempLogoDark = ref(null);
const tempBrandingName = ref('');
const tempFavicon = ref(null);

// Final state (what's actually applied/saved)
const finalLogoLight = ref(null);
const finalLogoDark = ref(null);
const finalBrandingName = ref('');
const finalFavicon = ref(null);

// Original state to revert to when canceling
const originalState = reactive({
  logoLight: null,
  logoDark: null,
  brandingName: '',
  favicon: null
});

// Preview logo uploads
const previewLogo = (mode, event) => {
  const file = event.target.files[0];
  if (!file) return;
  
  const reader = new FileReader();
  reader.onload = (e) => {
    if (mode === 'light') {
      tempLogoLight.value = e.target.result;
    } else if (mode === 'dark') {
      tempLogoDark.value = e.target.result;
    }
  };
  
  reader.readAsDataURL(file);
};

// Upload favicon
const uploadFavicon = (event) => {
  const file = event.target.files[0];
  if (!file) return;
  
  const reader = new FileReader();
  reader.onload = (e) => {
    tempFavicon.value = e.target.result;
  };
  
  reader.readAsDataURL(file);
};

// Apply changes to preview
const applyChanges = () => {
  finalLogoLight.value = tempLogoLight.value;
  finalLogoDark.value = tempLogoDark.value;
  finalBrandingName.value = tempBrandingName.value;
  finalFavicon.value = tempFavicon.value;
};

// Save changes permanently
const saveChanges = () => {
  // Apply changes first
  applyChanges();
  
  // Then save to original state (for cancel functionality)
  originalState.logoLight = finalLogoLight.value;
  originalState.logoDark = finalLogoDark.value;
  originalState.brandingName = finalBrandingName.value;
  originalState.favicon = finalFavicon.value;
  
  // Here you would typically send data to your backend
  alert('Changes saved successfully!');
};

// Cancel changes
const cancelChanges = () => {
  // Revert to original state
  tempLogoLight.value = originalState.logoLight;
  tempLogoDark.value = originalState.logoDark;
  tempBrandingName.value = originalState.brandingName;
  tempFavicon.value = originalState.favicon;
  
  // Also reset the preview
  finalLogoLight.value = originalState.logoLight;
  finalLogoDark.value = originalState.logoDark;
  finalBrandingName.value = originalState.brandingName;
  finalFavicon.value = originalState.favicon;
};

</script>