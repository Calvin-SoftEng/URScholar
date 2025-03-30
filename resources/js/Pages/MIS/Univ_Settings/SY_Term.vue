<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite p-6 h-full w-full space-y-10">
            <div>
                <div>
                    <h1 class="text-2xl font-bold mb-5">School year</h1>
                </div>

                <div v-if="scholar_year?.length">
                    <div v-for="year in scholar_year" :key="year.id"
                        class="w-full h-full flex flex-col p-10 mt-5 gap-2 bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 font-kanit text-white rounded-xl">

                        <span v-if="year.year" class="items-center justify-center font-bold text-3xl">
                            {{ year.year }}
                        </span>
                        <span v-else class="text-red-500 font-bold text-3xl">Year Not Available</span>

                        <div v-if="year.academic_year?.length">
                            <div v-for="academic in year.academic_year" :key="academic.id">
                                <h1 class="font-normal text-sm">
                                    Semester: {{ academic.semester ?? 'No Semester Assigned' }}
                                </h1>
                            </div>
                        </div>
                        <div v-else>
                            <h1 class="font-normal text-sm text-yellow-400">No Academic Year Available</h1>
                        </div>

                        <h1 class="font-normal text-sm">Current Academic Year</h1>
                    </div>
                </div>
                <div v-else class="text-center text-gray-400">
                    <h1 class="text-lg">No School Years Available</h1>
                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import CalendarPicker from "@/Components/MIS/CalendarPicker.vue";

const selectedDate = ref({ month: "", day: "" });
const props = defineProps({
    scholar_year: Array,
});


</script>