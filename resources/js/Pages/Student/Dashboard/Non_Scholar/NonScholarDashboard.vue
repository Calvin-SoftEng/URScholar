<template>
    <div class="flex flex-col gap-2 w-full h-1/12 justify-center items-center">
        <span class="text-4xl font-bold">Tulong Dunong Program</span>
        <span class="text-xl">Grantee</span>
    </div>
    <!--Pending Req-->

    <div v-if="submitPending.length == total_subreq.length || (submitReq.length <= 0 && submitApproved == false)">
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
            <div class="relative flex flex-col items-center">
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
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 p-4 mt-4 shadow-sm">
            <h2 class="text-xl font-semibold">You're Done!</h2>
            <p class="mt-2">
            <p>Your application has been successfully submitted.</p>
            <p>If your application meets the criteria, it will be marked as approved.</p>
            <p>However, please note that approval does not guarantee passing.</p>
            <p>Once the evaluation is complete, the official list of passers will be announced.</p>
            <br>
            </p>
        </div>
    </div>




    <!--Req to-->
    <div v-if="submitReq != 0">
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
            <div class="relative flex flex-col items-center">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-dtext font-bold text-lg">
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

        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 p-4 mt-4 shadow-sm flex flex-col space-y-2">
            <span>From the Scholarship and Financial Assistance Unit:</span>
            <span>Message: </span>
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
    </div>





    <!--Approved-->

    <div v-if="submitApproved != 0 && applicant.status == 'Pending'">
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
            <div class="relative flex flex-col items-center">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold text-lg">
                    <font-awesome-icon :icon="['fas', 'check']" class="text-white" />
                </div>
                <span class="mt-2 text-sm text-gray-700">Under Review</span>
            </div>


            <!-- Line -->
            <div class="w-16 h-1 bg-gray-300 relative -top-4"></div>

            <!-- Step 3 -->
            <div class="relative flex flex-col items-center">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold text-lg">
                    <font-awesome-icon :icon="['fas', 'check']" class="text-white" />
                </div>
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
        <div class="bg-green-100 border-l-4 border-green-500 text-green-900 p-4 mt-4 shadow-sm">
            <h2 class="text-xl font-semibold">Application Approved</h2>
            <p class="mt-2">
            <p>Your application has been reviewed and approved, and all requirements have been verified.</p>
            <p>This means you have moved forward in the evaluation process.</p>
            <p>Please note that approval does not automatically mean qualification.</p>
            <p>Final results will be released once the evaluation and ranking are completed.</p>
            </p>
        </div>
    </div>





    <!--Isa ka ng ganap na grantee-->
    <div v-if="applicant.status == 'Approved'">
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
            <div class="relative flex flex-col items-center">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold text-lg">
                    <font-awesome-icon :icon="['fas', 'check']" class="text-white" />
                </div>
                <span class="mt-2 text-sm text-gray-700">Under Review</span>
            </div>


            <!-- Line -->
            <div class="w-16 h-1 bg-gray-300 relative -top-4"></div>

            <!-- Step 3 -->
            <div class="relative flex flex-col items-center">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold text-lg">
                    <font-awesome-icon :icon="['fas', 'check']" class="text-white" />
                </div>
                <span class="mt-2 text-sm text-gray-700">Approved</span>
            </div>


            <!-- Line -->
            <div class="w-16 h-1 bg-gray-300 relative -top-4"></div>

            <!-- Step 4 -->
            <div class="relative flex flex-col items-center">
                <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold text-lg">
                    <font-awesome-icon :icon="['fas', 'check']" class="text-white" />
                </div>
                <span class="mt-2 text-sm text-gray-700">Qualification</span>
            </div>
        </div>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-900 p-4 mt-4 shadow-sm">
            <h2 class="text-xl font-semibold">You're Qualified!</h2>
            <p class="mt-2">
            <p>Congratulations! You have officially qualified for the scholarship.</p>
            <p>Your application and documents have been verified and approved.</p>
            <p>Please wait for further announcements regarding the schedule and process of the payout.</p>
            <p>Make sure to download your qr code to avoid delays in claiming your grant.</p>
            </p>
        </div>
    </div>
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
    applicant: Object,
    total_subreq: Array,

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