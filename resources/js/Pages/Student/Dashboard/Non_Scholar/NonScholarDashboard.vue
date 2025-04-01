<template>
        <div class="flex flex-col gap-2 w-full h-1/12 justify-center items-center">
            <span class="text-4xl font-bold">Tulong Dunong Program</span>
            <span class="text-xl">Grantee</span> 
        </div>
        <div class="flex items-center justify-center my-8 ">
            <!-- Step 1 -->
            <div class="relative flex flex-col items-center">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold text-lg">
                    <font-awesome-icon :icon="['fas', 'check']" class="text-white" />
                </div>
                <span class="mt-2 text-sm text-gray-700">Application</span>
            </div>

            <!-- Line -->
            <div class="w-16 h-1 bg-primary relative -top-4"></div>

            <!-- Step 2 -->
            <div 
            class="relative flex flex-col items-center">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">
                    2</div>
                <span class="mt-2 text-sm text-gray-700">Under Review</span>
            </div>


            <!-- Line -->
            <div  class="w-16 h-1 bg-gray-300 relative -top-4"></div>

            <!-- Step 3 -->
            <div 
            class="relative flex flex-col items-center">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">
                    3</div>
                <span class="mt-2 text-sm text-gray-700">Approved</span>
            </div>

            
            <!-- Line -->
            <div  class="w-16 h-1 bg-gray-300 relative -top-4"></div>

            <!-- Step 4 -->
            <div 
            class="relative flex flex-col items-center">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">
                    4</div>
                <span class="mt-2 text-sm text-gray-700">Qualification</span>
            </div>
        </div>

        <!-- first stepper -->

        <div class="bg-dirtywhite w-full p-3 flex flex-col font-popins text-xl">
            <h1>You're Done!</h1>
            <p>Your application has been successfully submitted.</p>
            <p>If your application meets the criteria, it will be marked as approved.</p>
            <p>However, please note that approval does not guarantee passing.</p>
            <p>Once the evaluation is complete, the official list of passers will be announced.</p>
            <br>
        </div>

        <div class="bg-dirtywhite w-full p-3 flex flex-col font-popins text-xl">
            <div class="bg-green-100 p-3 rounded-lg text-green-800 font-semibold">
                <h2>Congratulations! You Are Officially Qualified!</h2>
                <p>You have successfully passed the evaluation process.</p>
                <p>Further instructions will be provided soon.</p>
            </div>
        </div>


        <!-- second stepper -->

        <!-- one time -->
        <!-- <template v-if="scholar">
            <div class="mb-3">
                <span class="text-2xl font-medium font-poppins">My Scholarship</span>
            </div>

            <div class="bg-dirtywhite w-full p-6 flex flex-col font-poppins text-xl space-y-6 text-primary">

                <div class="text-center">
                    <span class="text-4xl font-bold">Tulong Dunong Program</span>
                    <p class="text-xl text-gray-600">Grantee</p>
                </div>

                <div class="h-0.5 bg-gray-300"></div>


                <div>
                    <span class="font-semibold text-xl">Payout History</span>

                    <div class="max-w-6xl mx-auto space-y-6 mt-4">
                        <div class="grid grid-cols-5 gap-4 items-center">

                            <div class="col-span-1 flex items-center justify-center text-primary font-bold">
                                Claimed
                            </div>


                            <div class="col-span-4 bg-white shadow-md p-4 rounded-lg">
                                <h2 class="text-lg font-semibold">March 30, 2025</h2>
                                <p class="text-gray-600">Claimed by: <span class="font-medium">John Doe</span>, ID: 123456</p>
                                <p class="text-gray-600">URSB Cashier</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-0.5 bg-gray-300"></div>


                <div class="text-center">
                    <p class="text-lg text-gray-700">
                        🎉 Congratulations on receiving your scholarship! We hope this support helps you achieve your academic goals.
                        Your journey doesn’t end here—keep striving for excellence, and remember that new opportunities await! 🚀
                    </p>
                </div>


                <div class="flex justify-center">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-300">
                        Continue on Your Next Journey
                    </button>
                </div>
            </div>
        </template> -->

</template>

<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    //For scholars only
    scholar: Object,
    scholarship: Object,
    submitReq: Array,

    //For non-scholars only
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

const getSponsorDetails = (sponsorId) => {
    return props.sponsors.find(s => s.id === sponsorId) || { name: 'Unknown Sponsor' };
};

</script>