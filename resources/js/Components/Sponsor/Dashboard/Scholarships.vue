<template>
    <div class="space-y-5">

        <div class="flex flex-row justify-between items-center">
            <span class="font-poppins font-semibold text-xl dark:text-dtext">Scholarships</span>

            <!-- Other content of your card goes here -->
            <!-- expand -->

            <!-- :href="route('sponsor.scholarship')" -->
        </div>

        <div class="mx-auto py-5">
                    <div class="flex w-full flex-col gap-6">

                        <!-- Need-Based Scholarships -->
                        <h2
                            class="text-xl font-semibold text-gray-700 dark:text-dtext flex items-center gap-3 before:flex-1 before:border-t before:border-gray-300 after:flex-1 after:border-t after:border-gray-300">
                            Grant-Based Scholarships
                        </h2>

                        <button v-for="scholarship in grantBasedScholarships" :key="scholarship.id"
                            @click="toggleSpecification(scholarship)" class="w-full">

                            <div class="relative border rounded-lg bg-white dark:bg-dcontainer dark:border-gray-600 
                                hover:shadow-md transition-all duration-300 py-5 px-10 flex flex-col md:flex-row 
                                justify-between items-start md:items-center space-y-5 md:space-y-0">

                                <!-- Notification Badge -->
                                <span v-if="scholarship.read !== 1"
                                    class="absolute top-[-13px] right-2 bg-primary text-white text-sm font-bold px-5 py-1 rounded-full">
                                    New Scholarship
                                </span>

                                <!-- Scholarship Info -->
                                <div class="space-y-4 flex flex-col items-start justify-start">
                                    <div class="badge badge-info text-xs badge-outline px-3 py-1">
                                        {{ getSponsorName(scholarship.sponsor_id) }}
                                    </div>

                                    <h2
                                        class="w-full items-start text-3xl md:text-2xl font-semibold text-gray-900 dark:text-dtext">
                                        {{ scholarship.name }}
                                    </h2>

                                    <p class="flex flex-col text-sm text-gray-500 items-start space-y-1">
                                        <span class="items-start">Created on: {{ new
                                            Date(scholarship.created_at).toLocaleDateString() }}</span>
                                        <span class="items-start">Sponsoring Since:
                                            {{ new Date(scholarship.created_at).toLocaleDateString('en-US', {
                                                year:
                                                    'numeric', month: 'long', day: 'numeric'
                                            }) }}
                                        </span>
                                    </p>

                                    <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                        <span class="font-medium">Requirements Deadline:</span>
                                        <span
                                            v-if="scholarship.requirements && scholarship.requirements.length > 0 && scholarship.requirements[0].date_end">
                                            {{ new
                                                Date(scholarship.requirements[0].date_end).toLocaleDateString('en-US', {
                                                    year: 'numeric', month: 'long', day: 'numeric'
                                                }) }}
                                        </span>
                                        <span v-else>
                                            No Deadline
                                        </span>
                                    </p>
                                </div>

                                <!-- Additional Info -->
                                <div class="flex flex-row gap-6">
                                    <div class="flex flex-col items-center">
                                        <span class="text-gray-500 text-sm">Batches</span>
                                        <span class="text-lg font-semibold text-gray-800 dark:text-dtext">34</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-gray-500 text-sm">Campuses</span>
                                        <span class="text-lg font-semibold text-gray-800 dark:text-dtext">2</span>
                                    </div>
                                </div>

                            </div>

                        </button>

                        <!-- One-Time Payment Scholarships -->
                        <h2
                            class="text-xl font-semibold text-gray-700 dark:text-dtext flex items-center gap-3 before:flex-1 before:border-t before:border-gray-300 after:flex-1 after:border-t after:border-gray-300">
                            One-Time Payment Scholarships
                        </h2>

                        <template v-if="oneTimeScholarships.length > 0">
                            <button v-for="scholarship in oneTimeScholarships" :key="scholarship.id"
                                @click="toggleSpecification(scholarship)" class="w-full">

                                <div class="relative border rounded-lg bg-white dark:bg-dcontainer dark:border-gray-600 
                                    hover:shadow-md transition-all duration-300 py-5 px-10 flex flex-col md:flex-row 
                                    justify-between items-start md:items-center space-y-5 md:space-y-0">

                                    <!-- Notification Badge -->
                                    <span
                                        class="absolute top-[-13px] right-2 bg-primary text-white text-sm font-bold px-5 py-1 rounded-full flex items-center gap-2">
                                        <font-awesome-icon :icon="['fas', 'bell']" class="text-base" />
                                        New Activities
                                    </span>


                                    <!-- Scholarship Info -->
                                    <div class="space-y-4 flex flex-col items-start justify-start">
                                        <div class="badge badge-info text-xs badge-outline px-3 py-1">
                                            {{ getSponsorName(scholarship.sponsor_id) }}
                                        </div>

                                        <h2
                                            class="w-full items-start text-3xl md:text-2xl font-semibold text-gray-900 dark:text-dtext">
                                            {{ scholarship.name }}
                                        </h2>

                                        <p class="flex flex-col text-sm text-gray-500 items-start space-y-1">
                                            <span class="items-start">Created on: {{ new
                                                Date(scholarship.created_at).toLocaleDateString() }}</span>
                                            <span class="items-start">Sponsoring Since:
                                                {{ new Date(scholarship.created_at).toLocaleDateString('en-US', {
                                                    year:
                                                        'numeric', month: 'long', day: 'numeric'
                                                }) }}
                                            </span>
                                        </p>

                                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                            <span class="font-medium">Requirements Deadline:</span>
                                            <span
                                                v-if="scholarship.requirements && scholarship.requirements.length > 0 && scholarship.requirements[0].date_end">
                                                {{ new
                                                    Date(scholarship.requirements[0].date_end).toLocaleDateString('en-US', {
                                                        year: 'numeric', month: 'long', day: 'numeric'
                                                    }) }}
                                            </span>
                                            <span v-else>
                                                No Deadline
                                            </span>
                                        </p>
                                    </div>

                                    <!-- Additional Info -->
                                    <div class="flex flex-row gap-6">
                                        <div class="flex flex-col items-center">
                                            <span class="text-gray-500 text-sm">Batches</span>
                                            <span class="text-lg font-semibold text-gray-800 dark:text-dtext">34</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="text-gray-500 text-sm">Campuses</span>
                                            <span class="text-lg font-semibold text-gray-800 dark:text-dtext">2</span>
                                        </div>
                                    </div>

                                </div>
                            </button>
                        </template>
                        <div v-else class="text-gray-500 text-center py-4">No available scholarships.</div>

                    </div>
                </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, computed, onUnmounted } from 'vue';
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { useRouter, useRoute } from 'vue-router'
import Echo from 'laravel-echo';

import { Tooltip } from 'primevue';

import { Button } from '@/Components/ui/button'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'

const props = defineProps({
    sponsor: {
        type: Object,
        required: true
    },
    scholarships: {
        type: Array,
        required: true
    },
    schoolyears: {
        type: Array,
        required: true
    },
});

onMounted(() => {
    const echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        authEndpoint: "/broadcasting/auth", // Required for private channels
    });

    // Listen for general notifications
    echo.channel('general-notifications')
        .listen('GeneralNotification', (event) => {
            // Check if this is a scholarship read notification
            if (event.type === 'scholarship_read' &&
                event.data.scholarship_id === scholarship.value.id) {
                scholarship.value.read = event.data.read
            }
        })
})

// Set up real-time messaging using Laravel Echo
// onMounted(() => {

//     const echo = new Echo({
//         broadcaster: 'pusher',
//         key: import.meta.env.VITE_PUSHER_APP_KEY,
//         cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//         forceTLS: true,
//         authEndpoint: "/broadcasting/auth", // Required for private channels
//     });

//     echo.private(`chat.${props.selectedScholarship.id}`) // Use private channel
//         .listen('.message.sent', (e) => {
//             fetchMessages(); // Fetch messages after receiving
//             scrollToBottom();
//             messages.value.push(e.message); // Append new message
//         });
// });


const directives = {
    Tooltip,
};

const grantBasedScholarships = computed(() =>
    props.scholarships.filter(scholarship => scholarship.scholarshipType === 'Grant-Based')
);

const oneTimeScholarships = computed(() =>
    props.scholarships.filter(scholarship => scholarship.scholarshipType === 'One-time Payment')
);

const ScholarshipSpecification = ref(false);

const form = ref({
    id: null,
    name: '',
    description: '',
    sponsor_id: ''
});

const getSponsorName = (sponsorId) => {
    // Since you only have the current sponsor in props.sponsor, 
    // we check if the sponsorId matches the current sponsor's id
    return props.sponsor && props.sponsor.id === sponsorId 
        ? props.sponsor.name 
        : 'Unknown Sponsor';
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};

const selectedYear = ref("");
const selectedSem = ref("");

const selectedScholarship = ref(null);

const formErrors = ref({
    selectedSem: "",
    selectedYear: "",
});

const toggleSpecification = (Scholarship) => {
    ScholarshipSpecification.value = !ScholarshipSpecification.value;

    if (ScholarshipSpecification.value) {
        selectedScholarship.value = Scholarship;
    }
    resetForm();
};

const date_end = new Date(props.scholarships.date_end).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric"
});


const openScholarship = () => {
    formErrors.value.selectedSem = "";
    formErrors.value.selectedYear = "";

    // Validate
    if (!selectedSem.value && !selectedYear.value) {
        formErrors.value.selectedSem = "Semester selection is required.";
        formErrors.value.selectedYear = "Academic Year selection is required.";
        return; // Stop form submission
    }

    const formData = new FormData();
    formData.append("selectedYear", selectedYear.value);
    formData.append("semester", selectedSem.value); // Make sure it's being passed


    router.visit(`/scholarships/${selectedScholarship.value.id}`, {
        data: { selectedYear: selectedYear.value, selectedSem: selectedSem.value },
        preserveState: true
    });
};


const closeModal = () => {
    ScholarshipSpecification.value = false;
    selectedScholarship.value = null;
    selectedYear.value = "";
    selectedSem.value = "";
    formErrors.value.selectedSem = "";
    formErrors.value.selectedYear = "";
    resetForm();
};

const resetForm = () => {
    form.value = {
        id: null,
        name: '',
        description: '',
        sponsor_id: '',
        selectedYear: null,
        selectedSem: null,
    };
};

onMounted(() => {
    window.addEventListener('popstate', () => {
        window.location.reload();
    });
});

onUnmounted(() => {
    window.removeEventListener('popstate', () => {
        window.location.reload();
    });
});

</script>

<style scoped>
.p-tooltip-text {
    margin-left: 0px;
    font-size: 13px !important;
}
</style>