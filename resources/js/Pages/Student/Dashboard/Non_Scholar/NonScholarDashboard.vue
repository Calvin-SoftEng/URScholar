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
        <div v-if="submitReq != 0 || submitPending != 0" class="relative flex flex-col items-center">
            <div
                class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">
                2</div>
            <span class="mt-2 text-sm text-gray-700">Under Review</span>
        </div>


        <!-- Line -->
        <div class="w-16 h-1 bg-gray-300 relative -top-4"></div>

        <!-- Step 3 -->
        <div class="relative flex flex-col items-center">
            <div
                class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">
                3</div>
            <span class="mt-2 text-sm text-gray-700">Approved</span>
        </div>


        <!-- Line -->
        <div class="w-16 h-1 bg-gray-300 relative -top-4"></div>

        <!-- Step 4 -->
        <div class="relative flex flex-col items-center">
            <div
                class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">
                4</div>
            <span class="mt-2 text-sm text-gray-700">Qualification</span>
        </div>
    </div>

    <!-- first stepper -->

    <div v-if="submitPending != 0" class="bg-dirtywhite w-full p-3 flex flex-col font-popins text-xl">
        <h1>You're Done!</h1>
        <p>Your application has been successfully submitted.</p>
        <p>If your application meets the criteria, it will be marked as approved.</p>
        <p>However, please note that approval does not guarantee passing.</p>
        <p>Once the evaluation is complete, the official list of passers will be announced.</p>
        <br>
    </div>

    <!-- second stepper -->

    <div v-else-if="submitReq != 0"
        class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 p-4 mt-4 shadow-sm flex flex-col space-y-2">
        <span>From Maam Anorn:</span>
        <span>Message</span>
        <p>It is noted, however, that among the requirements you have submittted to DBP, the
            following documents must be
            resubmitted.</p>
        <span>Deadline {{ new Date(reqDeadline.date_end).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) }}</span>
        <br>
        <form @submit.prevent="submitRequirements" class="space-y-6">
            <div v-for="req in returnedRequirements" :key="req.id" class="bg-white border rounded-lg shadow-sm p-4">
                <h3 class="font-medium text-gray-900">{{ req.requirement_name }}</h3>
                <p class="text-sm text-gray-600 mt-1">Return reason: {{ req.message }}</p>

                <div class="mt-3">
                    <input type="file" @change="(e) => handleFile(e, req.id, req.requirement_name)"
                        :id="'file_' + req.id" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none hover:bg-gray-100" />

                    <div v-if="selectedFiles[req.id]" class="flex items-center gap-2 text-sm text-gray-600 mt-2">
                        <font-awesome-icon :icon="['fas', 'file']" />
                        <span>{{ selectedFiles[req.id] }}</span>
                        <button type="button" @click="removeFile(req.id)" class="text-red-600 hover:text-red-800">
                            <font-awesome-icon :icon="['fas', 'times']" />
                        </button>
                    </div>

                    <p v-if="form.errors[`files.${req.id}`]" class="mt-1 text-sm text-red-600">
                        {{ form.errors[`files.${req.id}`] }}
                    </p>
                </div>
            </div>


            <div v-if="returnedRequirements.length === 0" class="text-center py-8">
                <p class="text-gray-500">No returned requirements to resubmit.</p>
            </div>

            <div v-if="returnedRequirements.length > 0" class="flex justify-end">
                <button type="submit" :disabled="form.processing || Object.keys(form.files).length === 0"
                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none
            focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 
            transition-all duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span>{{ form.processing ? 'Submitting...' : 'Submit again' }}</span>
                </button>
            </div>
        </form>
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
    //For applicants only
    scholar: Object,
    scholarship: Object,
    submitReq: Array,
    submitPending: Array,
    submitApproved: Array,
    reqDeadline: Object,

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


// Track selected files
const selectedFiles = ref({});

// List of returned requirements
const returnedRequirements = computed(() => props.submitReq || []);

// Form state
const form = useForm({
    files: {},
    requirements: []
});


const handleFile = (event, reqId, requirementName) => {
    const file = event.target.files[0];
    if (file) {
        // Store file
        form.files[reqId] = file;
        selectedFiles.value[reqId] = file.name;

        // Add requirement if not exists
        const existingIndex = form.requirements.findIndex(r => r.id === reqId);
        if (existingIndex === -1) {
            form.requirements.push({
                id: reqId,
                requirement: requirementName
            });
        }
    }
};

const removeFile = (reqId) => {
    // Remove file
    delete form.files[reqId];
    delete selectedFiles.value[reqId];

    // Remove requirement from array
    form.requirements = form.requirements.filter(r => r.id !== reqId);

    // Reset file input
    const fileInput = document.getElementById(`file_${reqId}`);
    if (fileInput) {
        fileInput.value = '';
    }
};

// const handleFile = (event, requirementId, requirementName) => {
//     const file = event.target.files[0];
//     if (file) {
//         form.files[requirementId] = file;

//         // Update or add the requirement entry
//         const existingIndex = form.requirements.findIndex(r => r.id === requirementId);
//         if (existingIndex !== -1) {
//             form.requirements[existingIndex] = { id: requirementId, name: requirementName };
//         } else {
//             form.requirements.push({ id: requirementId, name: requirementName });
//         }
//     }
// };

// const removeFile = (requirementId) => {
//     delete form.files[requirementId];
//     form.requirements = form.requirements.filter(r => r.id !== requirementId);
// };

const submitRequirements = () => {
    if (Object.keys(form.files).length === 0) {
        alert('Please select at least one file before submitting.');
        return;
    }

    if (!confirm('Are you sure you want to resubmit these requirements?')) {
        return;
    }

    form.post('/student/application/re-upload', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            alert('Requirements resubmitted successfully!');
            form.reset();
            selectedFiles.value = {};
            window.location.reload();
        },
        onError: (errors) => {
            console.error('Upload errors:', errors);
            alert('There was an issue with the upload. Please try again.');
        }
    });
};

</script>