<template>
    <template v-if="!disbursement && !isDashboard">
        <div class="flex flex-col gap-2 w-full h-1/12 justify-center items-center">
            <span class="text-4xl font-bold sm:text-center">Tulong Dunong Program</span>
            <span class="text-xl">Grantee</span>
        </div>
        <div class="flex items-center justify-center my-8 ">
            <!-- Step 1 -->
            <div class="relative flex flex-col items-center">
                <div
                    class="sm:w-8 sm:h-8 lg:w-10 lg:h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold sm:text-sm lg:text-lg">
                    <font-awesome-icon :icon="['fas', 'check']" class="text-white" />
                </div>
                <span class="mt-2 sm:text-xs lg:text-sm text-gray-700">Application</span>
            </div>

            <!-- Line -->
            <div class="sm:w-8 lg:w-16 h-1 bg-primary relative sm:-top-3 lg:-top-4"></div>

            <!-- Step 2 -->
            <div v-if="submitReq != 0 || submitPending != 0" class="relative flex flex-col items-center">
                <div
                    class="sm:w-8 sm:h-8 lg:w-10 lg:h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold sm:text-sm lg:text-lg">
                    2</div>
                <span class="mt-2 sm:text-xs lg:text-sm whitespace-nowrap text-gray-700">Under Review</span>
            </div>

            <div v-if="submitApproved != 0" class="relative flex flex-col items-center">
                <div
                    class="sm:w-8 sm:h-8 lg:w-10 lg:h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold sm:text-sm lg:text-lg">
                    <font-awesome-icon :icon="['fas', 'check']" class="text-white" />
                </div>
                <span class="mt-2 sm:text-xs lg:text-sm whitespace-nowrap text-gray-700">Under Review</span>
            </div>

            <!-- Line -->
            <div v-if="submitReq != 0 || submitPending != 0"
                class="sm:w-8 lg:w-16 h-1 bg-gray-300 relative sm:-top-3 lg:-top-4"></div>
            <div v-if="submitApproved != 0" class="sm:w-8 lg:w-16 h-1 bg-primary relative sm:-top-3 lg:-top-4"></div>

            <!-- Step 3 -->
            <div v-if="submitReq != 0 || submitPending != 0" class="relative flex flex-col items-center">
                <div
                    class="sm:w-8 sm:h-8 lg:w-10 lg:h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold sm:text-sm lg:text-lg">
                    3</div>
                <span class="mt-2 sm:text-xs lg:text-sm whitespace-nowrap text-gray-700">Approved</span>
            </div>

            <div v-if="submitApproved != 0" class="relative flex flex-col items-center">
                <div
                    class="sm:w-8 sm:h-8 lg:w-10 lg:h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold sm:text-sm lg:text-lg">
                    <font-awesome-icon :icon="['fas', 'check']" class="text-white" />
                </div>
                <span class="mt-2 sm:text-xs lg:text-sm whitespace-nowrap text-gray-700">Approved</span>
            </div>
        </div>

        <!-- first stepper -->

        <div v-if="submitPending != 0" class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 p-4 mt-4 shadow-sm">
            <h2 class="text-xl font-semibold">Congratulations!</h2>
            <p class="mt-2">
            <p>Your application has been successfully completed.</p>
            <p>You will be notified about the next steps soon.</p>
            <br>
            <p>For now kindly update and upload your grades to the system by navigativing to the profile then education
                section. Thankyou!</p>
            <p></p>
            </p>
        </div>

        <!-- second stepper -->

        <div v-else-if="submitReq != 0"
            class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 p-4 mt-4 shadow-sm flex flex-col space-y-2">
            <span>From Maam Anorn:</span>
            <span>Message</span>
            <p>It is noted, however, that among the requirements you have submittted to DBP, the
                following documents must be
                resubmitted.</p>
            <br>
            <span>Message nung nasa return: Ex anlabo pre</span>
            <br>
            <span>Deadline</span>
            <br>
            <form @submit.prevent="submitRequirements" class="space-y-6">
                <div v-for="req in returnedRequirements" :key="req.id" class="bg-white border rounded-lg shadow-sm p-4">
                    <h3 class="font-medium text-gray-900">{{ req.requirement_name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">Return reason: {{ req.return_message }}</p>

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

        <!-- third stepper -->

        <div v-if="submitApproved != 0" class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 p-4 mt-4 shadow-sm">
            <h2 class="text-xl font-semibold">Congratulations!</h2>
            <p class="mt-2">
            <p class="text-gray-700 mt-2">Your application has been successfully completed.</p>
            <p class="text-gray-600">You will be notified about the payout announcement soon.</p>
            </p>

            <!-- Encouragement to Stay Updated -->
            <div class="mt-6">
                <button @click="goToDashboard" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg">
                    Continue to Dashboard
                </button>
            </div>
        </div>

    </template>


    <!-- scholar grant kapag tapos na mag pasa requiremetns -->
    <template v-if="isDashboard">
        <div class="mb-3 p-1">
            <span class="text-2xl font-medium font-poppins">My Scholarship</span>
        </div>

        <div class="bg-dirtywhite w-full sm:p-2 lg:p-6 flex flex-col font-poppins text-xl space-y-10 text-primary">

            <!-- Scholarship Details -->
            <div class="bg-white shadow-md p-6 rounded-lg text-center">
                <div class="flex flex-col sm:flex-row items-center justify-center space-x-4">
                    <!-- Logo -->
                    <img src="../../../../../assets/images/CHED.png" alt="CHED Logo" class="w-16 h-16 object-contain mb-4 sm:mb-0">

                    <!-- Scholarship Info -->
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-blue-800">{{ scholarship.name }}</h2>
                        <p class="text-lg sm:text-xl text-gray-600">{{ oldestGrantee.school_year.year }} Grantee</p>
                    </div>
                </div>

                <div class="h-0.5 bg-gray-300 my-4"></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left text-gray-700">
                    <p><span class="font-semibold">Current Semester:</span> {{ grantee.school_year.year }} - {{ grantee.semester }} Sem</p>
                    <p><span class="font-semibold">Status:</span> <span class="text-green-600 font-bold">{{ grantee.status }}</span></p>
                </div>
            </div>

            <!-- Payout Announcement Card (Only shown if there's a schedule) -->
            <div v-if="!payout_schedule || disbursement.status === 'Claimed'"
                class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 p-4 mt-4 shadow-sm">
                <h2 class="text-xl font-semibold">Upcoming Payout Schedule</h2>
                <p class="mt-2">
                    <span class="font-bold">Next payout schedule will be announced soon</span>.
                    Stay updated for further announcements.
                </p>
            </div>
            <div v-else class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 p-4 mt-4 shadow-sm">
                <h2 class="text-xl font-semibold">Payout Schedule</h2>
                <p class="mt-2">
                    Your next payout is expected on
                    <span class="font-bold">{{ formattedDate }} at {{ formattedTime }}</span>.
                    Stay updated for further announcements.
                </p>
                <p class="mt-2">
                    <span class="font-bold">Reminders:</span>
                    <br>
                    <span v-html="formattedReminders"></span>
                </p>
            </div>


            <!-- kapag may new requirmeents -->

            <div v-if="fefamefi"
                class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 p-4 mt-4 shadow-sm space-y-3">
                <h2 class="text-xl font-semibold">Documents must be submitted</h2>
                <div class="border rounded-lg p-3 bg-white shadow-sm w-full max-w-xl">
                    <!-- Header -->
                    <div class="flex justify-between items-center gap-5">
                        <div class="flex items-center space-x-2">
                            <span class="bg-yellow-400 text-black font-bold px-2 py-1 rounded">
                                {{ String.fromCharCode(65 + index) }}
                            </span>
                            <span class="font-semibold text-gray-800">
                                {{ requirement.requirements }}
                            </span>
                        </div>
                        <label class="bg-blue-900 text-white px-3 py-1 rounded cursor-pointer text-sm">
                            Add File
                            <input type="file" class="hidden"
                                @change="(e) => handleFile(e, requirement.id, requirement.requirements)"
                                :id="'file_input_' + requirement.id" />
                        </label>
                    </div>

                    <!-- Uploaded File Preview -->
                    <div
                        class="border border-dashed border-purple-400 rounded-lg p-2 mt-2 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <img src="https://img.icons8.com/ios-filled/50/000000/pdf.png" class="w-6 h-6"
                                alt="PDF Icon">
                            <div>
                                <p class="text-sm font-medium">
                                    {{ form.files[requirement.id].name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ form.files[requirement.id].size }}
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="removeFile(requirement.id)"
                            class="ml-2 text-red-600 hover:text-red-800">
                            Remove
                        </button>

                        <!-- <div v-if="form.errors[`files.${requirement.id}`]"
                            class="text-red-500 text-sm">
                            {{ form.errors[`files.${requirement.id}`] }}
                        </div> -->
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing || !isFormValid" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none
                            focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 
                            transition-all duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed
                            flex items-center justify-center gap-2">
                        <span class="material-symbols-rounded">outbox</span>
                        <span>{{ form.processing ? 'Submitting...' : 'Submit' }}</span>
                    </button>
                </div>

            </div>

            <div class="h-0.5 bg-gray-300"></div>

            <!-- Payout History -->
            <div>
                <div class="flex flex-row justify-between items-center">
                    <span class="font-semibold text-xl">Payout History</span>
                    <!-- <span class="font-normal text-base">Show all</span> -->
                </div>

                <div class="max-w-6xl mx-auto space-y-6 mt-4">
                    <div v-if="filteredHistoryGrantee.length === 0">
                        <div class="grid grid-cols-5 gap-4 items-center">
                            <div class="col-span-5 flex items-center justify-center text-primary font-bold">
                                NO history yet
                            </div>
                        </div>
                    </div>

                    <div v-else>
                        <div v-for="history in filteredHistoryGrantee" :key="history.id"
                            class="grid grid-cols-5 gap-4 items-center">
                            <div class="col-span-5 gap-2 relative w-full flex items-center mt-2 whitespace-nowrap">
                                <h3 class="font-semibold text-base text-blue-900 dark:text-white">
                                    {{ history.semester }} Semester - {{ history.school_year }}
                                </h3>
                                <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                            </div>

                            <div class="col-span-1 flex items-center justify-center text-primary font-bold">
                                {{ history.dibursement_status }}
                            </div>

                            <div class="col-span-4 bg-white shadow-md p-4 rounded-lg">
                                <h2 class="text-lg font-semibold">{{ history.claimed_at ? new
                                    Date(history.claimed_at).toLocaleDateString('en-US', {
                                        year: 'numeric', month: 'long', day: 'numeric'
                                    }) : 'Not yet claimed' }}</h2>
                                <p class="text-gray-600" v-if="history.claimed_by">Claimed by: <span
                                        class="font-medium">{{ history.claimed_by.first_name }}</span>,
                                    ID: URSB123</p>
                                <p class="text-gray-600" v-if="history.claimed_at">Processed at: sa cashier</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Encouragement & Next Steps -->
            <div class="bg-white shadow-md p-6 rounded-lg text-center">
                <h2 class="text-2xl font-bold text-blue-800">Keep Going!</h2>
                <p class="text-gray-600 mt-2">
                    "Your hard work is paying off! Keep maintaining your academic performance and complete your
                    requirements on time to stay eligible for your next grant."
                    <br>
                    Update your GWA and upload corresponding documents to renew your scholarship.
                </p>
                <!-- <div class="mt-4">
                    <button class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg">
                        View Renewal Requirements
                    </button>
                </div> -->
            </div>

            <!-- Scholarship Progress -->
            <!-- <div class="bg-white shadow-md p-6 rounded-lg">
                <h2 class="text-xl font-semibold text-blue-800">Scholarship Progress</h2>
                <div class="mt-3 flex flex-col space-y-2 text-gray-600">
                    <p>✔ Documents Submitted</p>
                    <p>✔ Payouts Received</p>
                    <p>🔲 Renewal Application Pending</p>
                </div>
            </div> -->

        </div>
    </template>

</template>




<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    //For scholars only
    scholar: Object,
    scholarship: Object,
    submitReq: Array,
    submitPending: Array,
    submitApproved: Array,
    disbursement: Object,
    grantee: Object,
    oldestGrantee: Object,
    historygrantee: Array,
    payout_schedule: Object,

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

const isDashboard = ref(false); // Tracks whether the button has been clicked

const goToDashboard = () => {
  isDashboard.value = true; // Update state to show the new template
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

// Format Date to "April 5, 2025"
const formattedDate = computed(() => {
    const date = new Date(props.payout_schedule.scheduled_date);
    return new Intl.DateTimeFormat("en-US", { month: "long", day: "numeric", year: "numeric" }).format(date);
});

// Format Time to "3:05 PM"
const formattedTime = computed(() => {
    const [hours, minutes] = props.payout_schedule.scheduled_time.split(":");
    const date = new Date();
    date.setHours(hours, minutes);
    return new Intl.DateTimeFormat("en-US", { hour: "numeric", minute: "2-digit", hour12: true }).format(date);
});

// Convert newlines (`\n`) to `<br>` for proper display
const formattedReminders = computed(() => {
    return props.payout_schedule.reminders.replace(/\n/g, "<br>");
});

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

// Create the computed property
const filteredHistoryGrantee = computed(() => {
    return props.historygrantee.filter(history =>
        history.dibursement_status !== 'Pending'
    );
});

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