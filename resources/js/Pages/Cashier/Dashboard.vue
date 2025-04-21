<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ContentDashboard from '@/Pages/Cashier/Dashboard/Content-Dashboard.vue';

const props = defineProps({
    scholarships: Array,
    sponsors: Array,
    scholars: Array,
    active_scholars: Array,
    activity_logs: Array,
    academic_year: Object,
    univ_students: Object,
    disbursements: Array,
});


</script>

<template>

    <AuthenticatedLayout>
        <template #default>
            <!-- Your dashboard content goes here -->
            <ContentDashboard class="w-full h-full dark:bg-dprimary" :disbursements="disbursements" :univ_students="univ_students" :academic_year="academic_year" :activity_logs="activity_logs" :scholarships="scholarships" :sponsors="sponsors" :active_scholars="active_scholars" :scholars="scholars
            "/>
        </template>
    </AuthenticatedLayout>
</template>
