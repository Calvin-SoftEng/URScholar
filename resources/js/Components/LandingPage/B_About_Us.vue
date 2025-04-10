<template>
    <section class="relative py-10 px-6 sm:py-12 sm:px-12 md:py-16 md:px-20 lg:py-20 lg:px-40 xl:py-24 xl:px-60 h-fit items-center bg-dsecondary">
        <div class="flex flex-col text-left space-y-12 sm:space-y-16 justify-center h-full max-w-5xl mx-auto">
            
            <!-- About Us Heading -->
            <div class="sm:text-center text-right flex flex-col space-y-6 sm:space-y-10">
                <span class="bg-gradient-to-r from-[#0D3B80] via-white to-[#296fd6] bg-clip-text text-transparent font-sora lg:text-right sm:text-center sm:text-3xl lg:text-7xl font-bold">
                    About Our System
                </span>
                <p class="text-justify text-base sm:text-lg md:text-2xl text-white max-w-5xl mx-auto font-medium font-albert leading-relaxed sm:leading-tight">
                    URScholar is a cloud-based integrated scholarship portal designed to streamline scholarship applications, 
                    enhance transparency, and improve the overall student financial aid experience. Our system ensures that 
                    every deserving student gets the support they need efficiently and fairly.
                </p>
            </div>

            <!-- How to Get Started -->
            <div class="space-y-4 sm:space-y-6">
                <span class="text-2xl sm:text-3xl md:text-4xl font-semibold font-sora bg-gradient-to-r from-white via-blue-300 to-[#0D3B80] bg-clip-text text-transparent">
                    How to Get Started?
                </span>

                <!-- Steps Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8 text-primary justify-items-center w-full">
                    
                    <!-- Step 1 -->
                    <div class="bg-white p-6 rounded-xl shadow-md w-full sm:max-w-xs transition-all transform hover:scale-[1.02] text-left">
                        <span class="font-medium text-xl sm:text-2xl block mb-2">Create A URScholar Account</span>
                        <p class="font-light text-gray-600 text-sm sm:text-base">
                            Create a URScholar account to save your personal scholarship results. Fill out future scholarships fast!
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white p-6 rounded-xl shadow-md w-full sm:max-w-xs transition-all transform hover:scale-[1.02] text-left">
                        <span class="font-medium text-xl sm:text-2xl block mb-2">Complete Your Scholarship Profile</span>
                        <p class="font-light text-gray-600 text-sm sm:text-base">
                            Set up your personal, education, family, and other information. Complete your profile to see your personal scholarship account.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white p-6 rounded-xl shadow-md w-full sm:max-w-xs transition-all transform hover:scale-[1.02] text-left">
                        <span class="font-medium text-xl sm:text-2xl block mb-2">Apply for Scholarships</span>
                        <p class="font-light text-gray-600 text-sm sm:text-base">
                            Start applying to scholarships in your personal results. Don’t forget to check your account regularly for more updates!
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>



</template>

<script setup>
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select';
import { Input } from '@/Components/ui/input'
</script>