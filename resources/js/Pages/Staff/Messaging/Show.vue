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
            fetchMessages();
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
            fetchMessages();
            // Don't need to fetchMessages - the broadcast will handle it
            form.reset();
        }
    });
};

const fetchMessages = async () => {
    const { data } = await router.get(route("grouppage.show", { scholarship: props.scholarship.id }));
    
    messageData.value = data;
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
            <div class="w-[70%] h-full flex flex-col space-y-3">
                <!-- Header -->
                <div class="shadow-sm mb-4 p-4">
                    <h3 class="text-lg font-bold text-primary">Conversation</h3>
                </div>

                <!-- Messages Area -->
                <div ref="messagesContainer" class="flex-1 px-2 overflow-y-auto overscroll-contain inset-shadow-sm max-h-[calc(95vh-160px)]">
                    <div v-for="message in messagesData" :key="message.id" class="flex items-start gap-2.5"
                        :class="message.user_id === currentUser.id ? 'justify-end' : ''">
                        
                        <!-- Avatar for other users -->
                        <!-- <img v-if="message.user_id !== currentUser.id" class="w-8 h-8 rounded-full mt-6 border"
                            src="/docs/images/people/profile-picture-3.jpg" alt="User Image"> -->
                        <div v-if="message.user_id !== currentUser.id" class="flex-shrink-0 mr-3">
                            <div
                                class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-semibold text-sm">
                                {{ message.user.name.charAt(0) }}
                            </div>
                        </div>

                        <!-- Message Bubble -->
                        <div class="flex flex-col gap-1 w-full max-w-[320px]">
                            <div class="flex items-center space-x-2 rtl:space-x-reverse"
                                :class="message.user_id === currentUser.id ? 'justify-end' : ''">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    dada
                                </span>
                            </div>
                            
                            <div :class="['p-4 rounded-xl leading-1.5 border shadow-sm',
                                message.user_id === currentUser.id ? 'bg-blue-500 text-white rounded-br-none' : 'bg-gray-100 dark:bg-gray-700 rounded-bl-none']">
                                <p class="text-sm">{{ message.content }}</p>
                            </div>

                            <div class="flex items-center space-x-2 rtl:space-x-reverse text-gray-500 text-xs"
                                :class="message.user_id === currentUser.id ? 'justify-end' : ''">
                                <span>Delivered</span>
                                <!-- <span>{{ formatDate(message.created_at) }}</span> -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="flex items-center p-2 bg-white shadow-[0_-2px_5px_rgba(0,0,0,0.1)]">
                    <form @submit.prevent="sendMessage" class="flex">
                        <button class="px-2" @click="sendMessage">
                            <font-awesome-icon :icon="['fas', 'circle-plus']"
                                class="w-6 h-6 text-primary hover:text-primary/80 hover:rotate-90 transition" />
                        </button>
                        <input type="text" placeholder="Type your message..."
                            class="flex-1 bg-transparent text-primary-foreground p-2 focus:outline-none border-none"
                            v-model="form.content" @keyup.enter="sendMessage" />
                        <button class="px-2 transition duration-200 group" @click="sendMessage">
                            <font-awesome-icon :icon="['far', 'paper-plane']"
                                class="w-6 h-6 text-primary group-hover:hidden" />
                            <font-awesome-icon :icon="['fas', 'paper-plane']"
                                class="w-6 h-6 text-primary hidden group-hover:inline-block" />
                        </button>
                    </form>
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