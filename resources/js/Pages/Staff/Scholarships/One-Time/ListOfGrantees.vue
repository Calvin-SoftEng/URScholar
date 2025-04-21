<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholarships</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">{{ scholarship?.name }}</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between">
                    <div class="text-3xl font-semibold text-gray-700">
                        <h1
                            class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>
                            <span>{{ scholarship?.name }}</span>
                            <span>{{ scholarship?.type }}</span>
                        </h1>
                        <span class="text-xl">SY {{ schoolyear?.year || '2024' }} - {{ props.selectedSem || 'Semester'
                            }} Semester</span>
                    </div>
                </div>

                <div class="w-full mt-5 rounded-xl space-y-10">
                    <!-- Stats Section -->
                    <div class="w-full h-[1px] bg-gray-200"></div>

                    <div class="grid grid-cols-2">
                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Total Qualified Applicants</p>
                            </div>
                            <div class="w-full flex flex-row justify-between space-x-3 items-end">
                                <p class="text-4xl font-semibold font-kanit">{{ grantees.length }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-start py-4 px-10">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['far', 'circle-check']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Completed Payouts</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit">{{ DonePayout ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="w-full h-[1px] bg-gray-200"></div>
                    <div>
                        <!-- Forward to Cashier -->
                        <div class="w-full flex justify-end gap-3">
                            <div>
                                <button @click="toggleView"
                                    class="flex items-center gap-2 dark:text-dtext bg-white dark:bg-white 
                                border border-green-300 dark:border-green-500 hover:bg-green-200 px-4 py-2 rounded-lg transition duration-200">
                                    <font-awesome-icon :icon="['fas', 'receipt']" class="text-base" />
                                    <span class="font-normal">
                                        {{ showPayrolls ? 'View Scholar List' : 'View Payrolls' }}
                                    </span>
                                </button>
                            </div>
                            <button v-if="!payouts" @click="toggleSendBatch"
                                class="flex items-center gap-2 bg-green-500 font-poppins text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                                <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                <span class="font-normal">Forward to <span class="font-semibold">University
                                        Cashier</span></span>
                            </button>
                            <div v-else>
                                <button v-tooltip.left="'Complete all batches first'" disabled
                                    class="flex items-center gap-2 dark:text-dtext bg-blue-100 dark:bg-blue-800 
                                                border border-blue-300 dark:border-blue-500  hover:bg-blue-200 px-4 py-2 rounded-lg  transition duration-200">
                                    <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                    <span class="font-normal">Forward to University Cashier</span>
                                </button>
                            </div>
                        </div>

                        <div v-if="!showPayrolls">
                            <ListOfGrantees :scholarship="scholarship" :batches="batches" :grantees="grantees"
                                :requirements="requirements" @update:stats="updateStats" />
                        </div>

                        <div v-else>
                            <PayrollTable :scholarship="scholarship" :batch="batch" :disbursements="disbursements"
                                :scholars="scholars" :payout="payout" @update:stats="updateStats" />
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Forwarding batch list modal -->
        <div v-if="ForwardBatchList"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />

                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Forward the list for Payouts
                            </h2>
                        </div>
                    </div>
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

                <!-- Form -->
                <form @submit.prevent="forwardBatches">
                    <div class="py-6 px-8 flex flex-col gap-6">

                        <h2 class="text-lg font-semibold text-gray-700 dark:text-white">
                            Summary of Qualified Scholars for Forwarding
                        </h2>

                        <!-- Loader -->
                        <div v-if="isLoading" class="flex justify-center items-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Loading campus batches...</span>
                        </div>

                        <!-- Campus Data -->
                        <h3 class="text-md font-semibold text-blue-800 dark:text-blue-300 mb-2">
                            {{ scholarship?.name }} SY {{ schoolyear?.year || '2024' }}
                        </h3>

                        <!-- Campus List -->
                        <ul class="space-y-3">
                            <li v-for="campus in scholarsByCampus" :key="campus.name"
                                class="flex justify-between items-center bg-gray-50 dark:bg-gray-800 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">
                                        {{ campus.name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Scholars: {{ campus.count }}
                                    </p>
                                </div>
                                <span
                                    class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    Ready to Send
                                </span>
                            </li>
                        </ul>

                        <div class="mt-4">
                            <button v-tooltip.left="'Complete all batches'"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                Forward
                            </button>
                        </div>
                    </div>
                </form>
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
import { defineProps, ref, watchEffect, onMounted, computed } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';
import { Tooltip } from 'primevue';
import { initFlowbite } from 'flowbite';
import ListOfGrantees from '@/Components/Staff/OneTimeScholars/ListOfGrantees.vue';
import PayrollTable from '@/Components/Staff/OneTimeScholars/PayrollTable.vue';

// Define props to include scholars data
const props = defineProps({
    batch: Object,
    scholarship: Object,
    schoolyear: Object,
    selectedSem: String,
    grantees: Array,
    campus: Array,
    disbursements: Array,
    scholars: Array,
    payout: Array,
    DonePayout: Number,
});

const directives = {
    Tooltip,
};

// UI State
const showPayrolls = ref(false);
const ForwardBatchList = ref(false);
const isLoading = ref(false);
const isSubmitting = ref(false);
const toastVisible = ref(false);
const toastMessage = ref("");

// Computed properties for stats
const totalQualifiedScholars = computed(() => {
    return props.grantees?.filter(scholar => scholar.status === "Verified").length || 0;
});

const completedPayouts = computed(() => {
    // This would be calculated based on your actual data structure
    // For now returning a placeholder value
    return 0;
});

// Compute scholars grouped by campus, sorted alphabetically
const scholarsByCampus = computed(() => {
    if (!props.grantees) return [];

    // Group grantees by campus
    const campusMap = {};

    props.grantees.forEach(grantee => {
        const campusName = grantee.campus || 'Unknown';
        if (!campusMap[campusName]) {
            campusMap[campusName] = 0;
        }
        campusMap[campusName]++;
    });

    // Convert to array and sort alphabetically
    return Object.keys(campusMap)
        .sort((a, b) => a.localeCompare(b))
        .map(name => ({
            name,
            count: campusMap[name]
        }));
});

// UI toggle functions
const toggleView = () => {
    showPayrolls.value = !showPayrolls.value;
};

const toggleSendBatch = () => {
    ForwardBatchList.value = !ForwardBatchList.value;
};

const closeModal = () => {
    ForwardBatchList.value = false;
};

const forwardBatches = async () => {
    isSubmitting.value = true;

    try {
        // Create payload that matches your controller's requirements
        const payload = {
            scholarship_id: props.scholarship.id,
            grantees: [props.grantees],
            batch_ids: [props.batch.id], // Wrap in array as per controller expectation
            school_year_id: props.schoolyear.id,
            semester: props.selectedSem
        };

        // Send the request
        router.post(`/scholarship/forward-batches`, payload, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                // Show success message
                toastMessage.value = `Successfully forwarded ${payload.grantees.length} scholars to University Cashier`;
                toastVisible.value = true;

                // Refresh data if needed
                updateStats();
            },
            onError: (errors) => {
                console.error('Validation errors:', errors);
                toastMessage.value = 'Failed to forward batch. Please check the form.';
                toastVisible.value = true;
            }
        });
    } catch (error) {
        console.error('Error forwarding batch:', error);
        toastMessage.value = error.message || 'Failed to forward batch';
        toastVisible.value = true;
    } finally {
        isSubmitting.value = false;
    }
};

// Update stats when needed
const updateStats = () => {
    // Logic to refresh statistics
};

// Initialize flowbite components
onMounted(() => {
    initFlowbite();
});

// Watch for flash messages
watchEffect(() => {
    const flashMessage = usePage().props.flash?.success;

    if (flashMessage) {
        toastMessage.value = flashMessage;
        toastVisible.value = true;

        setTimeout(() => {
            toastVisible.value = false;
        }, 3000);
    }
});
</script>

<style scoped>
/* override the prime vue components */
:root {
    --p-tooltip-background: #D97706 !important;
    /* Yellow warning color */
}

.p-tooltip-text {
    font-size: 12px !important;
    color: white !important;
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