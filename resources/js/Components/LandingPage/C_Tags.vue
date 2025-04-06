<template>
    <section class="relative py-10 px-6 sm:py-12 sm:px-16 md:py-20 md:px-24 lg:py-6 lg:px-20 xl:py-16 xl:px-40 h-fit my-auto items-center bg-dirtywhite">
        <div class="flex flex-col text-center sm:text-left space-y-10 sm:space-y-20 justify-center h-full max-w-8xl mx-auto">
            <div class="text-center sm:text-left flex flex-col space-y-5">
                <span class="bg-gradient-to-r from-[#0D3B80] to-[#296fd6] bg-clip-text text-transparent font-sora lg:text-center sm:text-center sm:text-3xl lg:text-7xl font-bold">
                    Unlock Educational Success
                </span>
                <p class="text-xl sm:text-2xl lg:text-3xl xl:text-4lg text-primary font-medium font-albert">
                    Access comprehensive database of scholarships <br class="hidden sm:block"> 
                    and stay updated with the latest announcements <br class="hidden sm:block"> 
                    to boost your educational journey
                </p>
            </div>

            <div class="flex justify-center">
                <!-- tags -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-10 gap-y-10 text-primary text-center justify-items-center">
                    <!-- Comprehensive Search -->
                    <div class="space-y-2 max-w-xs transition-all transform hover:scale-[1.02]">
                        <span class="flex items-center justify-center w-14 h-14 bg-white border border-gray-200 rounded-md shadow-md mx-auto mb-3">
                            <font-awesome-icon :icon="['fas', 'magnifying-glass']" class="text-primary text-2xl" />
                        </span>
                        <span class="font-medium text-2xl">Comprehensive Search</span>
                        <p class="font-light text-gray-600">Easily find scholarships that match your profile.</p>
                    </div>

                    <!-- Timely Updates -->
                    <div class="space-y-2 max-w-xs transition-all transform hover:scale-[1.02]">
                        <span class="flex items-center justify-center w-14 h-14 bg-white border border-gray-200 rounded-md shadow-md mx-auto mb-3">
                            <font-awesome-icon :icon="['fas', 'bell']" class="text-primary text-2xl" />
                        </span>
                        <span class="font-medium text-2xl">Timely Updates</span>
                        <p class="font-light text-gray-600">Stay informed with the latest scholarship information and deadlines.</p>
                    </div>

                    <!-- Easy Online Application -->
                    <div class="space-y-2 max-w-xs transition-all transform hover:scale-[1.02]">
                        <span class="flex items-center justify-center w-14 h-14 bg-white border border-gray-200 rounded-md shadow-md mx-auto mb-3">
                            <font-awesome-icon :icon="['fas', 'file-alt']" class="text-primary text-2xl" />
                        </span>
                        <span class="font-medium text-2xl">Easy Online Application</span>
                        <p class="font-light text-gray-600">Apply for scholarships online in just a few steps.</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-center">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-10 text-primary text-center justify-items-center">
                    <!-- Transparent Tracking -->
                    <div class="space-y-2 max-w-xs transition-all transform hover:scale-[1.02]">
                        <span class="flex items-center justify-center w-14 h-14 bg-white border border-gray-200 rounded-md shadow-md mx-auto mb-3">
                            <font-awesome-icon :icon="['fas', 'chart-line']" class="text-primary text-2xl" />
                        </span>
                        <span class="font-medium text-2xl">Transparent Tracking</span>
                        <p class="font-light text-gray-600">Monitor your application progress and payout status in real time.</p>
                    </div>

                    <!-- Fast Payout Notifications -->
                    <div class="space-y-2 max-w-xs transition-all transform hover:scale-[1.02]">
                        <span class="flex items-center justify-center w-14 h-14 bg-white border border-gray-200 rounded-md shadow-md mx-auto mb-3">
                            <font-awesome-icon :icon="['fas', 'wallet']" class="text-primary text-2xl" />
                        </span>
                        <span class="font-medium text-2xl">Fast Payout Notifications</span>
                        <p class="font-light text-gray-600">Get instant updates on your scholarship disbursements and transactions.</p>
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