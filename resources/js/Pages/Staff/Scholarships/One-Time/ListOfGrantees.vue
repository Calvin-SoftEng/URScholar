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
                        <li class="hover:text-gray-600">
                            <span>{{ scholarship.name }}</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Batch {{ batch.batch_no }}</span>
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
                        <span class="text-xl">SY {{ schoolyear?.year || '2024' }} - {{ selectedSem || 'Semester' }}
                            Semester</span>
                    </div>
                    <div>
                        <Link :href="`/scholarships/${scholarship.id}/view`"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                        Back to Scholarship
                        </Link>
                    </div>
                </div>

                <div class="w-full mt-5 rounded-xl space-y-10">
                    <!-- Stats Section -->
                    <div class="w-full h-[1px] bg-gray-200"></div>

                    <div class="grid grid-cols-4">
                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Total Scholars</p>
                            </div>
                            <div class="w-full flex flex-row justify-between space-x-3 items-end">
                                <p class="text-4xl font-semibold font-kanit">{{ grantees.length }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'user-check']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Verified Scholars</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit">{{ verifiedScholars }}</p>
                        </div>

                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Unverified Scholars</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit">{{ unverifiedScholars }}</p>
                        </div>

                        <div class="flex flex-col items-start py-4 px-10">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'building']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Campus</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit">{{ campus?.name || 'All' }}</p>
                        </div>
                    </div>

                    <div class="w-full h-[1px] bg-gray-200"></div>

                    <div>
                        <!-- Forward to Cashier -->
                        <div class="w-full flex justify-end mb-5">
                            <button @click="toggleSendBatch"
                                class="flex items-center gap-2 bg-green-500 font-poppins text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                                <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                <span class="font-normal">Forward to <span class="font-semibold">University
                                        Cashier</span></span>
                            </button>
                        </div>

                        <!-- Grantees Table -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-semibold mb-4">List of Grantees - Batch {{ batch.batch_no }}</h2>

                            <div class="flex justify-between mb-4">
                                <div class="flex items-center w-1/3">
                                    <input type="text" v-model="search" placeholder="Search scholars..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="flex gap-2">
                                    <select v-model="filterStatus"
                                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Status</option>
                                        <option value="Verified">Verified</option>
                                        <option value="Unverified">Unverified</option>
                                    </select>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                ID
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Campus
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Course
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Year Level
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Student Status
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        <tr v-for="grantee in filteredGrantees" :key="grantee.id"
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ grantee.urscholar_id }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ grantee.full_name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ grantee.campus }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ grantee.course }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ grantee.year_level }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ grantee.student_status }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    :class="`px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    ${grantee.status === 'Verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}`">
                                                    {{ grantee.status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button @click="verifyGrantee(grantee.id)"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-2">
                                                    {{ grantee.status === 'Verified' ? 'Unverify' : 'Verify' }}
                                                </button>
                                                <button @click="viewDetails(grantee.scholar_id)"
                                                    class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="filteredGrantees.length === 0">
                                            <td colspan="8"
                                                class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                No grantees found matching your criteria
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forward batch modal -->
        <div v-if="forwardBatchModal"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Forward Batch {{ batch.batch_no }} for Payout
                            </h2>
                        </div>
                    </div>
                    <button type="button" @click="closeBatchModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="forwardBatch">
                    <div class="py-4 px-8 flex flex-col gap-3">
                        <div class="mb-4">
                            <label for="dateRange"
                                class="block mb-2 text-base font-medium text-gray-500 dark:text-white">
                                Select Payout Date Range:
                            </label>
                            <div id="date-range-picker" date-rangepicker class="flex items-center gap-4 w-full">
                                <!-- Payout Start Date -->
                                <div class="flex flex-col w-full">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input v-model="payoutStartDate" id="datepicker-range-start" name="start"
                                            type="text" autocomplete="off"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Start Date">
                                    </div>
                                </div>

                                <span class="text-gray-500">to</span>

                                <!-- Payout End Date -->
                                <div class="flex flex-col w-full">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input v-model="payoutEndDate" id="datepicker-range-end" name="end" type="text"
                                            autocomplete="off"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="End Date">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 text-base font-medium text-gray-500 dark:text-white">
                                Batch Summary:
                            </label>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-gray-700 dark:text-gray-300">Batch Number: <span class="font-semibold">{{
                                        batch.batch_no }}</span></p>
                                <p class="text-gray-700 dark:text-gray-300">Total Scholars: <span
                                        class="font-semibold">{{ grantees.length }}</span></p>
                                <p class="text-gray-700 dark:text-gray-300">Verified Scholars: <span
                                        class="font-semibold">{{ verifiedScholars }}</span></p>
                                <p class="text-gray-700 dark:text-gray-300">Unverified Scholars: <span
                                        class="font-semibold">{{ unverifiedScholars }}</span></p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" :disabled="isSubmitting || verifiedScholars === 0"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ isSubmitting ? 'Processing...' : 'Forward to Cashier' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast Notification -->
        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">{{ toastTitle }}</ToastTitle>
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>
            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';
import { initFlowbite } from 'flowbite';

const props = defineProps({
    scholarship: Object,
    batch: Object,
    grantees: Array,
    schoolyear: Object,
    campus: Object,
    selectedSem: String,
});

// Search and filter state
const search = ref('');
const filterStatus = ref('');

// Computed properties for filtered data
const filteredGrantees = computed(() => {
    return props.grantees.filter(grantee => {
        const matchesSearch = grantee.full_name.toLowerCase().includes(search.value.toLowerCase()) ||
            grantee.urscholar_id.toString().includes(search.value);
        const matchesStatus = filterStatus.value ? grantee.status === filterStatus.value : true;

        return matchesSearch && matchesStatus;
    });
});

// Stats computations
const verifiedScholars = computed(() => {
    return props.grantees.filter(grantee => grantee.status === 'Verified').length;
});

const unverifiedScholars = computed(() => {
    return props.grantees.filter(grantee => grantee.status === 'Unverified').length;
});

// Modal state
const forwardBatchModal = ref(false);
const isSubmitting = ref(false);
const payoutStartDate = ref('');
const payoutEndDate = ref('');

// Toast notification state
const toastVisible = ref(false);
const toastTitle = ref('');
const toastMessage = ref('');

// Show toast message
const showToast = (title, message, duration = 3000) => {
    toastTitle.value = title;
    toastMessage.value = message;
    toastVisible.value = true;

    setTimeout(() => {
        toastVisible.value = false;
    }, duration);
};

// Toggle forward batch modal
const toggleSendBatch = () => {
    forwardBatchModal.value = true;
    setTimeout(() => {
        initFlowbite(); // Initialize datepickers
    }, 200);
};

// Close modal
const closeBatchModal = () => {
    forwardBatchModal.value = false;
    payoutStartDate.value = '';
    payoutEndDate.value = '';
};

// Verify/Unverify grantee
const verifyGrantee = (granteeId) => {
    // In a real implementation, this would make an API call
    const grantee = props.grantees.find(g => g.id === granteeId);

    router.patch(`/scholarships/grantees/${granteeId}/toggle-verification`, {}, {
        onSuccess: () => {
            showToast(
                'Status Updated',
                `Scholar ${grantee.full_name} has been ${grantee.status === 'Verified' ? 'unverified' : 'verified'}.`
            );
        },
        onError: () => {
            showToast('Error', 'Failed to update scholar status', 'error');
        }
    });
};

// View scholar details
const viewDetails = (scholarId) => {
    router.visit(`/scholars/${scholarId}`);
};

// Forward batch to cashier
const forwardBatch = () => {
    if (!payoutStartDate.value || !payoutEndDate.value) {
        showToast('Error', 'Please select a date range for payout');
        return;
    }

    isSubmitting.value = true;

    // Prepare payload
    const payload = {
        batch_id: props.batch.id,
        scholarship_id: props.scholarship.id,
        date_start: payoutStartDate.value,
        date_end: payoutEndDate.value,
    };

    // In a real implementation, this would make an API call
    setTimeout(() => {
        router.post('/scholarship/forward-batch', payload, {
            onSuccess: () => {
                showToast('Success', `Batch ${props.batch.batch_no} has been forwarded to the University Cashier for processing.`);
                closeBatchModal();
            },
            onError: (errors) => {
                showToast('Error', errors.message || 'Failed to forward batch');
            },
            onFinish: () => {
                isSubmitting.value = false;
            }
        });
    }, 1000);
};

// Initialize components on mount
onMounted(() => {
    initFlowbite();

    // Check for flash messages
    const flashMessage = usePage().props.flash?.success;
    if (flashMessage) {
        showToast('Success', flashMessage);
    }

    // Initialize datepickers
    watch(forwardBatchModal, (newValue) => {
        if (newValue) {
            setTimeout(() => {
                const startInput = document.getElementById("datepicker-range-start");
                if (startInput) {
                    startInput.addEventListener("changeDate", (event) => {
                        payoutStartDate.value = event.target.value;
                    });
                }

                const endInput = document.getElementById("datepicker-range-end");
                if (endInput) {
                    endInput.addEventListener("changeDate", (event) => {
                        payoutEndDate.value = event.target.value;
                    });
                }
            }, 200);
        }
    });
});
</script>

<style scoped>
.p-tooltip-text {
    font-size: 12px !important;
    color: white !important;
}

/* Transitions */
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

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>