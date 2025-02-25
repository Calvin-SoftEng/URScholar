<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Echo from 'laravel-echo';
import { format, isToday } from 'date-fns';
import { Tooltip } from 'primevue';

const props = defineProps({
    messages: Array,
    currentUser: Object, // Pass the authenticated user object from Laravel
});

const form = useForm({
    content: '', // Initialize the content for the message input
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return isToday(date) ? format(date, 'HH:mm') : format(date, 'dd MMM');
};

// Function to send a message
const sendMessage = () => {
    form.post('/messages', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset(); // Clear the input after sending the message
            console.log('New message!!!!:', form.content);
        },
        onError: (errors) => {
            console.error('Validation errors:', errors);
        },
    });
};

// Set up real-time listener using Laravel Echo
onMounted(() => {
    const echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        
    });
    console.log('Connected!', form.content);
    // Listen for the "MessageSent" event on the 'chat' channel
    echo.channel('chat')
        .listen('MessageSent', (e) => {
            // When a new message is received, prepend it to the messages array
            // This will ensure it shows up at the top of the chat
            messages.value.push(e.message); // Add the new message at the top
        });
});
</script>

<template>

    <Head title="Chat" />

    <AuthenticatedLayout>
        <template #default>
            <div class="w-full h-full bg-dirtywhite">
                <div class="px-48 border-box w-full h-full flex flex-row bg-dirtywhite">
                    <div class="bg-dirtywhite py-5 w-[5%] h-full">
                        <div class="flex flex-col items-center justify-center">
                            <font-awesome-icon :icon="['fas', 'comment']" class="items-center justify-center w-8 h-8 text-[30px] text-primary p-3 bg-gray-200 rounded-lg" />
                            <font-awesome-icon :icon="['fas', 'users-rectangle']" class="items-center justify-center w-8 h-8 text-[30px] text-gray-600 p-3 rounded-lg hover:bg-gray-100" />
                            <span class="block w-full h-[2px] bg-gray-300 my-3"></span>
                            <font-awesome-icon :icon="['fab', 'google-scholar']" class="items-center justify-center w-7 h-7 text-[30px] text-primary p-3.5 bg-white rounded-lg shadow-md mb-3 hover:bg-gray-100" v-tooltip="'Scholarships'" />
                            <font-awesome-icon :icon="['fab', 'google-scholar']" class="items-center justify-center w-7 h-7 text-[30px] text-primary p-3.5 bg-white rounded-lg shadow-md mb-3 hover:bg-gray-100" />
                        </div>
                    </div>
                    <div class="bg-dirtywhite w-[95%] p-4 h-full">  
                        <div class="bg-white w-full h-full rounded-xl flex flex-row">
                            <div class="w-[30%] border-r">
                                <h3 class="text-xl text-primary mb-1 px-4 pt-4 pb-0 font-poppins font-extrabold ">Messages</h3>
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
                                        <input type="search" id="default-search"
                                            class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Search group chats and scholars" required />
                                    </div>
                                </form>
                                <!-- people gc, etc -->
                                <ul>
                                    <li class="w-full flex items-center space-x-2 mb-2 hover:bg-gray-100 p-4">
                                        <img src="https://placehold.co/50" alt="Person" class="h-8 w-8 rounded-full" />
                                        <span class="text-primary-foreground font-quicksand font-semibold text-lg">John Doe
                                            Dimacatacutan</span>
                                    </li>
                                    <li class="w-full flex items-center space-x-2 mb-2 hover:bg-gray-100 p-4">
                                        <img src="https://placehold.co/50" alt="Person" class="h-8 w-8 rounded-full" />
                                        <span class="text-primary-foreground font-quicksand font-semibold text-lg">John Doe
                                            Dimacatacutan</span>
                                    </li>
                                </ul>

                            </div>
                            <div class="w-[70%] h-full flex flex-col space-y-3">
                                <div class="shadow-sm mb-4 p-4">
                                    <h3 class="text-lg font-bold text-primary">Conversation</h3>
                                </div>
                                <div class="flex-1 px-2 overflow-y-auto overscroll-contain inset-shadow-sm">
                                    <div class="flex items-start justify-end gap-2.5" v-for="message in messages" :key="message.id">
                                        <div class="flex flex-col gap-1 w-full justify-end max-w-[320px]">
                                            <div class="flex justify-end items-center space-x-2 rtl:space-x-reverse">
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ message.user.first_name }}</span>
                                            </div>
                                            <div class="flex flex-col leading-1.5 p-4 border-gray-200 bg-gray-100 rounded-s-xl rounded-ee-xl dark:bg-gray-700">
                                                <p class="text-sm font-normal text-gray-900 dark:text-white"> {{ message.content }} </p>
                                            </div>
                                            <div class="flex justify-end items-center space-x-2 rtl:space-x-reverse">
                                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Delivered</span>
                                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ formatDate(message.created_at) }}</span>
                                            </div>
                                        </div>
                                        <img class="w-8 h-8 rounded-full mt-6 border" src="/docs/images/people/profile-picture-3.jpg" alt="Jese image">
                                    </div>
                                </div>
                                <div class="flex items-center box-border p-2 bg-white z-100 shadow-[0_-2px_5px_rgba(0,0,0,0.1)]">
                                    <button class="px-2"
                                        @click="sendMessage">
                                        <font-awesome-icon :icon="['fas', 'circle-plus']" class="w-6 h-6 text-primary hover:text-primary/80 hover:[transform:rotate(95deg)] transition" />
                                    </button>
                                    <input type="text" placeholder="Type your message..."
                                        class="flex-1 bg-transparent text-primary-foreground p-2 focus:outline-none focus:ring-0 border-none"
                                        v-model="form.content" @keyup.enter="sendMessage" />
                                        <button class="px-2 transition duration-200 group" @click="sendMessage">
                                            <font-awesome-icon :icon="['far', 'paper-plane']"
                                                class="w-6 h-6 text-primary group-hover:hidden" />
                                            <font-awesome-icon :icon="['fas', 'paper-plane']"
                                                class="w-6 h-6 text-primary hidden group-hover:inline-block" />
                                        </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>
</template>
