<template>
    <!-- Messages Container -->
    <div class="relative bg-gradient-to-b from-primary via-[#2b3e76db] to-blue-700 dark:from-[#E9F4FF] dark:via-white dark:to-blue-300 w-full min-h-full rounded-xl overflow-hidden">

        <!-- Top Left: Date & Time -->
        <div class="absolute top-4 left-4 text-gray-200 dark:text-gray-500 text-lg font-normal max-w-md leading-snug space-y-2">
          <p>Today is <span class="font-bold text-xl text-white dark:text-dprimary">{{ currentDate }}</span>. Let’s make meaningful progress and strive for excellence.</p>
          <p class="mt-1"><span class="font-bold text-2xl text-white dark:text-dprimary">{{ currentTime }}</span></p>
        </div>

        <!-- Bottom Left: School Year & Semester -->
        <div class="absolute bottom-8 left-6 z-10 text-white dark:text-gray-800 text-xl font-semibold font-sora">
        S.Y. 2024-2025<br />
        <span class="text-3xl">2nd Semester</span>
        </div>

        <!-- Bottom Right: Background Image -->
        <div class="absolute bottom-[-5%] right-[-5%] w-64 h-64 md:w-60 md:h-60 pointer-events-none opacity-80">
        <img src="../../../../assets/images/BG.png" alt="Decoration" class="w-full h-full object-contain" />
        </div>

    </div>





</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    scholarships: Array,
    sponsors: Array,
});

const currentDate = ref('')
const currentTime = ref('')

onMounted(() => {
  const updateDateTime = () => {
    const now = new Date()

    // Format: September 29, 2020
    currentDate.value = now.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    })

    // Format: 10:45:03 AM or PM
    currentTime.value = now.toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      second: '2-digit',
      hour12: true,
    })
  }

  updateDateTime()
  setInterval(updateDateTime, 1000)
})



</script>