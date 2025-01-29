<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Echo from 'laravel-echo';
import { format, isToday } from 'date-fns';

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
            <div class="grid grid-cols-4 bg-[#F8F8FA] px-40 h-full">
                <div class="bg-white col-span-2 h-full flex flex-col bg-card border overflow-visible">
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
                            <!-- <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button> -->
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
                <div class="bg-white col-span-2 h-full flex flex-col space-y-3 bg-card border">
                    <h3 class="text-lg font-bold text-primary mb-4 p-4">Conversation</h3>
                    <!-- <div class="flex-1 px-2 overflow-y-auto overscroll-contain">
                        <div>
                            <div :class="[
                                'flex items-start mb-3',
                                message.user.id === currentUser.id ? 'justify-end' : 'justify-start'
                            ]" v-for="message in messages" :key="message.id">
                                <div :class="[
                                    'flex flex-col',
                                    message.user.id === currentUser.id ? 'items-end' : 'items-start'
                                ]">
                                    <p class="text-sm text-gray-500 mb-1">{{ message.user.first_name }}</p>
                                    <div :class="[
                                        'bg-primary', 
                                        'text-white', 
                                        'px-2.5 py-2', 
                                        'rounded-xl', 
                                        'max-w-xs', 
                                        message.user.id === currentUser.id ? 'ml-2' : 'mr-2'
                                    ]">
                                        {{ message.content }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ formatDate(message.created_at) }}
                                    </p>
                                </div>
                                <img src="https://placehold.co/50" alt="Person" class="h-8 w-8 rounded-full self-end ml-2" />
                            </div>
                        </div>
                    </div> -->
                    <div class="flex-1 px-2 overflow-y-auto overscroll-contain">
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
                        <!-- <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" data-dropdown-placement="bottom-start" class="inline-flex self-center items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800 dark:focus:ring-gray-600" type="button">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                            </svg>
                        </button>
                        <div id="dropdownDots" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-40 dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMenuIconButton">
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Reply</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Forward</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Copy</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Report</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Delete</a>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                    </div>
                    <div class="flex items-center shadow-md box-border p-2 bg-black z-100">
                        <input type="text" placeholder="Type your message..."
                            class="flex-1 bg-input text-primary-foreground p-2 rounded-lg mr-2"
                            v-model="form.content" @keyup.enter="sendMessage" />
                        <button
                            class="bg-primary text-primary-foreground px-4 py-2 rounded-lg hover:bg-primary/80 transition"
                            @click="sendMessage">Send</button>
                    </div>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>
</template>