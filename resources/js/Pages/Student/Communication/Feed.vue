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
onMounted(() => {

    const echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        authEndpoint: "/broadcasting/auth", // Required for private channels
    });

    echo.private(`chat.${props.selectedScholarship.id}`) // Use private channel
        .listen('.message.sent', (e) => {
            fetchMessages(); // Fetch messages after receiving
            scrollToBottom();
            messages.value.push(e.message); // Append new message
        });
});

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

<template>
    <div class="w-[95%] pt-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-900 scrollbar-track-gray-100 scrollbar-thumb-rounded h-full">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 ">
            <div class="rounded-lg mb-10">
                <div class="h-full space-y-3 flex flex-col items-center justify-start pt-2 pb-10">
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

</template>
