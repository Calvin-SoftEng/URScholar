<template>

    <Head title="Scholarships" />
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li class="hover:text-gray-600">
                        <li>
                            <span class="text-blue-400 font-semibold dark:text-gray-300">Scholars</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="w-full mx-auto px-28 space-y-3">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                        <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>My
                        Scholars
                    </h1>
                </div>

                <div class="mx-auto py-5">
                    <div class="flex w-full flex-col gap-6">

                        <div>
                            <span class="font-poppins text-sm">Filter Campus</span>
                            <select 
                                class="p-2.5 text-sm border border-gray-200 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="">All
                                    Campuses</option>
                                <option>
                                    nfieafaef
                                </option>
                            </select>
                        </div>

                        <ScholarList :scholarships="props.scholarships" :sponsors="props.sponsors" />


                    </div>
                </div>


            </div>
        </div>
        
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, computed } from 'vue';
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { useRouter, useRoute } from 'vue-router'
import Echo from 'laravel-echo';
import ScholarList from '@/Components/Sponsor/Scholars/ScholarList.vue';

import { Tooltip } from 'primevue';

import { Button } from '@/Components/ui/button'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'

const props = defineProps({
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

// const date_end = new Date(props.scholarships.date_end).toLocaleDateString("en-US", {
//     year: "numeric",
//     month: "long",
//     day: "numeric"
// });


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

</script>

<style scoped>
.p-tooltip-text {
    margin-left: 0px;
    font-size: 13px !important;
}
</style>