<template>

    <Head title="Scholarships" />
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-2">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Scholarship Payout</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                        <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>Pending
                        Payouts
                    </h1>
                </div>

                <div class="mx-auto py-5">
                    <div v-if="payouts == 0"
                        class="flex flex-col items-center justify-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0 w-16 h-16" />
                        <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">No Payouts Available</h3>
                    </div>
                    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <button v-for="scholarship in scholarships" :key="scholarship.id" class="w-full h-full">
                            <div v-for="payout in getPayoutsForScholarship(scholarship.id)" :key="payout.id">
                                <Link :href="`/cashier/payouts/${payout.id}`" class="block h-full">
                                <div class="card relative border bg-white hover:shadow-xl hover:border-gray-400 dark:bg-dcontainer 
                                            dark:border-gray-600 dark:hover:border-gray-400 flex flex-col h-full">
                                    <div class="card-body p-5 space-y-2 flex flex-col flex-grow">
                                        <!-- Sponsor Badge -->
                                        <div class="badge badge-info text-[12px] badge-outline">
                                            {{ getSponsorName(scholarship.sponsor_id) }}
                                        </div>

                                        <!-- Scholarship Title -->
                                        <h2
                                            class="card-title text-4xl text-gray-800 font-sora font-semibold dark:text-dtext">
                                            {{ scholarship.name }}
                                        </h2>

                                        <!-- Sponsoring Since -->
                                        <p class="text-sm text-gray-400">
                                            Sponsoring Since:
                                            {{ new Date(scholarship.created_at).toLocaleDateString('en-US', {
                                                year: 'numeric', month: 'long', day: 'numeric'
                                            }) }}
                                        </p>

                                        <!-- Active Payouts -->
                                        <div class="flex flex-col flex-grow mt-4 justify-end">
                                            <p class="text-sm text-gray-400 mb-2 text-start">Payouts:</p>

                                            <div
                                                class="max-h-40 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer flex flex-col gap-2">
                                                <!-- No Scholarships Available -->
                                                <!-- <div v-if="sponsor.scholarship.length === 0" 
                                                    class="p-3 text-gray-500 text-center bg-gray-100 rounded-lg border border-gray-300 shadow-sm 
                                                        dark:bg-dsecondary dark:text-gray-400 dark:border-gray-600 flex items-center justify-center h-full">
                                                    No scholarships available.
                                                </div> -->

                                                <!-- Scholarships List -->
                                                <!-- <div v-for="scholarship in sponsor.scholarship" :key="scholarship.id"> -->

                                                <div>
                                                    <div class="flex flex-row p-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 border border-blue-400 shadow-lg 
                                                        hover:shadow-xl transition-all duration-300 justify-between items-center text-white 
                                                        dark:from-dsecondary dark:to-dprimary dark:border-gray-600">

                                                        <!-- Scholarship Name -->
                                                        <div class="flex flex-col text-start">
                                                            <span class="font-semibold text-lg">
                                                                {{ scholarship.name }}
                                                            </span>
                                                            <span class="font-normal text-sm">
                                                                Total Batches:
                                                                {{ getTotalBatchesForScholarship(scholarship.id) }}
                                                                {{ scholarship.scholarshipType }}
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <!-- Pending Status Icon with Tooltip (Optional) -->
                                                            <span class="material-symbols-rounded text-white text-xl">
                                                                pending_actions
                                                            </span>

                                                            <!-- Status Date Text -->
                                                            <span class="text-sm font-semibold text-white">
                                                                {{ formatDate(payout.date_end) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </Link>
                            </div>

                        </button>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, computed } from 'vue';
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { useRouter, useRoute } from 'vue-router'

import { Tooltip } from 'primevue';

import { Button } from '@/Components/ui/button'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'

const props = defineProps({
    scholarships: {
        type: Array,
        required: true
    },
    sponsors: {
        type: Array,
        required: true
    },
    payouts: {
        type: Array,
        required: true
    }
});


const ScholarshipSpecification = ref(false);

const form = ref({
    id: null,
    name: '',
    description: '',
    sponsor_id: ''
});

const getSponsorName = (sponsorId) => {
    const sponsor = props.sponsors.find(s => s.id === sponsorId);
    return sponsor ? sponsor.name : 'Unknown Sponsor';
};


const getPayoutsForScholarship = (scholarshipId) => {
    return props.payouts.filter(payout => payout.scholarship_id === scholarshipId);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const getTotalBatchesForScholarship = (scholarshipId) => {
    // Get unique batch IDs for this scholarship's payouts
    const batchIds = new Set(
        props.payouts
            .filter(payout => payout.scholarship_id === scholarshipId)
            .map(payout => payout.batch_id)
    );
    return batchIds.size;
};

</script>

<style scoped>
.p-tooltip-text {
    margin-left: 0px;
    font-size: 13px !important;
}
</style>