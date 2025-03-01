<script setup>
import { Head, Link } from '@inertiajs/vue3';
import FloatingNav from '@/Components/LandingPage/FloatingNav.vue';
import Section_1 from '@/Components/LandingPage/Section_1.vue';
import Section_2 from '@/Components/LandingPage/Section_2.vue';
import Section_3 from '@/Components/LandingPage/Section_3.vue';
import Section_4 from '@/Components/LandingPage/Section_4.vue';
import SectionFooter from '@/Components/LandingPage/Footer.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <!-- <Head title="Welcome" /> -->

    <div class="bg-[#E9F4FF]">
        <FloatingNav/>
        <Section_1/>
        <Section_2/>
        <Section_3/>
        <Section_4/>
        <SectionFooter/>
    </div>
</template>
