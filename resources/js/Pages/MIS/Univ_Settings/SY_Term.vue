<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite p-6 h-full w-full space-y-10">
            <div>
                <div>
                    <h1 class="text-2xl font-bold mb-5">School year</h1>
                </div>

                <div class="w-full h-full flex flex-col p-10 mt-5 gap-2 bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 font-kanit text-white rounded-xl ">
                    <span class="items-center justify-center font-bold text-3xl">2025-2026</span>
                    <h1 class="font-normal text-sm">Current Academic Year</h1>
                </div>
            </div>
        </div>
        
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import CalendarPicker from "@/Components/MIS/CalendarPicker.vue";

const selectedDate = ref({ month: "", day: "" });


</script>