<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-2">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholarships</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">{{ scholarship.name }}</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between">
                    <div class="text-3xl font-semibold text-gray-700">
                        <!-- <span>{{ scholarship.name }}</span> <span>{{schoolyear.year}} {{props.selectedSem}} Semester</span> -->

                        <h1
                            class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span><span>{{
                                scholarship.name }}</span> <span>scholarship type</span>
                        </h1>
                    </div>
                    <div class="flex gap-2">
                        <button @click="openScholarship"
                            class="px-4 py-2 text-sm text-primary dark:text-dtext bg-dirtywhite dark:bg-[#3b5998] border border-1-gray-100 rounded-lg hover:bg-gray-100 font-poppins">
                            <span><font-awesome-icon :icon="['fas', 'user-plus']"
                                    class="mr-2 text-sm dark:text-dtext" />Import Scholars</span>
                        </button>
                        <Link :href="`/scholarships/${props.scholarship.id}/send-access`">
                        <button @click="importScholars"
                            class="px-4 py-2 text-sm text-primary dark:text-dtext bg-dirtywhite dark:bg-[#3b5998] border border-1-gray-100 rounded-lg hover:bg-gray-100 font-poppins">
                            <span><font-awesome-icon :icon="['far', 'envelope']"
                                    class="mr-2 text-sm dark:text-dtext" />Send Email</span>
                        </button>
                        </Link>
                    </div>
                </div>

                <!-- <ScholarList :scholarship="scholarship" :batches="batches" /> -->
                <!-- <Batches :scholarship="scholarship" :batches="batches" :schoolyear="schoolyear" :selectedSem="selectedSem" class="w-full h-full"/> -->


                <div v-if="!batches || batches.length === 0" class="text-center py-5 mt-5">
                    <p class="bg-white p-5 rounded-lg text-lg shadow-sm text-gray-700 dark:text-gray-300">No scholars
                        added yet</p>
                </div>

                <div v-else class="w-full mt-5 rounded-xl space-y-5">
                    <!-- <div class="p-4 flex flex-row justify-between items-center">
                    <span>SY 2024 - Semester</span>
                    <form class="w-3/12">
                        <label for="default-search"
                        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                        <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="default-search" v-model="searchQuery"
                            class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search Scholar" required />
                        </div>
                    </form>
                    </div> -->
                    <!-- <div class="bg-gray-100 mx-4 rounded-lg p-1">
                    <ul class="flex space-x-5 flex-grow justify-left font-quicksand font-semibold">
                        <li v-for="batch in batches" :key="batch.id"><button @click="toggleBatch(batch.id)"
                            class="px-10 py-1 border-b-2 cursor-pointer hover:bg-gray-300 hover:text-gray-600 rounded-lg"
                            :class="expandedBatches === batch.id ? 'bg-white text-primary' : 'bg-gray-100 text-gray-400'">Batch {{ batch.batch_no
                            }}</button>
                        </li>
                    </ul>
                    </div> -->
                    <!-- Stats Section -->
                    <div class="w-full h-[1px] bg-gray-200"></div>

                    <div class="grid grid-cols-5">
                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Scholarship Batches</p>
                            </div>
                            <div class="w-full flex flex-row justify-between space-x-3 items-end">
                                <p class="text-4xl font-semibold font-kanit">55</p>
                                <button class="px-3 bg-blue-400 text-white rounded-full text-sm">2 new Batch</button>
                            </div>
                        </div>

                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Total Verified Scholars</p>
                            </div>
                            <div class="w-full flex flex-row justify-between space-x-3 items-end">
                                <p class="text-4xl font-semibold font-kanit">55</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Unverified Scholars</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit">1</p>
                        </div>

                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Submitted Requirements</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit">2</p>
                        </div>

                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['far', 'circle-check']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Completed Scholars</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit">2</p>
                        </div>
                    </div>

                    <div class="w-full h-[1px] bg-gray-200"></div>

                    <div class="flex flex-row justify-between items-center">
                        <span>List of Batches {{ props.selectedSem }} {{ schoolyear.year }}</span>

                        <button @click="toggleSendBatch"
                            class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                            <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                            <span class="font-normal">Forward Completed Scholars</span>
                        </button>
                    </div>


                    <div v-for="batch in batches" :key="batch.id"
                        class="bg-gradient-to-r from-white to-[#D2CFFE] w-full rounded-lg p-5 shadow-sm hover:bg-lightblue">
                        <div @click="() => openBatch(batch.id)"
                            class="flex flex-row justify-between items-center cursor-pointer">
                            <span>Batch {{ batch.batch_no }}</span>
                            <div class="grid grid-cols-2">
                                <div class="flex flex-col">
                                    <span>No of Scholars</span>
                                    <span>200</span>
                                </div>
                                <div class="flex flex-col">
                                    <span>No of Unverified Scholars</span>
                                    <span>200</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Simplified forwarding batch list modal -->
        <div v-if="ForwardBatchList"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                            Forwarding Batch List
                        </h2>
                    </span>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <!-- body -->
                <div class="py-4 px-8 flex flex-col gap-3">
                    <label for="batchSelection" class="block mb-2 text-base font-medium text-gray-500 dark:text-white">
                        Select a Batch to Forward:
                    </label>

                    <!-- Loading indicator -->
                    <div v-if="isLoading" class="flex justify-center items-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Loading batches...</span>
                    </div>

                    <!-- Checkbox List -->
                    <div v-if="!isLoading" class="flex flex-col gap-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" value="all" v-model="selectedBatches" @change="selectAllBatches"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-gray-900 dark:text-white">Send All Batch List</span>
                        </label>

                        <label v-for="batch in batchesWithScholars" :key="batch.id" class="flex items-center space-x-2">
                            <input type="checkbox" :value="batch.id" v-model="selectedBatches"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-gray-900 dark:text-white">Batch {{ batch.batch_no }}</span>
                            <span class="text-sm text-gray-500">({{ batch.scholar_count }} scholars)</span>
                        </label>
                    </div>

                    <!-- Forward Button -->
                    <div class="mt-4">
                        <button :disabled="isSubmitting || selectedBatches.length === 0" @click="forwardBatches"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ isSubmitting ? 'Processing...' : 'Forward' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">Scholars Added Successfully!</ToastTitle>
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>

    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, watchEffect, onBeforeMount, reactive, onMounted } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';

// Define props to include scholars data
const props = defineProps({
    batches: Array,
    scholarship: Object,
    schoolyear: Object,
    selectedSem: String,
    scholars: Array // Add scholars prop
});

// Forward batch modal state
const ForwardBatchList = ref(false);
const selectedBatches = ref([]);
const isLoading = ref(false);
const isSubmitting = ref(false);
const batchesWithScholars = ref([]);

const toggleSendBatch = async () => {
    ForwardBatchList.value = true;

    // Load batches with scholar counts
    await loadBatchesData();
};

const loadBatchesData = async () => {
    isLoading.value = true;

    try {
        // Calculate scholar counts for each batch using the scholars prop
        setTimeout(() => {
            // Group scholars by batch_id and count them
            const scholarCountsByBatch = props.scholars.reduce((counts, scholar) => {
                if (scholar.batch_id) {
                    counts[scholar.batch_id] = (counts[scholar.batch_id] || 0) + 1;
                }
                return counts;
            }, {});

            // Map batches with their scholar counts
            batchesWithScholars.value = props.batches.map(batch => {
                return {
                    ...batch,
                    scholar_count: scholarCountsByBatch[batch.id] || 0
                };
            });

            // Automatically select all batches when data is loaded
            selectedBatches.value = ['all', ...batchesWithScholars.value.map(batch => batch.id)];

            isLoading.value = false;
        }, 300);
    } catch (error) {
        console.error('Error loading batch data:', error);
        toastMessage.value = 'Failed to load batch data';
        toastVisible.value = true;
        isLoading.value = false;
    }
};

const selectAllBatches = () => {
    if (selectedBatches.value.includes('all')) {
        // If 'all' is selected, select all batch IDs
        selectedBatches.value = ['all', ...batchesWithScholars.value.map(batch => batch.id)];
    } else {
        // If 'all' is unselected, clear all selections
        selectedBatches.value = [];
    }
};

// Watch for changes in individual batch selections
watchEffect(() => {
    // If any individual batch is unselected and 'all' was selected, unselect 'all'
    const allBatchIds = batchesWithScholars.value.map(batch => batch.id);

    if (selectedBatches.value.includes('all') &&
        !allBatchIds.every(id => selectedBatches.value.includes(id))) {
        selectedBatches.value = selectedBatches.value.filter(id => id !== 'all');
    }

    // If all individual batches are selected, also select 'all'
    if (allBatchIds.length > 0 &&
        allBatchIds.every(id => selectedBatches.value.includes(id)) &&
        !selectedBatches.value.includes('all')) {
        selectedBatches.value.push('all');
    }
});

const forwardBatches = async () => {
    isSubmitting.value = true;

    try {
        // Prepare data for submission
        const batchesToForward = selectedBatches.value.includes('all')
            ? batchesWithScholars.value.map(batch => batch.id)
            : selectedBatches.value;

        // Create payload with selected batches
        const payload = {
            scholarship_id: props.scholarship.id,
            scholars: batchesWithScholars.value.reduce((scholars, batch) => {
                if (batchesToForward.includes(batch.id)) {
                    scholars.push(...props.scholars.filter(s => s.batch_id === batch.id));
                }
                return scholars;
            }, []),
            batch_ids: batchesToForward
        };

        await router.post(`/scholarship/forward-batches`, payload);

        // In a real implementation, you would submit to the backend
        setTimeout(() => {
            // Simulate successful submission
            const totalScholars = batchesToForward.reduce((total, batchId) => {
                const batch = batchesWithScholars.value.find(b => b.id === batchId);
                return total + (batch ? batch.scholar_count : 0);
            }, 0);

            toastMessage.value = `Successfully forwarded ${totalScholars} scholars from ${batchesToForward.length} batch(es)`;
            toastVisible.value = true;

            // Close the modal and reset form
            closeModal();

            isSubmitting.value = false;
        }, 1000);

        // In a real implementation, you would use fetch or Inertia.js
    } catch (error) {
        console.error('Error forwarding batches:', error);
        toastMessage.value = error.message || 'Failed to forward batches';
        toastVisible.value = true;
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    ForwardBatchList.value = false;
    resetForm();
};

const resetForm = () => {
    selectedBatches.value = [];
    batchesWithScholars.value = [];
};

// Existing code remains the same
const addVisible = ref(false);
const List = ref(true);

const toggleAdd = () => {
    addVisible.value = true;
    List.value = false;
};

const toggleList = () => {
    List.value = true;
    addVisible.value = false;
};

// Reactive variables to track which tab is open
const activeTab = ref("scholars"); // Default to Scholars

// Functions to toggle the active tab
const toggleScholars = () => {
    activeTab.value = "scholars";
};

const toggleReqs = () => {
    activeTab.value = "requirements";
};

const toggleMonitoring = () => {
    activeTab.value = "monitoring";
};


const openBatch = (batchId) => {
    router.visit(`/scholarships/${props.scholarship.id}/batch/${batchId}`, {
        data: {
            scholarship: props.scholarship.id,
            selectedYear: props.schoolyear.id,
            selectedSem: props.selectedSem
        },
        preserveState: true
    });
};

const expandedBatches = ref(new Set([props.batches?.[0]?.id])) // First batch expanded by default

onMounted(() => {
    if (props.batches && props.batches.length > 0) {
        expandedBatches.value = props.batches[0].id;
    }
});

const selectedSem = ref("");

selectedSem.value = props.selectedSem;

const openScholarship = () => {
    router.visit(`/scholarships/${props.scholarship.id}/adding-scholars`, {
        data: { selectedYear: props.schoolyear.id, selectedSem: props.selectedSem },
        preserveState: true
    });
};

const formData = ref({
    file: null,
    // other form fields...
});

const updateFile = (file) => {
    formData.value.file = file;
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

<style>
/* override the prime vue componentss */

.p-fileupload-choose-button {
    background-color: #003366 !important;
    color: white !important;
    border-radius: 4px;
}

.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}

.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
}

/* Fade transition for backdrop */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>