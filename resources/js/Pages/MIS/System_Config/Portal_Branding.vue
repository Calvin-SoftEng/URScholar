<template>
  <!-- Same template as provided, but with the form submission now actually working -->

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="bg-dirtywhite dark:bg-dprimary p-6 h-full w-full space-y-2">
      <!-- Branding Header -->
      <div>
        <h1 class="text-2xl font-bold mb-5 dark:text-dtext">Portal Branding</h1>
      </div>
      <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
        Configure your portal branding by uploading logos, customizing colors, and setting typography.
      </p>

      <!-- Branding Configuration -->
      <form @submit.prevent="submitForm">
  <div class="w-full mt-5">
    <div class="bg-white dark:bg-dcontainer relative overflow-x-auto border border-gray-200 dark:border-gray-800 rounded-lg p-6">
      <div class="flex flex-row justify-between w-full">
        <h1 class="text-xl font-semibold font-quicksand text-dprimary dark:text-dtext mb-4">Branding Settings</h1>
        <div class="space-x-2">
          <button v-if="changesApplied" @click="cancelChanges" type="button"
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
            Cancel
          </button>
          <button v-if="changesApplied" type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Save Changes
          </button>
          <button @click="applyChanges" type="button"
            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-green-700 transition-colors">
            Apply Changes
          </button>
        </div>
      </div>

      <div>
        <!-- Light Mode Logo Upload -->
        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Portal Logo (Light Mode)</label>
        <div class="mb-5 flex items-center gap-4">
          <div
            class="w-40 h-32 p-2 border rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
            <img v-if="tempLogoLight" :src="tempLogoLight" alt="Light Mode Logo"
              class="object-cover w-full h-full">
            <img v-else src="../../../../assets/images/main_logo.png" alt="Light Mode Logo">
          </div>
          <div class="w-full">
            <input type="file" ref="logoLightInput" @change="previewLogo('light', $event)" accept="image/*"
              class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
          </div>
        </div>

        <!-- Dark Mode Logo Upload -->
        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Portal Logo (Dark Mode)</label>
        <div class="mb-5 flex items-center gap-4">
          <div
            class="w-40 h-32 p-2 border rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
            <img v-if="tempLogoDark" :src="tempLogoDark" alt="Dark Mode Logo" class="object-cover w-full h-full">
            <img v-else src="../../../../assets/images/main_logo_white.png" alt="Light Mode Logo">
          </div>
          <div class="w-full">
            <input type="file" ref="logoDarkInput" @change="previewLogo('dark', $event)" accept="image/*"
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
            <div
              class="w-10 h-10 p-1 border rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
              <img v-if="tempFavicon" :src="tempFavicon" alt="Favicon" class="object-cover w-full h-full">
              <span v-else class="text-gray-500 text-xs">No Icon</span>
            </div>
            <div class="w-full">
              <input type="file" ref="faviconInput" @change="uploadFavicon" accept="image/*"
                class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
            </div>
          </div>
        </div>

        <!-- Preview Section - Only shown after Apply Changes is clicked -->
        <div v-if="changesApplied" class="p-4 bg-gray-100 dark:bg-gray-800 rounded-lg mt-5">
          <h2 class="text-lg font-semibold mb-3 dark:text-dtext">Overall Preview</h2>
          <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-gray-200 border rounded-lg flex items-center justify-center overflow-hidden">
              <!-- Light mode logo -->
              <img v-if="finalLogoLight" :src="finalLogoLight" class="w-full h-full object-contain dark:hidden" alt="Preview Logo">

              <!-- Dark mode logo -->
              <img v-if="finalLogoDark" :src="finalLogoDark" class="w-full h-full object-contain hidden dark:block" alt="Preview Logo (Dark)">

              <span v-else class="text-gray-500">No Logo</span>
            </div>
            <div>
              <p class="text-2xl font-poppins font-bold dark:text-dtext">
                {{ finalBrandingName || "Portal Name" }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
    </div>
    <ToastProvider>
      <ToastRoot v-if="toastVisible"
        class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
        <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
      </ToastRoot>

      <ToastViewport class="fixed bottom-4 right-4" />
    </ToastProvider>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, reactive, computed, onMounted,watchEffect } from 'vue';
import { Tooltip } from 'primevue';
import { User } from 'lucide-vue-next';
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';

const props = defineProps({
  campuses: Array,
  coor: Array,
  cashier: Array,
  branding: Object,
});

// Form for submitting to the server
const form = useForm({
  logoLight: null,
  logoDark: null,
  brandingName: '',
  favicon: null,
  status: 'Active'
});


// Refs for file inputs
const logoLightInput = ref(null);
const logoDarkInput = ref(null);
const faviconInput = ref(null);

// Temporary state for form inputs
const tempLogoLight = ref(null);
const tempLogoDark = ref(null);
const tempBrandingName = ref(null);
// const tempBrandingName = 'URScholar';
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


// Initialize with existing data if available
onMounted(() => {
  if (props.branding) {
    tempBrandingName.value = props.branding.branding_name || '';
    finalBrandingName.value = props.branding.branding_name || '';

    if (props.branding.light_path) {
      tempLogoLight.value = "/" + props.branding.light_path;
      finalLogoLight.value = "/" + props.branding.light_path;
    }

    if (props.branding.dark_path) {
      tempLogoDark.value = "/" + props.branding.dark_path;
      finalLogoDark.value = "/" + props.branding.dark_path;
    }

    if (props.branding.favicon_path) {
      tempFavicon.value = "/" + props.branding.favicon_path;
      finalFavicon.value = "/" + props.branding.favicon_path;
    }

    // Store initial values for cancel functionality
    originalState.logoLight = finalLogoLight.value;
    originalState.logoDark = finalLogoDark.value;
    originalState.brandingName = finalBrandingName.value;
    originalState.favicon = finalFavicon.value;
  }
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

// Add this to your existing refs
const changesApplied = ref(false);

// Then modify your applyChanges function
const applyChanges = () => {
  finalLogoLight.value = tempLogoLight.value;
  finalLogoDark.value = tempLogoDark.value;
  finalBrandingName.value = tempBrandingName.value;
  finalFavicon.value = tempFavicon.value;
  
  // Plus the new line we added earlier
  changesApplied.value = true;
};

// Also, update the cancelChanges function to reset this state
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
  
  // Hide the Save Changes button
  changesApplied.value = false;
};

// Submit form to server
const submitForm = () => {
  // Apply changes first to make sure everything is up to date
  applyChanges();

  // Prepare form data
  form.brandingName = finalBrandingName.value;
  form.status = 'Active';

  // Get actual file objects from input elements
  if (logoLightInput.value?.files[0]) {
    form.logoLight = logoLightInput.value.files[0];
  }

  if (logoDarkInput.value?.files[0]) {
    form.logoDark = logoDarkInput.value.files[0];
  }

  if (faviconInput.value?.files[0]) {
    form.favicon = faviconInput.value.files[0];
  }

  // Submit to server
  form.post(route('sa.portal_branding_store'), {
    preserveScroll: true,
    onSuccess: () => {
      // Update original state for cancel functionality
      originalState.logoLight = finalLogoLight.value;
      originalState.logoDark = finalLogoDark.value;
      originalState.brandingName = finalBrandingName.value;
      originalState.favicon = finalFavicon.value;

      // Show success message
      alert('Branding settings saved successfully!');
    },
    onError: (errors) => {
      console.error('Error saving branding settings:', errors);
    }
  });
};

// Save changes permanently - replaced by form submission
const saveChanges = () => {
  submitForm();
};

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