<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ContentDashboard from '@/Pages/Super_Admin/Dashboard/Content-Dashboard.vue';
import Messaging from '@/Pages/Coordinator/Messaging/Messaging.vue';
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #default>
            <!-- Your dashboard content goes here -->
            <ContentDashboard />
        </template>
    </AuthenticatedLayout>
</template>
