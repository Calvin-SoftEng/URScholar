<template>
    <section class="relative py-12 px-4 sm:py-8 sm:px-2 bg-[#F8F8FA] flex flex-col lg:flex-row items-start gap-6 min-h-[50vh]">
        <!-- Left Section (Sponsor List - Full Width on Mobile) -->
        <div class="lg:w-1/2 w-full space-y-6 relative z-10">
            <div v-for="sponsor in sponsors" :key="sponsor.id"
                class="bg-white flex flex-col sm:flex-row items-center sm:items-start gap-4 p-6 sm:p-3 border border-gray-300 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:scale-[1.02]">
                <div class="flex flex-col items-center space-y-1">
                    <div class="w-32 sm:w-20 aspect-square rounded-lg overflow-hidden">
                        <img :src="`/storage/sponsor/logo/${sponsor.logo}`" alt="Sponsor Logo"
                            class="w-full h-full object-cover shadow-sm" />
                    </div>
                    <span class="text-gray-600 text-sm sm:text-xs font-medium">{{sponsor.abbreviation}}</span>
                </div>

                <div class="flex flex-col space-y-2 w-full">
                    <h3 class="text-2xl sm:text-lg font-bold text-blue-900">{{sponsor.name}}</h3>
                    <span class="text-gray-500 text-sm sm:text-xs">Sponsoring since: <strong class="text-gray-700">{{sponsor.since}}</strong></span>
                    <p class="text-gray-700 text-base sm:text-sm leading-relaxed">{{sponsor.description}}</p>
                </div>
            </div>
        </div>

        <!-- Right Section (Sticky "Meet Our Sponsors") -->
        <div class="lg:w-1/2 w-full sm:space-y-5 lg:space-y-10 text-center lg:text-left items-center justify-center flex flex-col relative z-10 lg:sticky lg:top-32 self-start">
            <h1 class="sm:text-3xl lg:text-6xl font-extrabold text-white drop-shadow-lg">Meet Our Scholarship Sponsors</h1>
            <p class="sm:text-sm lg:text-xl text-gray-200 max-w-2xl leading-relaxed">
                Our generous sponsors play a vital role in empowering students at the University of Rizal System. 
                Their commitment provides scholarships that change lives, helping students achieve their academic dreams.
            </p>
            <p class="sm:text-sm lg:text-xl text-gray-200 max-w-2xl leading-relaxed">
                You'll find a list of esteemed sponsors who have contributed to making education more accessible. 
                We extend our deepest gratitude to each of them.
            </p>
        </div>

        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center opacity-40">
            <img src="../../../assets/images/ursbg_landingpage.png" class="w-full h-full object-cover" alt="">
        </div>

        <!-- Blue Overlay -->
        <div class="absolute inset-0 bg-blue-900 bg-opacity-30 mix-blend-multiply"></div>
    </section>








</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    sponsors: {
        type: Array,
        required: true
    },
    scholarships: {
        type: Array,
        required: true
    },
    schoolyears: {
        type: Array,
        required: true
    }
});


</script>