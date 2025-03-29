<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative flex min-h-screen flex-col items-center from-blue-900 to-blue-300 bg-[url('../assets/images/bg-opening.png')] bg-blend-multiply bg-cover pt-6 sm:justify-center sm:pt-0">
        <div class="absolute inset-0 bg-blue-900 opacity-50"></div>
        <div class="absolute inset-0 bg-blue-900 opacity-50"></div>
        <!-- <div class="z-10">
            <Link href="/">
                <ApplicationLogo class="mb-5" />
            </Link>
        </div> -->

        <div class="w-8/12 max-w-2xl overflow-hidden bg-white shadow-md sm:rounded-md z-10">
            <slot />
        </div>
    </div>
</template>
