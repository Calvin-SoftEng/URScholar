<template>

    <!-- Stats Section -->
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
        <span>List of Batches </span>
        <!-- {{ props.selectedSem }} {{ schoolyear.year }} -->

        <div class="flex flex-row space-x-3 items-center">
            <button @click="toggleSendBatch"
                class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                <span class="font-normal">Forward Completed Scholars</span>
            </button>
            <button @click="toggleSendBatch"
                class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                <span class="font-normal">Forward Completed Scholars</span>
            </button>

        </div>
    </div>


    <div v-for="batch in batches" :key="batch.id" class="bg-gradient-to-r from-white to-[#D2CFFE] w-full rounded-lg p-5 shadow-sm hover:bg-lightblue">
        <div @click="() => openBatch(batch.id)" class="flex flex-row justify-between items-center cursor-pointer">
            <span>Batch {{batch.batch_no}}</span>
            <div class="grid grid-cols-3 gap-5 items-center">
                <div class="flex flex-col justify-center items-center">
                    <span>Status</span>
                    <span
                        class="bg-green-100 text-green-800 border border-green-400 text-xs font-medium px-2.5 py-0.5 rounded">Completed</span>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <span>Claimed</span>
                    <span>200</span>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <span>Assigned</span>
                    <span>200</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, watchEffect, onBeforeMount, reactive } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'


const props = defineProps({
    scholarship: Object,
    batches: Array,
});

const openBatch = (batchId) => {
    router.visit(`/cashier/scholarships/${props.scholarship.id}/batch/${batchId}`, {
        data: { 
            scholarship: props.scholarship.id,
        },
        preserveState: true
    });
};
</script>
