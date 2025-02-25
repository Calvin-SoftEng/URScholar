<template>

<!-- Stats Section -->
    <div class="grid grid-cols-5">
        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
            <div class="flex flex-row space-x-3 items-center">
                <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base"/>
                <p class="text-gray-500 text-sm">Scholarship Batches</p>
            </div>
            <div class="w-full flex flex-row justify-between space-x-3 items-end">
                <p class="text-4xl font-semibold font-kanit">55</p>
                <button class="px-3 bg-blue-400 text-white rounded-full text-sm">2 new Batch</button>
            </div>
        </div>

        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
            <div class="flex flex-row space-x-3 items-center">
                <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base"/>
                <p class="text-gray-500 text-sm">Total Verified Scholars</p>
            </div>
            <div class="w-full flex flex-row justify-between space-x-3 items-end">
                <p class="text-4xl font-semibold font-kanit">55</p>
            </div>
        </div>

        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
            <div class="flex flex-row space-x-3 items-center">
                <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base"/>
                <p class="text-gray-500 text-sm">Unverified Scholars</p>
            </div>
            <p class="text-4xl font-semibold font-kanit">1</p>
        </div>

        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
            <div class="flex flex-row space-x-3 items-center">
                <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base"/>
                <p class="text-gray-500 text-sm">Submitted Requirements</p>
            </div>
            <p class="text-4xl font-semibold font-kanit">2</p>
        </div>

        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
            <div class="flex flex-row space-x-3 items-center">
                <font-awesome-icon :icon="['far', 'circle-check']" class="text-primary text-base"/>
                <p class="text-gray-500 text-sm">Completed Scholars</p>
            </div>
            <p class="text-4xl font-semibold font-kanit">2</p>
        </div>
    </div>

    <div class="w-full h-[1px] bg-gray-200"></div>

    <div class="flex flex-row justify-between items-center">
      <span>List of Batches</span>

      <button @click="toggleSendBatch" class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
        <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
        <span class="font-normal">Forward Completed Scholars</span>
      </button>
    </div>
    

    <Link :href="(route('cashier.payouts'))">
        <div
        class="bg-gradient-to-r from-white to-[#D2CFFE] w-full rounded-lg p-5 shadow-sm hover:bg-lightblue">
        <div class="flex flex-row justify-between items-center cursor-pointer">
            <span>Batch 1</span>
            <div class="grid grid-cols-3 gap-5 items-center">
                <div class="flex flex-col justify-center items-center">
                    <span>Status</span>
                    <span class="bg-green-100 text-green-800 border border-green-400 text-xs font-medium px-2.5 py-0.5 rounded">Completed</span>
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
    </Link>

    <!-- forwarding batch list -->
    <div v-if="ForwardBatchList"
        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
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
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>

            <!-- body -->
            <div class="p-5">
            <label for="batchSelection" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Select a Batch to Forward:
            </label>
            <select id="batchSelection" v-model="selectedBatch"
                class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                <option value="all">📤 Send All Batches</option>
                <option v-for="batch in batches" :key="batch.id" :value="batch.id">
                    {{ batch.batch_no }}
                </option>
            </select>

            <!-- Forward Button -->
            <!-- <div class="mt-4 flex justify-end">
                <button @click="forwardBatches"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    Forward Selected Batches
                </button>
            </div> -->
        </div>
        </div>
    </div>

</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, watchEffect, onBeforeMount, reactive } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

const ForwardBatchList = ref(false);

const toggleSendBatch = () => {
  ForwardBatchList.value = !ForwardBatchList.value;
}

const closeModal = () => {
    ForwardBatchList.value = false;
    resetForm();
};

</script>

