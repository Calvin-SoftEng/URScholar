<template>
    <AuthenticatedLayout>
        <template #default>
            <div
                class="sm:px-0 lg:px-48 border-box w-full h-full flex flex-row bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
                <div class="border-box w-full h-full flex flex-row">
                    <div
                        class="w-full pt-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-900 scrollbar-track-gray-100 scrollbar-thumb-rounded h-full">
                        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 ">
                            <div class="rounded-lg mb-10">
                                <div class="h-full space-y-3 flex flex-col items-center justify-start pt-2 pb-10">
                                    <!-- Check if there are any posts -->
                                    <div v-if="posts.length">
                                        <div v-for="post in posts" :key="post.id"
                                            class="w-full bg-white rounded-lg shadow-md mb-4">
                                            <div class="w-full p-4 flex items-center border-b border-gray-200">
                                                <img class="w-12 h-12 rounded-full" src="https://placehold.co/50x50"
                                                    alt="user-avatar" />
                                                <div class="ml-4">
                                                    <p class="text-primary font-semibold">{{ post.user.name }}</p>
                                                    <p class="text-sm text-gray-500">Posted {{ post.created_at }}</p>
                                                    <div v-if="post.filters.scholarships.length || post.filters.batches.length || post.filters.campuses.length"
                                                        class="flex flex-wrap gap-1 mt-1">
                                                        <span v-for="(scholarship, index) in post.filters.scholarships"
                                                            :key="`s-${index}`"
                                                            class="text-xs px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full">
                                                            {{ scholarship }}
                                                        </span>
                                                        <span v-for="(batch, index) in post.filters.batches"
                                                            :key="`b-${index}`"
                                                            class="text-xs px-2 py-0.5 bg-green-100 text-green-800 rounded-full">
                                                            {{ batch.name }}
                                                        </span>
                                                        <span v-for="(campus, index) in post.filters.campuses"
                                                            :key="`c-${index}`"
                                                            class="text-xs px-2 py-0.5 bg-purple-100 text-purple-800 rounded-full">
                                                            {{ campus }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4">
                                                <div
                                                    class="bg-gradient-to-t from-blue-900 via-blue-800 to-blue-700 h-96 flex items-center justify-center text-white text-xl text-center font-onest text-bold break-words overflow-hidden p-4">
                                                    <p>{{ post.content }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Show this if no posts -->
                                    <div v-else
                                        class="w-full bg-white rounded-lg shadow-md text-center text-gray-500 py-10">
                                        <p class="text-lg font-medium">No posts or announcements yet.</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, onMounted, onBeforeUnmount, watch, nextTick, computed } from 'vue';
import { initFlowbite } from 'flowbite';

// Props from controller - now using 'posts' prop directly from controller
const props = defineProps({
    campuses: Array,
    batches: Array,
    scholarships: Array,
    auth: Object,
    posts: Array, // Posts are now passed directly from controller
});

// Current user
const currentUser = computed(() => props.auth?.user || null);

// Create reference to posts for reactivity
const posts = computed(() => props.posts || []);
const isLoading = ref(false);

// Modal state if needed for creating new posts
const Share = ref(false);
const announcementContent = ref('');
const modalContent = ref('');

// Filter selections
const selectedScholarships = ref([]);
const selectedBatches = ref([]);
const selectedCampuses = ref([]);

// Dropdown control
const openDropdown = ref(null);

// Create computed properties for filtered options based on selections
const filteredBatches = computed(() => {
    if (selectedScholarships.value.length === 0) {
        return props.batches;
    } else {
        return props.batches.filter(batch => {
            return selectedScholarships.value.includes(batch.scholarship.name);
        });
    }
});

// Format timestamp for message display
const formatTimestamp = (timestamp) => {
    if (!timestamp) return '';

    const date = new Date(timestamp);
    const now = new Date();

    const isToday = date.getDate() === now.getDate() &&
        date.getMonth() === now.getMonth() &&
        date.getFullYear() === now.getFullYear();

    if (isToday) {
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    } else {
        return date.toLocaleDateString([], { month: 'short', day: 'numeric' });
    }
};

const filteredCampuses = computed(() => {
    if (selectedBatches.value.length === 0) {
        return props.campuses;
    } else {
        // Extract batch IDs from the selected batches (format: "Batch X")
        const selectedBatchNumbers = selectedBatches.value.map(batchName => {
            return parseInt(batchName.replace('Batch ', ''));
        });

        // Find campus IDs associated with these batches
        const campusIds = props.batches
            .filter(batch => selectedBatchNumbers.includes(batch.batch_no))
            .map(batch => batch.campus_id);

        // Return campuses with these IDs
        return props.campuses.filter(campus => campusIds.includes(campus.id));
    }
});

// Reset dependent selections when parent selection changes
watch(selectedScholarships, () => {
    // When scholarships change, reset batch and campus selections
    selectedBatches.value = [];
    selectedCampuses.value = [];
});

watch(selectedBatches, () => {
    // When batches change, reset campus selections
    selectedCampuses.value = [];
});

// Copy announcement content to modal when opening
watch(Share, (newValue) => {
    if (newValue) {
        modalContent.value = announcementContent.value;
    }
});

const toggleSharePost = async () => {
    Share.value = !Share.value;

    if (Share.value) {
        await nextTick();
        initFlowbite();
        document.addEventListener('click', handleClickOutside);
    } else {
        document.removeEventListener('click', handleClickOutside);
    }
};

const closeModal = () => {
    Share.value = false;
    // Reset selections when closing modal
    selectedScholarships.value = [];
    selectedBatches.value = [];
    selectedCampuses.value = [];
    openDropdown.value = null;
};

const toggleDropdown = (dropdown) => {
    openDropdown.value = openDropdown.value === dropdown ? null : dropdown;
};

const handleClickOutside = (event) => {
    const dropdownElements = document.querySelectorAll('.relative');
    let clickedOutside = true;

    dropdownElements.forEach(element => {
        if (element.contains(event.target)) {
            clickedOutside = false;
        }
    });

    if (clickedOutside && !event.target.closest('.your-modal-class')) {
        openDropdown.value = null;
    }
};

const submitForm = () => {
    // Prepare scholarship IDs array
    const scholarship_ids = props.scholarships
        .filter(scholarship => selectedScholarships.value.includes(scholarship.name))
        .map(scholarship => scholarship.id);

    // Prepare batch IDs array (extracting the number from "Batch X")
    const batch_ids = props.batches
        .filter(batch => selectedBatches.value.includes(`Batch ${batch.batch_no}`))
        .map(batch => batch.id);

    // Prepare campus IDs array
    const campus_ids = props.campuses
        .filter(campus => selectedCampuses.value.includes(campus.name))
        .map(campus => campus.id);

    // Submit the form via Inertia
    router.post('/posts', {
        content: modalContent.value,
        scholarship_ids: scholarship_ids,
        batch_ids: batch_ids,
        campus_ids: campus_ids,
    }, {
        onSuccess: () => {
            closeModal();
            announcementContent.value = '';
        }
    });
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    initFlowbite();
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>