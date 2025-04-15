<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <!-- Breadcrumbs and Header -->
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">Home</li>
                        <li class="hover:text-gray-600"><span>Scholarships</span></li>
                        <li><span class="text-blue-400 font-semibold">{{ scholarship.name }}</span></li>
                    </ul>
                </div>

                <div class="flex justify-between">
                    <div class="text-3xl font-semibold text-gray-700">
                        <h1
                            class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <button @click="goBack"
                                class="mr-2 font-poppins font-extrabold text-blue-400 hover:text-blue-500">
                                &lt;
                            </button>
                            <span>{{ scholarship.name }} {{ scholarship.scholarshipType }}</span>
                        </h1>
                        <span class="text-xl">SY {{ schoolyear.year }} - {{ selectedSem }} Semester</span>
                    </div>
                </div>

                <div>
                    <div class="w-full mt-5 rounded-xl space-y-5">
                        <!-- Stats Section -->
                        <div class="w-full h-[1px] bg-gray-200"></div>

                        <div class="grid grid-cols-4">
                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Total Batches</p>
                                </div>
                                <div class="w-full flex flex-row justify-between space-x-3 items-end">
                                    <p class="text-4xl font-semibold font-kanit">{{ batches.length }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Total Grantees</p>
                                </div>
                                <p class="text-4xl font-semibold font-kanit">{{ totalScholars }}</p>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Payouts</p>
                                </div>
                                <div class="grid grid-cols-3 items-center gap-3">
                                    <p class="text-4xl font-semibold font-kanit text-center">
                                        {{ totalClaimed }}
                                    </p>
                                    <div class="h-5 w-[2px] bg-gray-400 mx-auto"></div>
                                    <p class="text-4xl font-semibold font-kanit text-center">
                                        {{ totalPending }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['far', 'circle-check']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Completed Batches</p>
                                </div>
                                <div class="grid grid-cols-3 items-center gap-3">
                                    <p class="text-4xl font-semibold font-kanit text-center">
                                        {{ completedBatchesCount }}
                                    </p>
                                    <div class="h-5 w-[2px] bg-gray-400 mx-auto"></div>
                                    <p class="text-4xl font-semibold font-kanit text-center">
                                        {{ batches.length }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full h-[1px] bg-gray-200"></div>

                        <!-- Batch List Section -->
                        <div class="flex flex-row justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-800 mt-4">
                                List of Batches by Campus
                            </h2>

                            <div class="flex flex-row space-x-3 items-center">
                                <!-- Campus Filter -->
                                <span class="font-poppins text-sm">Filter Campus</span>
                                <select v-model="selectedCampus"
                                    class="p-2.5 text-sm border border-gray-200 rounded-lg dark:bg-gray-700 dark:text-white">
                                    <option value="">All Campuses</option>
                                    <option v-for="campus in campuses" :key="campus.id" :value="campus.id">
                                        {{ campus.name }}
                                    </option>
                                </select>

                                <!-- Forward to Cashier Button -->
                                <button @click="toggleSendBatch" :class="[
                                    hasForwardableBatches
                                        ? 'bg-green-500 hover:bg-green-700 text-white'
                                        : 'bg-blue-100 dark:bg-blue-800 border border-blue-300 dark:border-blue-500 hover:bg-blue-200 dark:text-dtext',
                                    'flex items-center gap-2 font-poppins px-4 py-2 rounded-lg transition duration-200'
                                ]" :disabled="!hasForwardableBatches"
                                    v-tooltip.left="!hasForwardableBatches ? 'All batches must be inactive and complete' : ''">
                                    <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                    <span class="font-normal">Forward to <span
                                            class="font-semibold">Cashiers</span></span>
                                </button>
                            </div>
                        </div>

                        <!-- Current User's Campus Batches -->
                        <div v-if="currentUserBatches.length > 0" class="mb-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">
                                {{ currentUserCampus.name }} <span class="text-sm font-medium text-blue-500">(Your
                                    Campus)</span>
                            </h3>

                            <div v-for="batch in currentUserBatches" :key="batch.id"
                                class="bg-gradient-to-r from-[#F8F9FC] to-[#D2CFFE] w-full rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer mb-3"
                                @click="viewBatchDetails(batch)">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-lg font-semibold text-gray-800">Batch #{{ batch.batch_no
                                            }}</span>
                                        <span :class="{
                                            'status-badge completed': batch.status === 'Completed',
                                            'status-badge pending': batch.status === 'Pending',
                                            'status-badge processing': batch.status === 'Processing'
                                        }">{{ batch.status || 'Pending' }}</span>
                                    </div>

                                    <div class="grid grid-cols-4 gap-6">
                                        <div class="flex flex-col items-center">
                                            <span class="text-sm text-gray-600">Total Scholars</span>
                                            <span class="text-xl font-bold text-blue-600">{{ batch.claimed_count +
                                                batch.not_claimed_count }}</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="text-sm text-gray-600">Claimed</span>
                                            <span class="text-xl font-bold text-green-600">{{ batch.claimed_count
                                                }}</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="text-sm text-gray-600">Pending</span>
                                            <span class="text-xl font-bold text-yellow-600">{{ batch.not_claimed_count
                                                }}</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="text-sm text-gray-600">Date Created</span>
                                            <span class="text-base text-gray-700">{{ formatDate(batch.created_at)
                                                }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Campuses' Batches -->
                        <div v-for="campusId in otherCampusIds" :key="campusId"
                            v-show="!selectedCampus || selectedCampus === '' || selectedCampus === campusId.toString()"
                            class="mb-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">
                                {{ getCampusName(parseInt(campusId)) }} Campus
                            </h3>

                            <!-- Check if payouts for this campus are pending/active -->
                            <div v-if="isCampusPayoutActive(campusId)" class="mb-4">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg text-center animate-fade-in">
                                    <font-awesome-icon :icon="['fas', 'user-graduate']"
                                        class="text-4xl text-gray-400 dark:text-gray-500 mb-4" />
                                    <p class="text-lg text-gray-700 dark:text-gray-300">
                                        Payout for this campus is still Ongoing
                                    </p>
                                </div>
                            </div>

                            <!-- Show completed batches for this campus -->
                            <div v-else>
                                <div v-for="batch in getCampusBatches(campusId)" :key="batch.id"
                                    class="bg-gradient-to-r from-[#F8F9FC] to-[#D2CFFE] w-full rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer mb-3"
                                    @click="viewBatchDetails(batch)">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-800">Batch {{ batch.batch_no
                                            }}</span>

                                        <div class="grid grid-cols-4 gap-6">
                                            <div class="flex flex-col items-center">
                                                <span class="text-sm text-gray-600">Total Scholars</span>
                                                <span class="text-xl font-bold text-blue-600">{{ batch.claimed_count +
                                                    batch.not_claimed_count }}</span>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <span class="text-sm text-gray-600">Claimed</span>
                                                <span class="text-xl font-bold text-green-600">{{ batch.claimed_count
                                                    }}</span>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <span class="text-sm text-gray-600">Pending</span>
                                                <span class="text-xl font-bold text-yellow-600">{{
                                                    batch.not_claimed_count }}</span>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <span class="text-sm text-gray-600">Date Created</span>
                                                <span class="text-base text-gray-700">{{ formatDate(batch.created_at)
                                                    }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- No batches message -->
                        <div v-if="!hasAnyBatches" class="text-center py-8">
                            <font-awesome-icon :icon="['far', 'folder-open']" class="text-gray-400 text-5xl mb-3" />
                            <p class="text-gray-500 text-lg">No batches found for any campus.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forward batch list modal -->
        <div v-if="ForwardBatchList"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <!-- Modal content here - kept as is -->
        </div>

        <!-- Toast notifications -->
        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">{{ toastMessage.title }}</ToastTitle>
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage.description }}
                </ToastDescription>
            </ToastRoot>
            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ToastProvider, ToastRoot, ToastTitle, ToastDescription, ToastViewport } from 'radix-vue';
import InputError from '@/Components/InputError.vue';
import { initFlowbite } from 'flowbite';

const props = defineProps({
    scholarship: Object,
    schoolyear: Object,
    selectedSem: String,
    batches: Array,
    campuses: Array,
    currentUser: Object,
    payoutsByCampus: Object,
});

// Form and UI state
const form = useForm({
    payoutStartInput: '',
    payoutEndInput: '',
});

const selectedCampus = ref('');
const ForwardBatchList = ref(false);
const isLoading = ref(false);
const isSubmitting = ref(false);

// Toast notification
const toastVisible = ref(false);
const toastMessage = ref({ title: '', description: '' });

// Get current user's campus info
const currentUserCampus = computed(() => {
    return props.campuses.find(campus => campus.id === props.currentUser.campus_id) || {};
});

// Get batches for current user's campus
const currentUserBatches = computed(() => {
    return props.batches.filter(batch =>
        batch.campus_id === props.currentUser.campus_id &&
        (!selectedCampus.value || selectedCampus.value === '' || selectedCampus.value === props.currentUser.campus_id.toString())
    );
});

// Get IDs of campuses other than current user's
const otherCampusIds = computed(() => {
    const campusIds = props.batches
        .map(batch => batch.campus_id.toString())
        .filter(id => id !== props.currentUser.campus_id.toString());
    return [...new Set(campusIds)]; // Remove duplicates
});

// Check if there are any batches
const hasAnyBatches = computed(() => props.batches.length > 0);

// Calculate statistics
const totalScholars = computed(() => {
    return props.batches.reduce((total, batch) => {
        return total + (batch.claimed_count + batch.not_claimed_count);
    }, 0);
});

const totalClaimed = computed(() => {
    return props.batches.reduce((total, batch) => {
        return total + batch.claimed_count;
    }, 0);
});

const totalPending = computed(() => {
    return props.batches.reduce((total, batch) => {
        return total + batch.not_claimed_count;
    }, 0);
});

const completedBatchesCount = computed(() => {
    return props.batches.filter(batch => batch.status === 'Completed').length;
});

// Get forwardable batches
const forwardableBatches = computed(() => {
    return props.batches.filter(batch =>
        batch.status === 'Accomplished' &&
        batch.not_claimed_count === 0
    );
});

const hasForwardableBatches = computed(() => forwardableBatches.value.length > 0);

// Helper functions
const getCampusName = (campusId) => {
    const campus = props.campuses.find(c => c.id === campusId);
    return campus ? campus.name : 'Unknown Campus';
};

const getCampusBatches = (campusId) => {
    return props.batches.filter(batch => batch.campus_id.toString() === campusId.toString());
};

const isCampusPayoutActive = (campusId) => {
    if (!props.payoutsByCampus || !props.payoutsByCampus[campusId]) return false;

    // Check if any payout for this campus is Pending or Active
    return props.payoutsByCampus[campusId].some(payout =>
        payout.status === 'Pending' || payout.status === 'Active'
    );
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    }).format(date);
};

// Actions
const goBack = () => {
    window.history.back();
};

const viewBatchDetails = (batch) => {
    router.visit(route('scholarships.batch.show', {
        scholarship: props.scholarship.id,
        batch: batch.id
    }));
};

const toggleSendBatch = () => {
    ForwardBatchList.value = true;
};

const closeModal = () => {
    ForwardBatchList.value = false;
    form.reset();
};

const forwardBatches = () => {
    isSubmitting.value = true;

    if (forwardableBatches.value.length === 0) {
        toastMessage.value = {
            title: 'Error',
            description: 'No batches available to forward'
        };
        toastVisible.value = true;
        isSubmitting.value = false;
        return;
    }

    const batchIds = forwardableBatches.value.map(batch => batch.id);

    form.post(route('cashier.forward'), {
        onSuccess: () => {
            toastMessage.value = {
                title: 'Success!',
                description: 'Batches have been forwarded to the cashier.'
            };
            toastVisible.value = true;
            closeModal();
            setTimeout(() => {
                toastVisible.value = false;
            }, 3000);
        },
        onError: (errors) => {
            console.error('Error forwarding batches:', errors);
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};

// Initialize components on mount
onMounted(() => {
    initFlowbite();

    // Initialize Flowbite Datepicker when modal opens
    watch(ForwardBatchList, (newValue) => {
        if (newValue) {
            setTimeout(() => {
                initFlowbite();
            }, 200);
        }
    });
});
</script>

<style scoped>
.breadcrumbs ul {
    display: flex;
    align-items: center;
}

.breadcrumbs li:not(:last-child)::after {
    content: '/';
    margin: 0 0.5rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.completed {
    background-color: #DEF7EC;
    color: #03543E;
}

.status-badge.pending {
    background-color: #FEF3C7;
    color: #92400E;
}

.status-badge.processing {
    background-color: #DBEAFE;
    color: #1E40AF;
}

/* Animations */
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}
</style>