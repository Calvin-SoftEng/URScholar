<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout :branding="branding">
        <div class="bg-dirtywhite dark:bg-dprimary p-6 h-full w-full">
            <div>
                <h1 class="text-2xl font-bold mb-5 text-dprimary dark:text-dtext">Course List</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of the University's courses. You can add and edit courses for each campus.
            </p>

            <div class="w-full h-full flex flex-col px-5 mt-5">
                <!-- card -->
                <div v-for="campus in campuses" :key="campus.id"
                    class="bg-white dark:bg-dcontainer border border-gray-100 shadow-sm w-full block rounded-lg mb-3">
                    <div class="flex justify-between items-center p-5 border-b border-b-blue-100 border-1">
                        <div>
                            <img src="" alt="">
                            <span class="font-semibold font-sora text-lg dark:text-dtext">{{ campus.name }}</span>
                        </div>
                        <Link :href="`/system_admin/univ-settings/course/config/${campus.id}`">
                        <button
                            class="bg-white dark:text-dtext px-3 py-1 rounded-md border-gray-100 text-dprimary border hover:bg-gray-100 hover:text-dtext dark:border-gray-600 dark:bg-dprimary dark:hover:bg-primary">
                            Add Course
                        </button>
                        </Link>
                    </div>

                    <div class="w-full grid grid-cols-2 px-5 py-3 gap-2">
                        <div v-for="course in campus.courses" :key="course.id" class="font-instrument font-normal text-sm text-dprimary dark:text-dtext">
                            {{ course.abbreviation }} - {{ course.name }}
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    campuses: Array,
    courses: Array,
    branding: Object,
});


const isCoursesVisible = ref(false);

const toggleModal = () => {
    isCoursesVisible.value = !isCoursesVisible.value;
};

const cancel = () => {
    isCoursesVisible.value = !isCoursesVisible.value;
};

const closeModal = () => {
    isCoursesVisible.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = { id: null, name: '', description: '' };
};
</script>