<template>
    <Head title="Scholarships" />
    <AuthenticatedLayout>
        <template #default>

            <div class="flex flex-1 flex-col lg:flex-row overflow-hidden">

                <SettingsNav />

                <div class=" flex-1 h-full overflow-auto">
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
import SettingsNav from '../Components/Staff/Settings/SettingsSubMenu.vue';

</script>