<template>
<button @click="goBack" class="inline-flex items-center text-sm text-primary hover:underline">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>
    Back
</button>
</template>

<script setup>
const goBack = () => {
window.history.back();
};
</script>