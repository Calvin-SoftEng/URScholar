<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ContentDashboard from '@/Pages/Staff/Dashboard/Content-Dashboard.vue';

const props = defineProps({
    scholarships: Array,
    sponsors: Array,
    scholars: Array,
    active_scholars: Array,
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #default>
            <!-- Your dashboard content goes here -->
            <ContentDashboard class="w-full h-full dark:bg-dprimary" :scholarships="scholarships" :sponsors="sponsors" :active_scholars="active_scholars" :scholars="scholars
            "/>
        </template>
    </AuthenticatedLayout>
</template>
