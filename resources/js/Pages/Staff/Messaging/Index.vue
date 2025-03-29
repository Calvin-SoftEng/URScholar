<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Props received from controller
const props = defineProps({
    scholarships: Array
});

const searchQuery = ref('');

const filteredScholarships = computed(() => {
    if (!searchQuery.value) return props.scholarships;

    return props.scholarships.filter(scholarship =>
        scholarship.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const formatTime = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};
</script>

<template>

    <Head title="My Scholarships" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Scholarship Groups
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Search bar -->
                    <div class="p-4 border-b">
                        <input v-model="searchQuery" type="text" placeholder="Search groups..."
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <!-- Group list -->
                    <div class="divide-y">
                        <div v-if="filteredScholarships.length === 0" class="text-center py-12 text-gray-500">
                            <p v-if="searchQuery">No scholarship groups found matching "{{ searchQuery }}"</p>
                            <p v-else>You are not part of any scholarship groups yet.</p>
                        </div>

                        <Link v-for="scholarship in filteredScholarships" :key="scholarship.id"
                            :href="route('grouppage.show', scholarship.id)" class="block hover:bg-gray-50">
                        <div class="p-4 flex">
                            <div class="flex-shrink-0 mr-4">
                                <!-- Avatar placeholder -->
                                <div
                                    class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 font-semibold text-lg">
                                    {{ scholarship.name.charAt(0) }}
                                </div>
                            </div>

                            <div class="flex-grow">
                                <div class="flex justify-between">
                                    <h3 class="font-semibold text-gray-900">{{ scholarship.name }}</h3>
                                    <span class="text-sm text-gray-500" v-if="scholarship.latest_message">
                                        {{ formatTime(scholarship.latest_message.created_at) }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 truncate" v-if="scholarship.latest_message">
                                    <span class="font-medium">{{ scholarship.latest_message.user.name }}:</span>
                                    {{ scholarship.latest_message.content }}
                                </p>
                                <p class="text-sm text-gray-400 italic" v-else>No messages yet</p>

                                <div class="mt-1 flex justify-between">
                                    <span class="text-xs text-gray-500">{{ scholarship.users_count }} members</span>
                                </div>
                            </div>
                        </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>