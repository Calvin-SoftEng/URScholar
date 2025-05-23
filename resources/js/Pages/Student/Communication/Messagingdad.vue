<template #default>
    <AuthenticatedLayout>
        <div
            class="sm:px-0 lg:px-48 border-box w-full h-full flex flex-row bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
            <div class="w-full p-4 h-full">
                <div class="bg-white w-full h-full rounded-xl flex flex-row">
                    <div
                        class="border-r sm:w-full lg:w-[30%]"
                        :class="{
                            'hidden': isChatOpen,
                            'block': !isChatOpen,
                            'md:block': true
                        }"
                        >
                        <h3 class="text-xl text-primary mb-1 px-4 pt-4 pb-0 font-poppins font-extrabold">
                            Messages</h3>

                        <!-- Removed DM tab and now only showing Group Chats -->
                        <div class="mt-4 border-b border-gray-100 dark:border-gray-600">
                            <div
                                class="w-full p-2 text-center text-sm font-medium text-primary border-b-2 border-primary">
                                Group Chats
                            </div>
                        </div>

                        <!-- search -->
                        <form class="w-full p-3">
                            <label for="default-search"
                                class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="search" id="default-search" v-model="searchTerm"
                                    class="block w-full p-3 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search group chats" />
                            </div>
                        </form>

                        <!-- Group chats section -->
                        <div class="divide-y">
                            <!-- Staff Groups Section -->
                            <div class="">
                                <h4 class="text-xs uppercase text-gray-500 font-semibold px-4 py-2">Staff Groups</h4>
                                <Link v-for="group in filteredStaffGroups" :key="`staff-${group.id}`"
                                    :href="route('messaging.staff', group.id)"
                                    :class="['w-full flex items-center space-x-3 p-4 hover:bg-gray-100',
                                        selectedData && selectedData.id === group.id && groupType === 'staff' ? 'bg-blue-50' : '']"
                                    @click.prevent="selectGroup(group, 'staff')">
                                <div
                                    class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-500 font-semibold">
                                    {{ group.name.slice(0, 2).toUpperCase() }}
                                </div>
                                <div class="flex flex-col space-y-1 flex-grow">
                                    <div class="flex justify-between">
                                        <span class="text-primary-foreground font-quicksand font-semibold">{{ group.name
                                        }}</span>
                                        <span v-if="group.latest_message" class="text-xs text-gray-400">
                                            {{ formatTimestamp(group.latest_message.created_at) }}
                                        </span>
                                    </div>
                                    <div class="flex-grow">
                                        <p class="text-xs text-gray-500 truncate" v-if="group.latest_message">
                                            {{ group.latest_message.user.first_name }}: {{ group.latest_message.content
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-500 italic" v-else>
                                            No messages yet
                                        </p>
                                    </div>
                                </div>
                                </Link>
                            </div>

                            <!-- Scholarship Batches Section -->
                            <div class="py-2">
                                <h4 class="text-xs uppercase text-gray-500 font-semibold px-4 py-2">Scholarship Batches
                                </h4>
                                <Link v-for="batch in filteredBatches" :key="`batch-${batch.id}`"
                                    :href="route('messaging.batch', batch.id)"
                                    :class="['w-full flex items-center space-x-3 p-4 hover:bg-gray-100',
                                        selectedData && selectedData.id === batch.id && groupType === 'batch' ? 'bg-blue-50' : '']"
                                    @click.prevent="selectGroup(batch, 'batch')">
                                <div
                                    class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 font-semibold">
                                    {{ batch.batch_no || 'B' }}
                                </div>
                                <div class="flex flex-col space-y-1 flex-grow">
                                    <div class="flex justify-between">
                                        <span class="text-primary-foreground font-quicksand font-semibold">
                                            {{ batch.name || `Batch ${batch.batch_no}` }}
                                            <span class="text-xs text-gray-500">
                                                ({{ batch.scholarship ? batch.scholarship.name : 'Scholarship' }})
                                            </span>
                                        </span>
                                        <span v-if="batch.latest_message" class="text-xs text-gray-400">
                                            {{ formatTimestamp(batch.latest_message.created_at) }}
                                        </span>
                                    </div>
                                    <div class="flex-grow">
                                        <p class="text-xs text-gray-500 truncate" v-if="batch.latest_message">
                                            {{ batch.latest_message.user.first_name }}: {{ batch.latest_message.content
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-500 italic" v-else>
                                            No messages yet
                                        </p>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-xs text-gray-400">{{ batch.users_count }} members</span>
                                    </div>
                                </div>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="sm:w-full lg:w-[70%] h-full flex flex-col"
                        :class="{
                            'hidden': !isChatOpen,
                            'flex': isChatOpen,
                            'lg:flex': true
                        }">
                        <div class="shadow-sm p-4 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-primary">
                                {{ selectedData ? (selectedData.name || (selectedData.batch_no ? `Batch
                                ${selectedData.batch_no}` : 'Conversation')) : 'Conversation' }}
                            </h3>
                            <!-- Three dots menu aligned with conversation text -->
                            <button class="text-gray-600 hover:text-primary transition-colors"
                                @click="showMemberList = !showMemberList">
                                <font-awesome-icon :icon="['fas', 'ellipsis-vertical']" />
                            </button>
                        </div>

                        <!-- Main chat area -->
                        <div
                            class="flex flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer">
                            <!-- Messages column -->
                            <div
                                class="flex-1 px-2 overflow-y-auto overscroll-contain inset-shadow-sm flex flex-col-reverse">
                                <!-- No group selected message -->
                                <div v-if="!selectedData || !selectedData.id"
                                    class="flex items-center justify-center h-full text-gray-500">
                                    <span class="text-lg font-semibold">Select a group</span>
                                </div>

                                <!-- Selected group but no messages -->
                                <div v-else-if="selectedData && selectedData.id && !messageData.length"
                                    class="flex items-center justify-center h-full text-gray-500">
                                    <span class="text-lg font-semibold">No messages available</span>
                                </div>

                                <!-- Message display when messages exist -->
                                <div v-else v-for="(message, index) in messageData" :key="message.id" :class="{
                                    'flex items-start justify-end gap-2.5': message.user.id === currentUser.id,
                                    'flex items-start justify-start gap-2.5': message.user.id !== currentUser.id
                                }">

                                    <!-- Other User's Message -->
                                    <template v-if="message.user.id !== currentUser.id">
                                        <div v-if="message.user.picture">
                                            <img class="w-8 h-8 rounded-full mt-6 border"
                                                :src="`/storage/user/profile/${message.user.picture}`" alt="picture">
                                        </div>
                                        <div v-else>
                                            <img class="w-8 h-8 rounded-full mt-6 border"
                                                :src="`/storage/user/profile/male.png`" alt="picture">
                                        </div>
                                        <div class="flex flex-col gap-1 w-full justify-start max-w-[320px] mb-3">
                                            <div class="flex justify-start items-center space-x-1 rtl:space-x-reverse">
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ message.user.first_name }}
                                                </span>
                                                <span class="text-sm font-semibold text-gray-400 dark:text-white">
                                                    {{ message.user.usertype }}
                                                </span>
                                                <span class="text-sm font-normal text-gray-300 dark:text-white">
                                                    {{ formatTimeOnly(message.user.created_at) }}
                                                </span>
                                            </div>
                                            <div
                                                class="flex flex-col leading-1.5 p-4 bg-gray-100 text-gray-900 rounded-es-xl rounded-se-xl dark:bg-gray-700">
                                                <p class="text-sm font-normal">{{ message.content }}</p>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Current User's Message -->
                                    <template v-else>
                                        <div class="flex flex-col gap-1 w-full justify-end max-w-[320px]">
                                            <div class="flex justify-end items-center space-x-2 rtl:space-x-reverse">
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ message.user.first_name }}
                                                </span>
                                            </div>
                                            <div
                                                class="flex flex-col leading-1.5 p-4 bg-primary text-white rounded-s-xl rounded-ee-xl dark:bg-gray-700">
                                                <p class="text-sm font-normal">{{ message.content }}</p>
                                            </div>
                                            <!-- Delivered message only for the latest message of the current user -->
                                            <div v-if="index === 0"
                                                class="flex justify-end items-center space-x-2 rtl:space-x-reverse">
                                                <span
                                                    class="text-sm font-normal text-gray-500 dark:text-gray-400">Delivered</span>
                                            </div>
                                        </div>
                                        <div v-if="$page.props.auth.user.picture">
                                            <img class="w-8 h-8 rounded-full mt-6 border"
                                                :src="`/storage/user/profile/${$page.props.auth.user.picture}`"
                                                alt="picture">
                                        </div>
                                        <div v-else>
                                            <img class="w-8 h-8 rounded-full mt-6 border"
                                                :src="`/storage/user/profile/male.png`" alt="picture">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Member list sidebar - conditionally shown -->
                            <div v-if="showMemberList" class="w-64 border-l overflow-y-auto">
                                <div class="p-4">
                                    <h4 class="font-bold text-primary mb-3">Members</h4>

                                    <div v-if="members.length > 0">
                                        <!-- Group members by usertype -->
                                        <template v-for="member in members" :key="member">
                                            <div class="mb-4">
                                                <h5 class="text-xs uppercase text-gray-500 font-semibold mb-2">ewan ko
                                                </h5>
                                            </div>
                                        </template>
                                    </div>
                                    <div v-else class="text-center text-gray-500 py-4">
                                        <p>No member information available</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center box-border p-2 bg-white z-100 shadow-[0_-2px_5px_rgba(0,0,0,0.1)]">
                            <!-- For the circle-plus button -->
                            <button class="px-2" @click="toggleAttachmentMenu"
                                :disabled="!selectedData || !selectedData.id">
                                <font-awesome-icon :icon="['fas', 'circle-plus']" :class="[
                                    'w-6 h-6 transition',
                                    selectedData && selectedData.id ? 'text-primary hover:text-primary/80' : 'text-gray-400 cursor-not-allowed'
                                ]" />
                            </button>

                            <!-- For the text input -->
                            <input type="text" placeholder="Type your message..."
                                class="flex-1 bg-transparent text-primary-foreground p-2 focus:outline-none focus:ring-0 border-none"
                                v-model="form.content" @keyup.enter="sendMessage"
                                :disabled="!selectedData || !selectedData.id" />

                            <!-- For the paper-plane button -->
                            <button class="px-2 transition duration-200 group" @click="sendMessage"
                                :disabled="!selectedData || !selectedData.id || !form.content.trim()">
                                <font-awesome-icon :icon="['far', 'paper-plane']" :class="[
                                    'w-6 h-6',
                                    selectedData && selectedData.id && form.content.trim() ? 'text-primary group-hover:hidden' : 'text-gray-400 cursor-not-allowed'
                                ]" />
                                <font-awesome-icon :icon="['fas', 'paper-plane']" :class="[
                                    'w-6 h-6 hidden group-hover:inline-block',
                                    selectedData && selectedData.id && form.content.trim() ? 'text-primary' : 'text-gray-400 cursor-not-allowed'
                                ]" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, onMounted, watch, computed, onUnmounted } from 'vue';
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const props = defineProps({
    messages: Array,
    currentUser: Object,
    staffGroups: Array,
    batches: Array,
    selectedGroup: Object,
    groupType: String,
    members: Array,
});

const isChatOpen = ref(false)

const openChat = (chat) => {
  isChatOpen.value = true
  // Load chat content for `chat` here if needed
}

const closeChat = () => {
  isChatOpen.value = false
}

const messageData = ref(props.messages || []);
const selectedData = ref(props.selectedGroup);
const groupType = ref(props.groupType || null);
const searchTerm = ref('');
const showMemberList = ref(false);

// Create reactive refs for staff groups and batches
const staffGroupsData = ref(props.staffGroups || []);
const batchesData = ref(props.batches || []);

// Form data for sending messages
const form = ref({
    content: '',
    group_id: props.selectedGroup ? props.selectedGroup.id : '',
    group_type: props.groupType || '',
});

// Filter staff groups based on search term
const filteredStaffGroups = computed(() => {
    if (!staffGroupsData.value) return [];
    if (!searchTerm.value) return staffGroupsData.value;

    return staffGroupsData.value.filter(group =>
        group.name.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

// Filter batches based on search term
const filteredBatches = computed(() => {
    if (!batchesData.value) return [];
    if (!searchTerm.value) return batchesData.value;

    return batchesData.value.filter(batch => {
        const batchName = batch.name || `Batch ${batch.batch_no}`;
        const scholarshipName = batch.scholarship ? batch.scholarship.name : '';
        return batchName.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            scholarshipName.toLowerCase().includes(searchTerm.value.toLowerCase());
    });
});

// Group users by usertype for member list display
const groupedUsers = computed(() => {
    if (!selectedData.value || !selectedData.value.users) return {};

    return selectedData.value.users.reduce((acc, user) => {
        if (!acc[user.usertype]) {
            acc[user.usertype] = [];
        }
        acc[user.usertype].push(user);
        return acc;
    }, {});
});

// Format usertype for display
const formatUserType = (usertype) => {
    if (!usertype) return 'Unknown';

    // Convert snake_case to Title Case
    return usertype
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

const formatTimeOnly = (datetime) => {
    const date = new Date(datetime);
    return date.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
};

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

// Add this function to handle group selection
const selectGroup = (group, type) => {
    // Clear previous messages while loading new ones
    messageData.value = [];

    // Update selected data and group type
    selectedData.value = group;
    groupType.value = type;

    // Navigate to the appropriate route
    const routeName = type === 'staff' ? 'messaging.staff' : 'messaging.batch';
    router.get(route(routeName, group.id), {}, {
        preserveState: true,
        preserveScroll: true,
        only: ['messages', 'selectedGroup', 'groupType'],
        onSuccess: (page) => {
            if (page.props.messages) {
                messageData.value = page.props.messages;
                scrollToBottom();
            }
        }
    });
};

// Toggle attachment menu (placeholder for future functionality)
const toggleAttachmentMenu = () => {
    console.log('Attachment menu toggled');
    // Implementation for attachment menu would go here
};

// Update the sendMessage function to handle conversations
const sendMessage = () => {
    if (!selectedData.value || !selectedData.value.id || !form.value.content.trim()) {
        return;
    }

    // Set form values based on selected group
    form.value.group_id = selectedData.value.id;
    form.value.group_type = groupType.value;

    router.post('/messaging/send', form.value, {
        preserveScroll: true,
        onSuccess: (page) => {
            // Create a temporary message object to add to the UI immediately
            const tempMessage = {
                id: 'temp-' + Date.now(),
                content: form.value.content,
                user: props.currentUser,
                created_at: new Date().toISOString()
            };

            // Add the new message to the top of the list
            messageData.value.unshift(tempMessage);

            // Also update the latest message in the sidebar
            if (groupType.value === 'staff' && selectedData.value) {
                const groupIndex = staffGroupsData.value.findIndex(g => g.id === selectedData.value.id);
                if (groupIndex !== -1) {
                    staffGroupsData.value[groupIndex].latest_message = tempMessage;
                    staffGroupsData.value = [...staffGroupsData.value]; // Force reactivity
                }
            } else if (groupType.value === 'batch' && selectedData.value) {
                const batchIndex = batchesData.value.findIndex(b => b.id === selectedData.value.id);
                if (batchIndex !== -1) {
                    batchesData.value[batchIndex].latest_message = tempMessage;
                    batchesData.value = [...batchesData.value]; // Force reactivity
                }
            }

            // Reset form content
            form.value.content = '';

            // Scroll to bottom
            scrollToBottom();

            // Fetch messages to get the server-generated message with proper ID
            fetchMessages();
            clearForm();
        },
        onError: (errors) => {
            console.error('Error sending message:', errors);
        }
    });
};

const clearForm = () => {
    form.value = {
        content: '',
    };
};

// Scroll to bottom of message container
const scrollToBottom = () => {
    setTimeout(() => {
        const chatContainer = document.querySelector('.overflow-y-auto');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    }, 100);
};

// Initialize Echo for real-time communication
let echo;

// Set up all event listeners for real-time messaging
const setupRealTimeListeners = () => {
    if (!echo) {
        echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
            authEndpoint: "/broadcasting/auth"
        });
    }

    // Listen for new messages in the active chat
    if (selectedData.value && selectedData.value.id) {
        const channelName = `chat.${selectedData.value.id}`;

        echo.private(channelName)
            .listen('.message.sent', (e) => {
                fetchMessages();
                console.log('New message received:', e);
                if (e.message && !messageData.value.some(m => m.id === e.message.id)) {
                    messageData.value.unshift(e.message);
                    scrollToBottom();
                }
            });
    }

    // Listen for new messages in all staff groups
    staffGroupsData.value.forEach(group => {
        echo.private(`chat.${group.id}`)
            .listen('.message.sent', (e) => {
                fetchMessages();
                console.log('New message received in staff group:', e);
                if (e.message) {
                    // Update the latest message for this group
                    const groupIndex = staffGroupsData.value.findIndex(g => g.id === group.id);
                    if (groupIndex !== -1) {
                        staffGroupsData.value[groupIndex].latest_message = e.message;
                        // Force Vue to recognize the change
                        staffGroupsData.value = [...staffGroupsData.value];
                    }
                }
            });
    });

    // Listen for new messages in all batches
    batchesData.value.forEach(batch => {
        echo.private(`chat.${batch.id}`)
            .listen('.message.sent', (e) => {
                fetchMessages();
                console.log('New message received in batch:', e);
                if (e.message) {
                    // Update the latest message for this batch
                    const batchIndex = batchesData.value.findIndex(b => b.id === batch.id);
                    if (batchIndex !== -1) {
                        batchesData.value[batchIndex].latest_message = e.message;
                        // Force Vue to recognize the change
                        batchesData.value = [...batchesData.value];
                    }
                }
            });
    });
};

// Update the fetchMessages function to handle only group messages
const fetchMessages = async () => {
    if (!selectedData.value || !selectedData.value.id) return;

    let url;
    if (groupType.value === 'batch') {
        url = route("messaging.batch", { batch: selectedData.value.id });
    } else if (groupType.value === 'staff') {
        url = route("messaging.staff", { staffGroup: selectedData.value.id });
    } else {
        return;
    }

    try {
        // Use Inertia's visit method instead of direct axios call
        router.visit(url, {
            method: 'get',
            preserveState: true,
            preserveScroll: true,
            only: ['messages'],
            onSuccess: (page) => {
                console.log('Page data received:', page.props);
                if (page.props.messages) {
                    messageData.value = page.props.messages;
                    scrollToBottom();
                }
            }
        });
    } catch (error) {
        console.error('Error fetching messages:', error);
    }
};

// Clean up event listeners when component is unmounted
const cleanupListeners = () => {
    if (echo) {
        if (selectedData.value && selectedData.value.id) {
            const channelName = `private-chat.${selectedData.value.id}`;
            echo.leave(channelName);
        }

        staffGroupsData.value.forEach(group => {
            echo.leave(`private-chat.${group.id}`);
        });

        batchesData.value.forEach(batch => {
            echo.leave(`private-chat.${batch.id}`);
        });
    }
};

onMounted(() => {
    // Initial setup
    setupRealTimeListeners();
    scrollToBottom();
});

watch([selectedData, groupType], ([newSelectedData, newGroupType], [oldSelectedData, oldGroupType]) => {
    if (newSelectedData && newSelectedData.id && newGroupType) {
        form.value.group_id = newSelectedData.id;
        form.value.group_type = newGroupType;

        // Only fetch messages if the selection has actually changed
        if (!oldSelectedData || oldSelectedData.id !== newSelectedData.id || oldGroupType !== newGroupType) {
            fetchMessages();
        }

        // Reset listeners to include the new selected chat
        cleanupListeners();
        setupRealTimeListeners();
    }
});

// Clean up when component is unmounted
onUnmounted(() => {
    cleanupListeners();
});
</script>