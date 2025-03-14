<script setup>
import { ref, onMounted, nextTick, computed } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Props received from controller
const props = defineProps({
    scholarship: Object,
    messages: Object,
    currentUser: Object,
    allScholarships: Array,
    errors: Object
});

const messagesContainer = ref(null);
const messagesData = ref(props.messages.data.slice().reverse()); // Reverse to show oldest first
const showMembersList = ref(false);
const mobileSidebarOpen = ref(false);

const form = useForm({
    content: ''
});

const scrollToBottom = () => {
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
};

onMounted(() => {
    // Scroll to bottom on load
    nextTick(() => {
        scrollToBottom();
    });

    // Set up real-time listener
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true
    });

    // Listen for new messages on the correct channel
    // This could be either a private or presence channel depending on your setup
    window.Echo.channel(`scholarship.${props.scholarship.id}`)
        .listen('NewMessage', (e) => {
            console.log('New message received:', e);
            // Add the new message to the messages array
            messagesData.value.push(e.message);
            nextTick(() => {
                scrollToBottom();
            });
        });
});

const sendMessage = () => {
    if (!form.content.trim()) return;

    form.post(route('grouppage.store', props.scholarship.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Don't need to fetchMessages - the broadcast will handle it
            form.reset();
        }
    });
};

const formattedTime = (dateString) => {
    return new Date(dateString).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const toggleMembersList = () => {
    showMembersList.value = !showMembersList.value;
};

const toggleMobileSidebar = () => {
    mobileSidebarOpen.value = !mobileSidebarOpen.value;
};

const formatLastSeen = (dateString) => {
    if (!dateString) return '';

    const date = new Date(dateString);
    const now = new Date();
    const diffInMinutes = Math.floor((now - date) / (1000 * 60));

    if (diffInMinutes < 1) return 'Just now';
    if (diffInMinutes < 60) return `${diffInMinutes}m ago`;

    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours}h ago`;

    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 7) return `${diffInDays}d ago`;

    return date.toLocaleDateString();
};
</script>

<template>

    <Head :title="scholarship.name" />

    <AuthenticatedLayout>
        <div class="flex h-screen overflow-hidden bg-gray-100">
            <!-- Mobile sidebar toggle -->
            <div class="lg:hidden fixed top-0 left-0 z-20 mt-4 ml-4">
                <button @click="toggleMobileSidebar" class="p-2 bg-white rounded-full shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar / Group list -->
            <div :class="['bg-white w-80 flex-shrink-0 border-r overflow-y-auto',
                {
                    'hidden lg:block': !mobileSidebarOpen,
                    'fixed inset-0 z-10 lg:relative lg:inset-auto': mobileSidebarOpen
                }]">
                <div class="p-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center justify-between">
                        My Groups
                        <button @click="toggleMobileSidebar" class="lg:hidden p-1 rounded-full hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </h2>
                </div>

                <div class="divide-y">
                    <Link v-for="group in allScholarships" :key="group.id" :href="route('grouppage.show', group.id)"
                        :class="['block hover:bg-gray-50 p-4',
                            { 'bg-blue-50': group.id === scholarship.id }]">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-3">
                            <!-- Group avatar -->
                            <div
                                class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 font-semibold">
                                {{ group.name.charAt(0) }}
                            </div>
                        </div>

                        <div class="flex-grow">
                            <h3 class="font-medium text-gray-900">{{ group.name }}</h3>
                            <p class="text-xs text-gray-500 truncate" v-if="group.latest_message">
                                {{ group.latest_message.content }}
                            </p>
                            <p class="text-xs text-gray-400 italic" v-else>No messages yet</p>
                        </div>
                    </div>
                    </Link>
                </div>
            </div>

            <!-- Main chat area -->
            <div class="flex-grow flex flex-col">
                <!-- Chat header -->
                <div class="bg-white border-b p-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-3">
                            <!-- Group avatar -->
                            <div
                                class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 font-semibold">
                                {{ scholarship.name.charAt(0) }}
                            </div>
                        </div>

                        <div>
                            <h2 class="font-semibold text-lg text-gray-900">{{ scholarship.name }}</h2>
                            <p class="text-xs text-gray-500">{{ scholarship.users_count }} members</p>
                        </div>
                    </div>

                    <button @click="toggleMembersList" class="p-2 rounded-full hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </button>
                </div>

                <!-- Messages area -->
                <div ref="messagesContainer" class="flex-grow overflow-y-auto p-4 space-y-3 bg-gray-50">
                    <div v-for="message in messagesData" :key="message.id" class="message">
                        <div :class="['flex items-start', message.user_id === currentUser.id ? 'justify-end' : '']">
                            <!-- Avatar for others' messages -->
                            <div v-if="message.user_id !== currentUser.id" class="flex-shrink-0 mr-3">
                                <div
                                    class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-semibold text-sm">
                                    {{ message.user.name.charAt(0) }}
                                </div>
                            </div>

                            <!-- Message bubble -->
                            <div :class="['p-3 rounded-lg max-w-xs sm:max-w-md',
                                message.user_id === currentUser.id ?
                                    'bg-blue-500 text-white' : 'bg-white border']">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium text-sm">{{ message.user.name }}</span>
                                    <span class="text-xs opacity-75 ml-2">{{ formattedTime(message.created_at) }}</span>
                                </div>
                                <div>{{ message.content }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message input -->
                <div class="bg-white border-t p-4">
                    <form @submit.prevent="sendMessage" class="flex">
                        <input v-model="form.content" type="text" placeholder="Type your message..."
                            class="flex-grow border rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :disabled="form.processing" />
                        <button type="submit" :disabled="form.processing"
                            class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-75">
                            <span v-if="!form.processing">Send</span>
                            <span v-else>Sending...</span>
                        </button>
                    </form>
                    <p v-if="errors.content" class="text-red-500 text-sm mt-1">{{ errors.content }}</p>
                </div>
            </div>

            <!-- Members panel (right sidebar) -->
            <div v-if="showMembersList" class="w-64 bg-white border-l overflow-y-auto">
                <div class="p-4 border-b">
                    <h3 class="font-semibold text-gray-800 flex items-center justify-between">
                        Members
                        <button @click="toggleMembersList" class="p-1 rounded-full hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </h3>
                </div>

                <ul class="divide-y">
                    <li v-for="user in scholarship.users" :key="user.id" class="p-3 hover:bg-gray-50">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 mr-3">
                                <div
                                    class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold">
                                    {{ user.name.charAt(0) }}
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-sm" :class="{ 'text-blue-500': user.id === currentUser.id }">
                                    {{ user.name }}
                                    <span v-if="user.id === currentUser.id">(You)</span>
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>