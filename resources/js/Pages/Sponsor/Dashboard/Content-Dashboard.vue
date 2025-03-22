<template>
    <div class="w-full h-full bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
        <p>tite</p>
    </div>

</template>

<script setup>
import { ref } from 'vue';

const components = {
    ActiveScholarship,
    QuickPost,
    Messages,
    // Calendar,
};

const props = defineProps({
    scholarships: Array,
    sponsors: Array,
    scholars: Array,
});

</script>
