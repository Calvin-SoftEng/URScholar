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

    <div>
      <span>List of Batches </span>
    </div>
    

    <Link :href="(route('cashier.payouts'))">
        <div
        class="bg-gradient-to-r from-white to-[#D2CFFE] w-full rounded-lg p-5 shadow-sm hover:bg-lightblue">
        <div class="flex flex-row justify-between items-center cursor-pointer">
            <span>Batch 1</span>
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
    </Link>

</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, watchEffect, onBeforeMount, reactive } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

</script>

