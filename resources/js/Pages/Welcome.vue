<script setup>
import { Head, Link } from '@inertiajs/vue3';
import FloatingNav from '@/Components/LandingPage/FloatingNav.vue';
import Section_1 from '@/Components/LandingPage/A_Title_Section.vue';
import Section_2 from '@/Components/LandingPage/B_About_Us.vue';
import Section_3 from '@/Components/LandingPage/C_Tags.vue';
import Section_4 from '@/Components/LandingPage/D_Sponsors.vue';
import Section_5 from '@/Components/LandingPage/E_Scholarships.vue';
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
    scholarships: {
        type: Array,
        required: true,
    },
    sponsors: {
        type: Array,
        required: true,
    },
    schoolyears: {
        type: String,
        required: true,
    },
    branding: {
        type: Object,
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

    <div class="bg-[#E9F4FF] w-full">
        <!-- Sticky Navbar for Desktop and Mobile -->
        <div class="sticky xl:top-3 z-50">
            <FloatingNav :branding="branding"/>
        </div>

        <!-- Page Sections -->
        <Section_1 />
        <Section_2 />
        <Section_3 />
        <Section_4 :sponsors="sponsors" />
        <Section_5 :scholarships="scholarships" :sponsors="sponsors" :schoolyears="schoolyears" />
        <SectionFooter />
    </div>
</template>
