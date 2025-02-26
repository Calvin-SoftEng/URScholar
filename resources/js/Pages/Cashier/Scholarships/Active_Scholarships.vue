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
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <button v-for="scholarship in scholarships" :key="scholarship.id">
                            <Link :href="`/cashier/scholarships/${scholarship.id}`" class="block">
                            <div
                                class="card border bg-white hover:shadow-xl hover:border-gray-400 dark:bg-dcontainer dark:border-gray-600 dark:hover:border-gray-400">
                                <!-- <Link :href="`/scholarships/${scholarship.id}`"> -->
                                <div class="card-body p-5 space-y-2">
                                    <div class="badge badge-info text-[12px] badge-outline">
                                        {{ getSponsorName(scholarship.sponsor_id) }}
                                    </div>
                                    <h2
                                        class="card-title text-4xl text-gray-800 font-sora font-semibold dark:text-dtext">
                                        {{ scholarship.name }}
                                    </h2>
                                    <p class="leading-relaxed items-start justify-start text-sm text-gray-400">
                                        <!-- <span class="justify-start items-start">Created on: {{ new Date(scholarship.created_at).toLocaleDateString()
                                                }}</span><br>
                                            <span>Sponsoring Since: {{ new
                                                Date(scholarship.created_at).toLocaleDateString('en-US', {
                                                    year: 'numeric',
                                                    month: 'long', day: 'numeric'
                                                }) }}</span> -->
                                        when is the payout deadline
                                    </p>
                                    <p class="text-md text-gray-600 mb-4 text-justify overflow-hidden text-overflow-truncate line-clamp-4 h-24 max-w-full"
                                        style=" display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;">
                                        <!-- {{ scholarship.description }} -->
                                    </p>
                                    <div class="w-full flex flex-col space-y-2">
                                        <p class="leading-loose text-sm text-gray-400">
                                            Scheduled Payouts:
                                        </p>

                                        <!-- Scrollable Container -->
                                        <div
                                            class="max-h-40 overflow-y-auto space-y-2 scrollbar-thin scrollbar-thumb-gray-300">
                                            <div class="flex flex-row p-2 rounded-lg bg-blue-500 justify-between">
                                                <span>
                                                    <!-- {{scholarship.name}} -->
                                                </span>
                                                <div class="flex flex-row gap-1">
                                                    <span class="material-symbols-rounded text-base text-white">
                                                        pending_actions
                                                    </span>
                                                    <span>
                                                        <!-- {{scholarship.status}} -->
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- </Link> -->
                            </div>
                            </Link>
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


const openScholarship = () => {
    router.visit(`/cashier/scholarships/${props.value.id}`, {
        preserveState: true
    });
};


const closeModal = () => {
    ScholarshipSpecification.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = {
        id: null,
        name: '',
        description: '',
        sponsor_id: '',
        selectedYear: null,
        selectedSem: null,
    };
};

</script>

<style scoped>
.p-tooltip-text {
    margin-left: 0px;
    font-size: 13px !important;
}
</style>