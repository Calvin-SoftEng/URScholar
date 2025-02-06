<template>
    <div v-for="scholarship in scholarships" :key="scholarship.id" class="bg-dirtywhite w-full h-full p-5 space-y-3 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600">
        <span class="font-poppins font-semibold text-xl dark:text-dtext ">Active Scholarships</span>
        <div class="flex flex-col h-full gap-3">
            <div class="flex-col p-2 block bg-white border rounded border-gray-200 w-full">
                <div>
                    open
                </div>
                <div>
                    {{ scholarship.name }}
                </div>
                <div>
                    Funded Since
                </div>
                <div>
                    Description
                </div>
            </div>
            <div class="w-full justify-end items-end">
                <Link :href="(route('sponsor.index'))">
                <button class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Create New Scholarship</button>
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    sponsors: {
        type: Array,
        required: true
    },
    scholarships: {
        type: Array,
        required: true
    }
});
</script>