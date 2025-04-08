<template>

    <Head title="Chat" />

    <AuthenticatedLayout>
        <template #default>
            <!-- <div class="w-full h-full bg-dirtywhite">
                <div class="px-48 border-box w-full h-full flex flex-row bg-dirtywhite">
                    <div class="bg-dirtywhite py-5 w-[5%] h-full">
                        <div class="flex flex-col items-center justify-center">

                            <button @click="showPMContent">
                                <font-awesome-icon :icon="['fas', 'comment']"
                                class="items-center justify-center w-8 h-8 text-[30px] text-primary p-3 bg-gray-200 rounded-lg" />
                            </button>
                            <span class="block w-full h-[2px] bg-gray-300 my-3"></span>

                            <button @click="showFeedContent">
                                <font-awesome-icon :icon="['fab', 'google-scholar']"
                                class="items-center justify-center w-7 h-7 text-[30px] text-primary p-3.5 bg-white rounded-lg shadow-md mb-3 hover:bg-gray-100"
                                v-tooltip="'Scholarships'" />
                            </button>
                        </div>
                    </div>
                    
                    <div v-if="isFeedActive" class="w-[95%]">
                        <Feed />
                    </div>

                    <div v-if="isPMActive" class="w-[95%]">
                        <Messaging :messages="messages" :currentUser="currentUser" :batches="batches" :selectedBatch="selectedBatch"/>
                    </div>

                </div>
            </div> -->
        </template>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import Feed from './Feed.vue';
import Messaging from './Messaging.vue';

const props = defineProps({
    messages: Array,
    currentUser: Object,
    batches: Array,
    selectedBatch: Object,
});

// const messageData = ref(props.messages);


const isFeedActive = ref(false);
const isPMActive = ref(true);

const showFeedContent = () => {
  isFeedActive.value = true;
  isPMActive.value = false; // Hide Messaging
};

const showPMContent = () => {
  isPMActive.value = true;
  isFeedActive.value = false; // Hide Feed
};


// const selectedData = ref(props.selectedScholarship);
// const form = ref({
//     content: '',
//     scholarship_id: ''
// });

// const sendMessage = () => {
//     // Get scholarship_id from selected scholarship
//     form.value.scholarship_id = selectedData.value?.id || '';

//     router.post('/group-chat/message', form.value, {
//         preserveScroll: true,
//         onSuccess: () => {
//             fetchMessages(); // Fetch messages after sending
//             form.value.content = ''; // Clear input after sending
//         },
//     });
// };


// // Set up real-time messaging using Laravel Echo
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

// const scholarshipId = ref(props.selectedScholarship); // Or however you're getting the ID

// const fetchMessages = async () => {
//     const { data } = await router.get(route("student.messaging.show", { scholarship: props.selectedScholarship.id }));

//     messageData.value = data;
// };

// const scrollToBottom = () => {
//     const chatContainer = document.querySelector('.overflow-y-auto');
//     if (chatContainer) {
//         chatContainer.scrollTop = chatContainer.scrollHeight;
//     }
// };

// Update scroll after new message
// watch(messageData, () => {
//     scrollToBottom();
// });

// Add this near your other ref() declarations
const showMemberList = ref(false);
</script>
