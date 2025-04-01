<template>
    <section class="relative sm:py-12 sm:px-60 md:py-20 md:px-30 lg:py-6 lg:px-20 xl:py-16 xl:px-40 h-fit my-auto items-center bg-dsecondary">
        <div class="flex flex-col text-left space-y-16 justify-center h-full max-w-5xl mx-auto ">
            <!-- About Us Heading -->
            <div class="text-right flex flex-col space-y-10">
                <span class="bg-gradient-to-r from-[#0D3B80] via-white to-[#296fd6] bg-clip-text text-transparent font-sora text-7xl font-bold">About Our System</span>
                <p class="text-justify text-2xl text-white max-w-5xl mx-auto font-medium font-albert leading-tight">
                    URScholar is a cloud-based integrated scholarship portal designed to streamline scholarship applications,
                    enhance transparency, and improve the overall student financial aid experience. Our system ensures that
                    every deserving student gets the support they need efficiently and fairly.
                </p>
            </div>

            <div class="space-y-3">
                <span class="text-4xl font-semibold font-sora bg-gradient-to-r from-white via-blue-300 to-[#0D3B80] bg-clip-text text-transparent">How to get Started?</span>

                <!-- URSCHOALAR Account Steps -->
                <div class="flex justify-center">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-10 gap-y-10 text-primary justify-items-center w-full">
                        <!-- Create A URScholar Account -->
                        <div class="bg-white p-6 rounded-xl shadow-md max-w-xs transition-all transform hover:scale-[1.02] text-left">
                            <span class="font-medium text-2xl block mb-2">Create A URScholar Account</span>
                            <p class="font-light text-gray-600">Create a URScholar account to save your personal scholarship results. Fill out future scholarships fast!</p>
                        </div>

                        <!-- Complete Your Scholarship Profile -->
                        <div class="bg-white p-6 rounded-xl shadow-md max-w-xs transition-all transform hover:scale-[1.02] text-left">
                            <span class="font-medium text-2xl block mb-2">Complete Your Scholarship Profile</span>
                            <p class="font-light text-gray-600">Set up your personal, education, family, and other information. In only a few minutes, complete your scholarship profile to see your personal scholarship account.</p>
                        </div>

                        <!-- Apply for Scholarships -->
                        <div class="bg-white p-6 rounded-xl shadow-md max-w-xs transition-all transform hover:scale-[1.02] text-left">
                            <span class="font-medium text-2xl block mb-2">Apply for Scholarships</span>
                            <p class="font-light text-gray-600">Start applying to scholarships in your personal results. Don’t forget to regularly check your account for more updates!</p>
                        </div>
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