<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const props = defineProps({
    messages: Array,
    currentUser: Object,
    scholarships: Array,
    selectedScholarship: Object,
    organizedScholarships: Array,
});

const messageData = ref(props.messages);
const selectedData = ref(props.selectedScholarship);
const form = ref({
    content: '',
    scholarship_id: ''
});

const sendMessage = () => {
    form.value.scholarship_id = selectedData.value?.id || '';

    router.post('/group-page/message', form.value, {
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
        authEndpoint: "/broadcasting/auth",
    });

    echo.private(`chat.${props.selectedScholarship.id}`)
        .listen('.message.sent', (e) => {
            fetchMessages();
            scrollToBottom();
            messageData.value.push(e.message); // Append new message
        });
});

const fetchMessages = async () => {
    const { data } = await router.get(route("messaging.show", { scholarship: props.selectedScholarship.id }));
    messageData.value = data;
};

const scrollToBottom = () => {
    const chatContainer = document.querySelector('.overflow-y-auto');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
};

const showMemberList = ref(false);
</script>

<Head title="Chat" />
<template #default>
    <div class="bg-dirtywhite w-[95%] p-4 h-full">
        <div class="bg-white w-full h-full rounded-xl flex flex-row">
            <div class="w-[30%] border-r">
                <h3 class="text-xl text-primary mb-1 px-4 pt-4 pb-0 font-poppins font-extrabold">Messages</h3>
                <div class="divide-y">
                    <Link class="w-full flex items-center space-x-3 mb-2 p-4" v-for="scholarship in scholarships" :key="scholarship.id"
                        :href="route('messaging.show', scholarship.id)" :class="[
                            'hover:bg-gray-100',
                            selectedData && selectedData.id === scholarship.id ? 'bg-blue-50 border-l-4 border-primary' : ''
                        ]">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 font-semibold">
                            {{ scholarship.name.charAt(0) }}
                        </div>
                        <div class="flex flex-col space-y-1">
                            <span class="text-primary-foreground font-quicksand font-semibold text-lg">{{ scholarship.name }}</span>
                            <p class="text-xs text-gray-500 truncate" v-if="scholarship.latest_message">
                                {{ scholarship.latest_message.content }}
                            </p>
                            <p class="text-xs text-gray-400 italic" v-else>No messages yet</p>
                        </div>
                    </Link>
                </div>
            </div>

            <div class="w-[70%] h-full flex flex-col">
                <div class="shadow-sm p-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-primary">Conversation</h3>
                </div>

                <!-- Main chat area -->
                <div class="flex flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer">
                    <div class="flex-1 px-2 overflow-y-auto overscroll-contain inset-shadow-sm flex flex-col-reverse">
                        <div v-if="!selectedData || !selectedData.id" class="flex items-center justify-center h-full text-gray-500">
                            <span class="text-lg font-semibold">Select a group</span>
                        </div>

                        <div v-else-if="selectedData && selectedData.id && !messageData.length"
                             class="flex items-center justify-center h-full text-gray-500">
                            <span class="text-lg font-semibold">No messages available</span>
                        </div>

                        <div v-else v-for="(message, index) in messageData" :key="message.id">
                            <!-- Display message components here -->
                        </div>
                    </div>
                </div>

                <div class="flex items-center box-border p-2 bg-white z-100 shadow-[0_-2px_5px_rgba(0,0,0,0.1)]">
                    <button class="px-2" @click="sendMessage" :disabled="!selectedData || !selectedData.id">
                        <font-awesome-icon :icon="['fas', 'circle-plus']" />
                    </button>
                    <input type="text" placeholder="Type your message..." class="flex-1 bg-transparent text-primary-foreground p-2"
                        v-model="form.content" @keyup.enter="sendMessage" :disabled="!selectedData || !selectedData.id" />
                    <button class="px-2 transition duration-200 group" @click="sendMessage" :disabled="!selectedData || !selectedData.id">
                        <font-awesome-icon :icon="['far', 'paper-plane']" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
