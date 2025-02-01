<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ContentDashboard from '@/Pages/Super_Admin/Dashboard/Content-Dashboard.vue';

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite p-6 h-full w-full">
            <div>
                <h1 class="text-2xl font-bold">Course List</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of the University's courses. You can add and edit courses for each campus.
            </p>

            <div class="w-full h-full flex flex-col px-5">
                <div class="bg-yellow-400 w-full h-96 rounded-lg ">
                    <span>Antipolo</span>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>