<template>
    <!-- <section class="relative py-16 px-6 h-[100vh] bg-[#F8F8FA] flex flex-col lg:flex-row items-center lg:items-start gap-8">
        <div class="lg:w-1/2 w-full space-y-5 text-center lg:text-left relative z-10">
            <h1 class="text-5xl font-bold text-blue-900">Scholarship Sponsors of URS</h1>
            <p class="text-lg text-gray-600 max-w-2xl">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nec purus feugiat, vestibulum mi nec, ultricies leo.
            </p>
        </div>

        <div class="lg:w-1/2 w-full max-h-[60vh] overflow-y-auto bg-white rounded-xl shadow-lg p-5 space-y-4">
            <div v-for="sponsor in sponsors" :key="sponsor.id" class="flex items-center gap-4 p-4 border rounded-lg">
                <img :src="sponsor.logo" alt="Sponsor Logo" class="w-16 h-16 object-cover rounded-lg">
                <div>
                    <h3 class="text-xl font-bold text-blue-900">{{ sponsor.name }}</h3>
                    <p class="text-gray-600 text-sm">{{ sponsor.description }}</p>
                </div>
            </div>
        </div>

        <div class="absolute inset-0 bg-cover bg-center opacity-10">
            <img src="../../../assets/images/ursbg_landingpage.png" class="w-full h-full object-cover" alt="">
        </div>
    </section> -->



    <section class="relative py-16 px-6 bg-[#F8F8FA] flex flex-col lg:flex-row items-start gap-10 min-h-[50vh]">
    <!-- Left Section (Sponsor List - Auto Expands) --> 
    <div class="lg:w-1/2 w-full p-8 space-y-8 relative z-10">
        <div
            class="bg-white flex flex-col lg:flex-row items-center lg:items-start gap-6 p-8 border border-gray-300 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-[1.02]">
            <div class="flex flex-col items-center space-y-2">
                <img src="../../../assets/images/univ-seal.png" alt="Sponsor Logo" 
                    class="w-32 h-32 rounded-lg border border-gray-300 shadow-sm">
                <span class="text-gray-600 text-base font-medium">CHED</span>
            </div>

            
            <div class="flex flex-col space-y-3 w-full">
                <h3 class="text-3xl font-bold text-blue-900">Commissioner of Baasketball</h3>
                <span class="text-gray-500 text-md">Sponsoring since: <strong class="text-gray-700">2020</strong></span>
                <p class="text-gray-700 text-lg leading-relaxed">Lorem ipsum dolor sit amet consectetur adipisicing elit. Error nesciunt eum tenetur quo doloremque perferendis, amet quos, quasi pariatur aperiam facilis atque totam provident sunt consequatur architecto dolor reprehenderit ullam!</p>
            </div>
        </div>
    </div>

    <!-- Right Section (Sticky Introduction) --> 
    <div class="lg:w-1/2 w-full space-y-6 text-center items-center justify-center flex flex-col lg:text-left relative z-10 lg:sticky top-32 self-start">
        <h1 class="text-5xl font-extrabold text-white drop-shadow-lg">Meet Our Scholarship Sponsors</h1>
        <p class="text-lg text-gray-200 max-w-2xl items-center leading-relaxed">
            Our generous sponsors play a vital role in empowering students at the University of Rizal System. 
            Their commitment provides scholarships that change lives, helping students achieve their academic dreams.
        </p>
        <p class="text-lg text-gray-200 max-w-2xl items-center leading-relaxed">
            Below, you'll find a list of esteemed sponsors who have contributed to making education more accessible. 
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

const sponsors = ref([
    { id: 1, name: "Sponsor A", logo: "@/assets/images/ursbg_landingpage.png", description: "" },
]);


</script>