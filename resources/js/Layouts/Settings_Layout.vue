<template>
    <Head title="Scholarships" />
    <AuthenticatedLayout>
        <template #default>

            <div class="flex flex-col lg:flex-row w-full h-[calc(100vh-50px)]">

                <SettingsNav />

                <div class="flex-1 lg:h-full h-full lg:ml-0 bg-gray-100">
                <slot></slot>
                </div>
            </div>
        </template>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import { Head, useForm, Link, } from '@inertiajs/vue3';
import { Tooltip } from 'primevue';
import SettingsNav from '../Components/Settings/SettingsSubMenu.vue';

</script>