<template>
    <section class="relative sm:py-12 sm:px-16 md:py-8 md:px-24 lg:py-6 lg:px-20 xl:py-16 xl:px-40 h-screen my-auto items-center bg-dirtywhite">

        <div class="flex flex-col text-left space-y-20 justify-center h-full">
            <div class="text-left flex flex-col space-y-5">
                <span class="bg-gradient-to-r from-[#0D3B80] to-[#296fd6] bg-clip-text text-transparent font-sora text-6xl font-bold">Unlock Educational Success</span>
                <p class="sm:text-4xl md:text-2xl lg:text-xl xl:text-lg  text-primary font-medium font-albert">Access comprehensive database of scholarships <br> and stay updated with the latest announcements <br> to boost your educational journey</p>
            </div>

            <div class="flex flex-row justify-between">
                <div class="flex flex-row space-x-20">
                    <div class="space-y-2 text-primary">
                        <span class="flex items-center justify-center w-10 h-10 bg-white border-1 border-gray-800 rounded-md shadow-md mb-3">
                            <font-awesome-icon :icon="['fas', 'magnifying-glass']" class="text-gray-800 font-bold" />
                        </span>
                        <span class="font-medium text-2xl">Comprehensive Search</span>
                        <p class="font-light">Easily find scholarships <br> that match your profile</p>
                    </div>
                    <div class="space-y-2 text-primary">
                        <span class="flex items-center justify-center w-10 h-10 bg-white border-1 border-gray-800 rounded-md shadow-md mb-3">
                            <font-awesome-icon :icon="['fas', 'bell']" class="text-gray-800 font-bold" />
                        </span>
                        <span class="font-medium text-2xl">Timely Updates</span>
                        <p class="font-light">Stay informed with the <br> latest scholarship <br> informations and <br> application deadlines</p>
                    </div>
                    <!-- <div>
                        <span>Chatbot Assistant</span>
                        <p>Ask your queries and <br> concerns with your <br> personal chatbot</p>
                    </div> -->
                </div>
                

            </div>
        </div>
        <!-- <div class="relative z-10 space-y-5">
            <h1 class="text-5xl font-bold text-white mb-4">Scholarship Sponsors of URS</h1>
            <p class="text-lg text-gray-200 max-w-2xl mx-auto">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nec purus feugiat, vestibulum mi nec, ultricies leo.
            </p>

            <div class="flex justify-center gap-2">
                <div class="bg-white rounded-lg p-4">
                    <img src="" alt="">
                    <p>mfemfamfefmapofemfp</p>
                </div>
                <div class="bg-white rounded-lg p-4">
                    
                </div>
                <div class="bg-white rounded-lg p-4">
                    
                </div>
            </div>
        </div> -->
    </section>

</template>

<script setup>
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select';
import { Input } from '@/Components/ui/input'
</script>