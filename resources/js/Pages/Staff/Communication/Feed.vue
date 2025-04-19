<template>
    <AuthenticatedLayout>
        <template #default>
            <div
                class="px-48 border-box w-full h-full flex flex-row bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
                <div class="w-[95%] p-4 h-full">
                    <div
                        class="w-[95%] pt-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-900 scrollbar-track-gray-100 scrollbar-thumb-rounded h-full">
                        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 ">
                            <div class="rounded-lg mb-10">
                                <div class="h-full space-y-3 flex flex-col items-center justify-start pt-2 pb-10">

                                    <div
                                        class="bg-white w-full h-full p-3 space-y-3 rounded-lg shadow-md dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">
                                        <span class="font-poppins font-semibold text-xl dark:text-dtext p-2">Announce to
                                            Everyone</span>

                                        <!-- Card -->
                                        <div class="flex flex-col flex-grow gap-3">
                                            <!-- Message Container (Expands to fill space) -->
                                            <div class="flex-grow w-full ">
                                                <textarea v-model="announcementContent"
                                                    class="w-full h-full resize-none px-4 py-2 bg-transparent border-none outline-none focus:ring-0"
                                                    rows="5" placeholder="Write your announcement here..."></textarea>
                                            </div>
                                            <!-- Buttons Container (Stays at the Bottom) -->
                                            <div
                                                class="w-full flex flex-row justify-end items-center border-t dark:border-gray-600">

                                                <button @click="toggleSharePost"
                                                    class="text-white mt-2 font-sans bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    Share a Post
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Posts -->
                                    <div v-if="isLoading" class="w-full flex justify-center py-10">
                                        <div role="status">
                                            <svg aria-hidden="true"
                                                class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                                    fill="currentColor" />
                                                <path
                                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                                    fill="currentFill" />
                                            </svg>
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>

                                    <!-- Check if there are any posts -->
                                    <div v-if="posts.length" class="w-full">
                                        <div v-for="post in posts" :key="post.id"
                                            class="w-full bg-white rounded-lg shadow-md">
                                            <div class="w-full p-4 flex items-center border-b border-gray-200">
                                                <img class="w-12 h-12 rounded-full"
                                                    :src="`/storage/user/profile/${post.user.picture }`"
                                                    alt="picture" />
                                                <div class="ml-4">
                                                    <p class="text-primary font-semibold">{{ post.user.first_name }} {{
                                                        post.user.last_name }}</p>
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

    <!-- Creating a post modal -->
    <div v-if="Share"
        class="fixed inset-0 flex items-center justify-center z-[999] bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
        <div
            class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12 overflow-visible relative">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <div class="flex items-center gap-3">
                    <!-- Icon -->
                    <font-awesome-icon :icon="['fas', 'graduation-cap']" class="text-blue-600 text-2xl flex-shrink-0" />

                    <!-- Title and Description -->
                    <div class="flex flex-col">
                        <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                            Share Announcement
                        </h2>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Send to everyone or you can filter out specific groups.
                        </span>
                    </div>
                </div>

                <!-- Close Button -->
                <button type="button" @click="closeModal"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form @submit.prevent="submitForm" class="p-6 flex flex-col gap-3">

                <!-- User Profile Section -->
                <div class="flex items-center gap-3">
                    <img class="w-12 h-12 rounded-full object-cover"
                        :src="`/storage/user/profile/${$page.props.auth.user.picture}`" alt="picture">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ currentUser?.first_name ||
                            'User'
                        }} {{ currentUser?.last_name || '' }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ currentUser?.role || 'Head ScholarshipAdmin'}}</p>
                    </div>
                </div>

                <div class="col-span-4 gap-2 relative w-full flex items-center mt-4 whitespace-nowrap">
                    <h3 class="font-semibold text-xs text-blue-900 dark:text-white">
                        Who can see your post?
                    </h3>
                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                </div>

                <!-- Filters -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <!-- Scholarship Filter -->
                    <div class="relative">
                        <label class="block text-xs font-medium mb-1">Scholarship</label>
                        <button type="button"
                            class="w-full text-left border border-gray-200 text-sm rounded-lg p-2 bg-white"
                            @click="toggleDropdown('scholarship')">
                            {{ selectedScholarships.length ? selectedScholarships.join(', ') : 'Select Scholarships' }}
                        </button>
                        <div v-if="openDropdown === 'scholarship'"
                            class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-md max-h-60 overflow-y-auto">
                            <label v-for="item in props.scholarships" :key="item.id"
                                class="block px-4 py-2 hover:bg-gray-100">
                                <input type="checkbox"
                                    class="mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                    :value="item.name" v-model="selectedScholarships" />
                                {{ item.name }}
                            </label>
                        </div>
                    </div>

                    <!-- Batch Filter -->
                    <div class="relative">
                        <label class="block text-xs font-medium mb-1">Batch</label>
                        <button type="button"
                            class="w-full text-left border border-gray-200 text-sm rounded-lg p-2 bg-white"
                            @click="toggleDropdown('batch')">
                            {{ selectedBatches.length ? selectedBatches.join(', ') : 'Select Batches' }}
                        </button>
                        <div v-if="openDropdown === 'batch'"
                            class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-md max-h-60 overflow-y-auto">
                            <label v-for="item in filteredBatches" :key="item.id"
                                class="block px-4 py-2 hover:bg-gray-100">
                                <input type="checkbox"
                                    class="mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                    :value="`Batch ${item.batch_no}`" v-model="selectedBatches" />
                                Batch {{ item.batch_no }} ({{ item.scholarship.name }})
                            </label>
                        </div>
                    </div>

                    <!-- Campus Filter -->
                    <div class="relative">
                        <label class="block text-xs font-medium mb-1">Campus</label>
                        <button type="button"
                            class="w-full text-left border border-gray-200 text-sm rounded-lg p-2 bg-white"
                            @click="toggleDropdown('campus')">
                            {{ selectedCampuses.length ? selectedCampuses.join(', ') : 'Select Campuses' }}
                        </button>
                        <div v-if="openDropdown === 'campus'"
                            class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-md max-h-60 overflow-y-auto">
                            <label v-for="item in filteredCampuses" :key="item.id"
                                class="block px-4 py-2 hover:bg-gray-100">
                                <input type="checkbox"
                                    class="mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                    :value="item.name" v-model="selectedCampuses" />
                                {{ item.name }}
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Message Box (Pre-filled Message) -->
                <div class="">
                    <label for="message" class="block text-sm font-medium text-gray-900 dark:text-gray-300">Announcement
                        Message</label>
                    <textarea v-model="modalContent"
                        class="w-full h-full resize-none p-2 bg-transparent border-gray-300 rounded-md bg-gray-200 outline-none focus:ring-0"
                        rows="5" placeholder="Write your announcement here..."></textarea>
                </div>

                <!-- Post Button -->
                <div class="mt-2">
                    <button type="submit"
                        class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                        Post Announcement</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, onMounted, onBeforeUnmount, watch, nextTick, computed } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { initFlowbite } from 'flowbite';
import axios from 'axios';

// Props from controller
const props = defineProps({
    campuses: Array,
    batches: Array,
    scholarships: Array,
    auth: Object,
});

// Current user
const currentUser = computed(() => props.auth?.user || null);

// Posts state
const posts = ref([]);
const isLoading = ref(true);

// Modal state
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

const fetchPosts = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get('/posts');
        posts.value = response.data.posts;
    } catch (error) {
        console.error('Error fetching posts:', error);
    } finally {
        isLoading.value = false;
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
            fetchPosts(); // Refresh the posts after successful submission
        }
    });
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    initFlowbite();
    fetchPosts();
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>