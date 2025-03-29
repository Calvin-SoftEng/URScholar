<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite p-6 h-full w-full space-y-2">
            <div>
                <h1 class="text-2xl font-bold mb-5">Activity Logs</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of all the system activities. Listed are logs of each users.
            </p>
            <div class="w-full mt-5">

                <div class="flex w-full border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
                    <a v-for="item in menuItems" :key="item.key" href="#" @click.prevent="selectMenu(item.key)" :class="[
                        'flex-1 text-center whitespace-nowrap px-6 py-3 text-sm font-medium',
                        selectedMenu === item.key
                            ? 'text-blue-700 border-b-4 border-blue-700 dark:text-white dark:border-white'
                            : 'text-gray-900 border-transparent hover:border-gray-200 hover:text-blue-700 dark:text-white dark:hover:bg-gray-700'
                    ]">
                        {{ item.name }}
                    </a>
                </div>

                <!-- Content Area -->
                <div
                    class="bg-white relative overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-b-lg border-t-0">
                    <div class="flex items-center justify-between bg-white dark:bg-gray-900 m-5">
                        <h1 class="text-xl font-semibold font-quicksand text-primary">
                            {{ getActivitiesTitle() }}
                        </h1>
                    </div>

                    <div class="max-w-3xl mx-auto bg-white py-5">
                        <div v-if="filteredLogs.length > 0" class="space-y-6">
                            <div v-for="(log, index) in filteredLogs" :key="index">
                                <!-- Display Day Only Once -->
                                <div class="text-gray-500 text-sm font-semibold mb-2">{{ log.date }}</div>

                                <div class="grid grid-cols-[15%_75%_10%] gap-4 items-center">
                                    <!-- Time Column -->
                                    <div class="flex flex-col space-y-4">
                                        <span v-for="(entry, idx) in log.entries" :key="idx"
                                            class="text-gray-500 text-sm">{{ entry.time }}</span>
                                    </div>

                                    <!-- Activity Column -->
                                    <div class="flex flex-col space-y-4">
                                        <p v-for="(entry, idx) in log.entries" :key="idx" class="text-gray-700">
                                            <strong>{{ entry.user }}</strong> {{ entry.action }} <span
                                                :class="entry.color">{{ entry.item }}</span>.
                                        </p>
                                    </div>

                                    <!-- Restore Button Column -->
                                    <div class="flex flex-col space-y-4">
                                        <button v-for="(_, idx) in log.entries" :key="idx" @click="removeLog(log, idx)"
                                            v-tooltip.right="'Remove'">
                                            <span
                                                class="material-symbols-rounded p-1 font-medium text-primary dark:text-blue-500 bg-blue-100 rounded-lg">
                                                remove
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="flex justify-center items-center py-10">
                            <div class="text-center">
                                <span class="material-symbols-rounded text-4xl text-gray-400">
                                    history
                                </span>
                                <p class="text-gray-500 mt-2">No activity logs found for this user type.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { Tooltip } from 'primevue';
import { User } from 'lucide-vue-next';

const props = defineProps({
    activity: Array,
});

// Updated menu items to match user types
const menuItems = [
    { name: "All User Activities", key: "all_users" },
    { name: "Super Admin", key: "super_admin" },
    { name: "Coordinators", key: "coordinator" },
    { name: "Cashier", key: "cashier" },
    { name: "Scholars", key: "scholar" },
];

// Track the selected menu
const selectedMenu = ref("all_users");

// Function to change the selected menu
const selectMenu = (key) => {
    selectedMenu.value = key;
};

// Format date for grouping
const formatDate = (dateString) => {
    const date = new Date(dateString);
    const options = { weekday: 'long', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
};

// Format time
const formatTime = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
};

// Process activity logs from props
const processedLogs = ref([]);

onMounted(() => {
    if (props.activity && props.activity.length > 0) {
        // Group logs by date
        const groupedByDate = {};

        props.activity.forEach(log => {
            const date = formatDate(log.created_at);
            if (!groupedByDate[date]) {
                groupedByDate[date] = { date, entries: [] };
            }

            // Determine color based on activity type
            let color = 'text-gray-700';
            if (log.description.includes('created')) color = 'text-green-500';
            if (log.description.includes('updated')) color = 'text-blue-500';
            if (log.description.includes('deleted')) color = 'text-red-500';

            groupedByDate[date].entries.push({
                time: formatTime(log.created_at),
                user: log.user ? `${log.user.first_name} ${log.user.last_name}` : 'System',
                action: log.description.split(' ')[0], // Extract first word as action
                item: log.description.substring(log.description.indexOf(' ') + 1), // Everything after first word
                color,
                userType: log.user ? log.user.usertype : 'system'
            });
        });

        // Convert to array and sort by date (newest first)
        processedLogs.value = Object.values(groupedByDate).sort((a, b) => {
            return new Date(b.date) - new Date(a.date);
        });
    } else {
        // If no data from props, use sample data
        processedLogs.value = sampleActivityLogs.value;
    }
});


// Filtered logs based on selected menu
const filteredLogs = computed(() => {
    if (selectedMenu.value === 'all_users') {
        return processedLogs.value;
    }

    // Filter logs by usertype
    return processedLogs.value.map(dayLog => {
        const filteredEntries = dayLog.entries.filter(entry =>
            entry.userType === selectedMenu.value
        );

        // Only return days that have entries after filtering
        if (filteredEntries.length > 0) {
            return {
                date: dayLog.date,
                entries: filteredEntries
            };
        }
        return null;
    }).filter(log => log !== null);
});

// Get appropriate title based on selected menu
const getActivitiesTitle = () => {
    const menuItem = menuItems.find(item => item.key === selectedMenu.value);
    return menuItem ? `${menuItem.name} Activities` : 'Activities';
};

// Remove log entry
const removeLog = (log, entryIndex) => {
    log.entries.splice(entryIndex, 1);
    // If no more entries for the day, remove the day
    if (log.entries.length === 0) {
        const dayIndex = processedLogs.value.findIndex(day => day.date === log.date);
        if (dayIndex !== -1) {
            processedLogs.value.splice(dayIndex, 1);
        }
    }
};
</script>