<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div v-if="loading" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
                <div class="bg-white p-5 rounded-lg shadow-lg flex flex-col items-center">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary mb-3"></div>
                    <p class="text-gray-700">Loading scholarship data...</p>
                </div>
            </div>

            <div class="w-full mx-auto space-y-3">
                <!-- Breadcrumbs -->
                <div class="breadcrumbs text-sm text-gray-400 mb-2">
                    <ul>
                        <li class="hover:text-gray-600">
                            <Link href="/dashboard">Home</Link>
                        </li>
                        <li class="hover:text-gray-600">
                            <Link href="/scholarships">Scholarships</Link>
                        </li>
                        <li class="hover:text-gray-600">
                            <span>{{ scholarship?.name }}</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">{{ currentBatch ? `Batch ${currentBatch.batch_no}`
                                : 'Batch 1' }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Header Section -->
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <div class="text-3xl font-semibold text-gray-700 flex flex-col gap-2">
                        <h1
                            class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <Link>
                            <button class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">
                                < </button>
                                    </Link>
                                    <span>{{ scholarship?.name }}</span>
                                    <span>{{ scholarship?.type }}</span>
                        </h1>
                        <span class="text-xl">SY {{ schoolyear?.year || '2024' }} - {{ props.selectedSem || 'Semester'
                            }} Semester</span>
                    </div>

                    <!-- Stats Section -->
                    <div class="grid grid-cols-2 shadow-sm rounded-lg border">
                        <!-- Completed Scholars -->
                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'circle-check']" class="text-green-600 text-base" />
                                <p class="text-gray-500 text-sm">Completed Scholars</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit text-green-600">{{ stats.completedCount }}</p>
                        </div>

                        <!-- Total Scholars -->
                        <div class="flex flex-col items-start py-4 px-10">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Total Active Scholars</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit">{{ totalScholars }}</p>
                        </div>

                    </div>

                </div>

                <div class="w-full h-[1px] bg-gray-200 my-4"></div>

                <!-- Actions bar -->
                <div class="flex justify-between items-center mb-4">
                    <div class="flex gap-2">
                        <Select v-model="selectedBatchId" @update:modelValue="changeBatch">
                            <SelectTrigger class="w-[200px]">
                                <SelectValue :placeholder="`Batch ${currentBatch?.batch_no || '1'}`" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Available Batches</SelectLabel>
                                    <SelectItem v-for="batch in batches" :key="batch.id" :value="batch.id">
                                        Batch {{ batch.batch_no }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>

                        <!-- Refresh button that only appears when data has changed -->
                        <Button v-if="dataChanged" variant="outline" @click="refreshData"
                            class="flex items-center gap-2">
                            <font-awesome-icon :icon="['fas', 'sync']" :class="{ 'animate-spin': refreshing }" />
                            Refresh Data
                        </Button>
                    </div>

                    <Button variant="default" @click="openScholarship" class="bg-primary text-white">
                        <font-awesome-icon :icon="['fas', 'plus']" class="mr-2" />
                        Add Scholars
                    </Button>
                </div>

                <div v-if="props.batches.campus_id === $page.props.auth.user.campus_id">

                    <ScholarList :scholarship="scholarship" :batches="batches" :scholars="scholars"
                        :requirements="requirements" @update:stats="updateStats" />
                </div>
                <div v-else>
                    <div v-if="props.batches.status === 'Active'"
                        class="bg-white w-full dark:bg-gray-800 p-6 rounded-lg text-center animate-fade-in">
                        <font-awesome-icon :icon="['fas', 'user-graduate']"
                            class="text-4xl text-gray-400 dark:text-gray-500 mb-4" />
                        <p class="text-lg text-gray-700 dark:text-gray-300">
                            Batch still on going. Please check back later.
                        </p>
                    </div>
                    <div v-else>
                        <div v-if="props.batches.status !== 'Inactive'"
                            class="bg-white w-full dark:bg-dsecondary p-6 rounded-lg text-center animate-fade-in">
                            <font-awesome-icon :icon="['fas', 'user-graduate']"
                                class="text-4xl text-gray-400 dark:text-gray-500 mb-4" />
                            <p class="text-lg text-gray-700 dark:text-gray-300">
                                This
                            </p>
                        </div>
                        <div v-else>
                            <ScholarList :scholarship="scholarship" :batches="batches" :scholars="scholars"
                                :requirements="requirements" @update:stats="updateStats" />
                        </div>

                    </div>

                </div>


                <!-- <Payroll_List :scholarship="scholarship" :batches="batches" :scholars="scholars"
                        :requirements="requirements" @update:stats="updateStats" /> -->

            </div>
        </div>

        <!-- Toast notification -->
        <ToastProvider>
            <ToastRoot v-if="toast.visible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">{{ toast.title }}</ToastTitle>
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toast.message }}</ToastDescription>
            </ToastRoot>
            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, watchEffect, onMounted, computed, onUnmounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/Components/ui/select';
import ScholarList from '../../../Components/Staff/ScholarsTabs/ScholarList.vue';
import Payroll_List from '@/Components/Staff/ScholarsTabs/Payroll_List.vue';
import { cloneDeep, isEqual } from 'lodash';

const props = defineProps({
    scholarship: Object,
    schoolyear: Object,
    selectedSem: Object,
    batches: Array,
    scholars: Array,
    requirements: Array,
    payout: Array,
    totalScholars: Number,
});

const total_scholars = computed(() => {
    return props.scholars.filter(scholar => {
        // Add your conditions here, for example:
        // return scholar.isActive === true;
        return true; // Count all scholars by default
    }).length;
});

// State management
const loading = ref(false);
const refreshing = ref(false);
const selectedBatchId = ref('');
const currentBatch = computed(() => {
    if (!props.batches || props.batches.length === 0) return null;
    if (selectedBatchId.value) {
        return props.batches.find(batch => batch.id === selectedBatchId.value);
    }
    return props.batches[0]; // Default to first batch
});

// Add refs to track original data and detect changes
const originalScholars = ref([]);
const originalRequirements = ref([]);
const dataChanged = ref(false);

// Statistics state
const stats = ref({
    submittedCount: 0,
    completedCount: 0,
    totalScholars: 0
});

// Toast notification state
const toast = ref({
    visible: false,
    title: '',
    message: '',
    type: 'success'
});

// Computed property
const hasPayrollData = computed(() => {
    return props.payout && props.payout.length > 0;
});

// Calculate initial stats and store original data
onMounted(() => {
    calculateStats();
    // Store initial data for comparison
    originalScholars.value = cloneDeep(props.scholars || []);
    originalRequirements.value = cloneDeep(props.requirements || []);

    // Set up auto-refresh every 3 minutes
    const refreshInterval = setInterval(() => {
        if (dataChanged.value) {
            quietRefresh();
        }
    }, 180000); // 3 minutes

    // Clean up interval when component unmounts
    return () => clearInterval(refreshInterval);
});

// Watch for changes in props to show refresh button
watchEffect(() => {
    if (props.scholars && props.requirements) {
        const scholarsChanged = !isEqual(originalScholars.value, props.scholars);
        const requirementsChanged = !isEqual(originalRequirements.value, props.requirements);
        dataChanged.value = scholarsChanged || requirementsChanged;
    }
});

// Watch for flash messages from the server
watchEffect(() => {
    const flashMessage = usePage().props.flash?.success;
    const flashError = usePage().props.flash?.error;

    if (flashMessage) {
        showToast('Success', flashMessage);
    } else if (flashError) {
        showToast('Error', flashError, 'error');
    }
});

// Calculate statistics based on scholar data
const calculateStats = () => {
    if (!props.scholars || props.scholars.length === 0) {
        stats.value = { submittedCount: 0, completedCount: 0, totalScholars: 0 };
        return;
    }

    const totalScholars = props.scholars.length;
    const submittedCount = props.scholars.filter(scholar =>
        scholar.submittedRequirements > 0
    ).length;

    const completedCount = props.scholars.filter(scholar =>
        scholar.status === 'Complete'
    ).length;

    stats.value = {
        submittedCount,
        completedCount,
        totalScholars
    };
};

// Update stats from ScholarList component
const updateStats = (newStats) => {
    stats.value = { ...stats.value, ...newStats };
    // When stats are updated by child component, mark data as changed
    dataChanged.value = true;
};

// Change the selected batch
const changeBatch = () => {
    loading.value = true;

    router.visit(
        `/scholarships/${props.scholarship.id}/batch/${selectedBatchId.value}`,
        {
            preserveState: false,
            onSuccess: () => {
                calculateStats();
                // Reset data change tracking after batch change
                originalScholars.value = cloneDeep(props.scholars || []);
                originalRequirements.value = cloneDeep(props.requirements || []);
                dataChanged.value = false;
                showToast('Batch Changed', 'Scholarship batch data loaded successfully');
            },
            onError: () => {
                showToast('Error', 'Failed to load batch data', 'error');
            },
            onFinish: () => {
                loading.value = false;
            }
        }
    );
};

// Navigate to add scholars page
const openScholarship = () => {
    router.visit(`/scholarships/${props.scholarship.id}/adding-scholars`, {
        data: {
            selectedYear: props.schoolyear.id,
            selectedSem: props.selectedSem,
            scholarship: props.scholarship.id,
            batchId: currentBatch.value?.id
        },
        preserveState: true
    });
};

// Refresh data with loading indicator
const refreshData = async () => {
    if (refreshing.value) return;

    refreshing.value = true;

    try {
        await router.reload({
            only: ['scholars', 'requirements'],
            onSuccess: () => {
                calculateStats();
                // Reset the change tracking
                originalScholars.value = cloneDeep(props.scholars || []);
                originalRequirements.value = cloneDeep(props.requirements || []);
                dataChanged.value = false;
                showToast('Data Updated', 'Scholarship data has been refreshed');
            },
            onError: () => {
                showToast('Error', 'Failed to refresh data', 'error');
            },
            onFinish: () => {
                refreshing.value = false;
            }
        });
    } catch (error) {
        console.error('Error refreshing data:', error);
        showToast('Error', 'Failed to refresh data', 'error');
        refreshing.value = false;
    }
};

// Quiet background refresh without notifications
const quietRefresh = async () => {
    try {
        await router.reload({
            only: ['scholars', 'requirements'],
            preserveScroll: true,
            onSuccess: () => {
                calculateStats();
                // Reset the change tracking
                originalScholars.value = cloneDeep(props.scholars || []);
                originalRequirements.value = cloneDeep(props.requirements || []);
                dataChanged.value = false;
            }
        });
    } catch (error) {
        console.error('Error in background refresh:', error);
    }
};

// Toast notification helper
const showToast = (title, message, type = 'success') => {
    toast.value = {
        visible: true,
        title,
        message,
        type
    };

    // Auto-hide toast after 3 seconds
    setTimeout(() => {
        toast.value.visible = false;
    }, 3000);
};

onMounted(() => {
    window.addEventListener('popstate', () => {
        window.location.reload();
    });
});

onUnmounted(() => {
    window.removeEventListener('popstate', () => {
        window.location.reload();
    });
});
</script>

<style>
/* Prime Vue component overrides */
.p-fileupload-choose-button {
    background-color: #003366 !important;
    color: white !important;
    border-radius: 4px;
}

/* Animations */
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