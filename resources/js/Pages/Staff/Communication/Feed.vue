<template>
    <AuthenticatedLayout>
        <template #default>
            <div class="w-full h-full bg-dirtywhite">
                <div class="px-48 border-box w-full h-full flex flex-row bg-dirtywhite">
                    <div class="w-[95%] pt-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-900 scrollbar-track-gray-100 scrollbar-thumb-rounded h-full">
                        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 ">
                            <div class="rounded-lg mb-10">
                                <div class="h-full space-y-3 flex flex-col items-center justify-start pt-2 pb-10">
                                    
                                    <div class="bg-white w-full h-full p-5 space-y-3 rounded-lg shadow-md dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">
                                        <span class="font-poppins font-semibold text-xl dark:text-dtext">Announce to Everyone</span>
                                        
                                        <!-- Card -->
                                        <div class="flex flex-col flex-grow gap-3">
                                            <!-- Message Container (Expands to fill space) -->
                                            <div class="flex-grow w-full">
                                                <textarea class="w-full h-full resize-none p-2 bg-transparent border-none outline-none focus:ring-0" rows="5"
                                                    placeholder="Write your announcement here..."></textarea>
                                            </div>



                                            <!-- Buttons Container (Stays at the Bottom) -->
                                            <div class="w-full flex flex-row justify-between items-center">
                                                <div class="flex gap-2">
                                                    <button type="button" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-xs px-3 py-1 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55">
                                                        <span class="material-symbols-rounded text-[18px] me-2">school</span>
                                                        Share to University
                                                    </button>
                                                    
                                                    <button type="button" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-xs px-3 py-1 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55">
                                                        <span class="material-symbols-rounded text-[18px] me-2">groups</span>
                                                        Share to Group
                                                    </button>
                                                </div>

                                                <button @click="toggleSharePost" class="text-white font-sans bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    Share a Post
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="w-full bg-white rounded-lg shadow-md">
                                        <div class="w-full p-4 flex items-center border-b border-gray-200">
                                            <img class="w-12 h-12 rounded-full" src="https://placehold.co/50x50" alt="user-avatar" />
                                            <div class="ml-4">
                                                <p class="text-primary font-semibold">URS - HEAD SCHOLARSHIP ADMINISTRATOR</p>
                                                <p class="text-sm text-gray-500">Posted NOV. 15, 2024 @ 10:30 PM Binangonan Campus</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="bg-gradient-to-t from-blue-900 via-blue-800 to-blue-700 h-96 flex items-center justify-center text-white text-xl text-center font-onest text-bold break-words overflow-hidden">
                                                <p>fefrgr ghthtyjyj</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full bg-white rounded-lg shadow-md">
                                        <div class="w-full p-4 flex items-center border-b border-gray-200">
                                            <img class="w-12 h-12 rounded-full" src="https://placehold.co/50x50" alt="user-avatar" />
                                            <div class="ml-4">
                                                <p class="text-primary font-semibold">URS - HEAD SCHOLARSHIP ADMINISTRATOR</p>
                                                <p class="text-sm text-gray-500">Posted NOV. 15, 2024 @ 10:30 PM Binangonan Campus</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <!-- <div class="bg-gradient-to-t from-blue-900 via-blue-800 to-blue-700 h-96 flex items-center justify-center text-white text-xl text-center font-onest text-bold break-words overflow-hidden">
                                                <p>fefrgr ghthtyjyj</p>
                                            </div> -->
                                        </div>
                                    </div>

                                    <div class="w-full bg-white rounded-lg shadow-md">
                                        <div class="w-full p-4 flex items-center border-b border-gray-200">
                                            <img class="w-12 h-12 rounded-full" src="https://placehold.co/50x50" alt="user-avatar" />
                                            <div class="ml-4">
                                                <p class="text-primary font-semibold">URS - HEAD SCHOLARSHIP ADMINISTRATOR</p>
                                                <p class="text-sm text-gray-500">Posted NOV. 15, 2024 @ 10:30 PM Binangonan Campus</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="bg-gradient-to-t from-blue-900 via-blue-800 to-blue-700 h-96 flex items-center justify-center text-white text-xl text-center font-onest text-bold break-words overflow-hidden">
                                                <p>fefrgr ghthtyjyj</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full bg-white rounded-lg shadow-md">
                                        <div class="w-full p-4 flex items-center border-b border-gray-200">
                                            <img class="w-12 h-12 rounded-full" src="https://placehold.co/50x50" alt="user-avatar" />
                                            <div class="ml-4">
                                                <p class="text-primary font-semibold">URS - HEAD SCHOLARSHIP ADMINISTRATOR</p>
                                                <p class="text-sm text-gray-500">Posted NOV. 15, 2024 @ 10:30 PM Binangonan Campus</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="bg-gradient-to-t from-blue-900 via-blue-800 to-blue-700 h-96 flex items-center justify-center text-white text-xl text-center font-onest text-bold break-words overflow-hidden">
                                                <p>fefrgr ghthtyjyj</p>
                                            </div>
                                        </div>
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
        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
        <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
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
            <form @submit.prevent="submitForm" class="p-6 flex flex-col gap-7">
                
                <!-- User Profile Section -->
                <div class="flex items-center gap-3 mb-5">
                    <img src="../../../../assets/images/no_userpic.png" alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">si ako</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">taga binondo</p>
                    </div>
                </div>

                <!-- Message Box (Pre-filled Message) -->
                <div class="">
                    <label for="message" class="block text-sm font-medium text-gray-900 dark:text-gray-300">Announcement Message</label>
                    <textarea class="w-full h-full resize-none p-2 bg-transparent border-gray-300 rounded-md bg-gray-200 outline-none focus:ring-0" rows="5"
                    placeholder="Write your announcement here..."></textarea>

                </div>

                <!-- Filter Section (Who to send to) -->
                <div class="">
                    <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Who do you want to send this to?</label>
                    
                    <!-- Checkboxes for selecting groups -->
                    <div class="mt-2 flex flex-col gap-2">
                        <label v-for="group in groups" :key="group.id" class="flex items-center space-x-2">
                            <input type="checkbox" 
                                :value="group.id" 
                                v-model="selectedGroups" 
                                class="text-blue-600 focus:ring-0 rounded">
                            <span class="text-gray-900 dark:text-white">{{ group.name }}</span>
                        </label>
                        <!-- Option for selecting "Everyone" -->
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" 
                                value="all" 
                                v-model="selectedGroups" 
                                class="text-blue-600 focus:ring-0 rounded">
                            <span class="text-gray-900 dark:text-white">Everyone</span>
                        </label>
                    </div>
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
import { ref, onMounted, watch } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const props = defineProps({
    messages: Array,
    currentUser: Object,
    scholarships: Array,
    selectedScholarship: Object,
});

const Share = ref(false);

const toggleSharePost = () => {
    Share.value = !Share.value;
    initFlowbite(); // Initialize Flowbite first
};


const closeModal = () => {
    Share.value = false;

};

const messageData = ref(props.messages);


const selectedData = ref(props.selectedScholarship);
const form = ref({
    content: '',
    scholarship_id: ''
});

const sendMessage = () => {
    // Get scholarship_id from selected scholarship
    form.value.scholarship_id = selectedData.value?.id || '';

    router.post('/group-chat/message', form.value, {
        preserveScroll: true,
        onSuccess: () => {
            fetchMessages(); // Fetch messages after sending
            form.value.content = ''; // Clear input after sending
        },
    });
};


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

const scholarshipId = ref(props.selectedScholarship); // Or however you're getting the ID

const fetchMessages = async () => {
    const { data } = await router.get(route("student.messaging.show", { scholarship: props.selectedScholarship.id }));

    messageData.value = data;
};

const scrollToBottom = () => {
    const chatContainer = document.querySelector('.overflow-y-auto');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
};

// Update scroll after new message
// watch(messageData, () => {
//     scrollToBottom();
// });

// Add this near your other ref() declarations
const showMemberList = ref(false);
</script>
