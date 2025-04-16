<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ContentDashboard from '@/Pages/Sponsor/Dashboard/Content-Dashboard.vue';

const props = defineProps({
    sponsor: Object,
    scholarships: Array,
    payouts: Array,
    campuses: Array,
    schoolyears: Array,
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #default>
            <!-- Your dashboard content goes here -->
            <ContentDashboard class="w-full h-full dark:bg-dprimary" :payouts="payouts" :campuses="campuses" :sponsor="sponsor" :scholarships="scholarships" :schoolyears="schoolyears"/>
        </template>
    </AuthenticatedLayout>
</template>
