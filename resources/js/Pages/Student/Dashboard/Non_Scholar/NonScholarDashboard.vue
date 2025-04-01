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

        

        <!-- third stepper -->

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