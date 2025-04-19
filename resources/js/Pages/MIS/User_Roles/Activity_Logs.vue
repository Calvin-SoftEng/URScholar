<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite dark:bg-dprimary p-6 h-full w-full space-y-2 ">
            <div>
                <h1 class="text-2xl font-bold mb-5 dark:text-dtext">Activity Logs</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of all the system activities. Listed are logs of each users.
            </p>
            <div class="w-full mt-5 overflow-y-auto ">

                <div class="flex w-full border-b border-gray-200 dark:border-gray-700">
                    <a v-for="item in menuItems" :key="item.key" href="#" @click.prevent="selectMenu(item.key)" :class="[
                        'flex-1 text-center whitespace-nowrap px-6 py-3 text-sm font-medium',
                        selectedMenu === item.key
                            ? 'text-blue-700 border-b-4 border-blue-700 dark:border-dnavy dark:text-white'
                            : 'text-gray-900 border-transparent hover:border-gray-200 hover:text-blue-700 dark:text-white dark:hover:bg-gray-700'
                    ]">
                        {{ item.name }}
                    </a>
                </div>

                <!-- Content Area -->
                <div
                    class="bg-white dark:bg-dcontainer relative h-full overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-b-lg border-t-0">

                    <!-- Header inside content area -->
                    <div class="flex items-center justify-between bg-white dark:bg-dcontainer m-5">
                        <h1 class="text-xl font-semibold font-quicksand text-dprimary dark:text-dtext">
                            {{ getActivitiesTitle() }}
                        </h1>
                    </div>

                    <!-- Scrollable log list -->
                    <div
                        class="max-w-3xl mx-auto bg-white dark:bg-dcontainer py-5 pr-2 overflow-y-auto"
                        style="max-height: calc(100vh - 300px);">

                        <div v-if="filteredLogs.length > 0" class="space-y-6">
                            <div v-for="(log, index) in filteredLogs" :key="index">
                                <!-- Display Day Only Once -->
                                <div class="text-gray-500 text-sm font-semibold mb-2">{{ log.date }}</div>

                                <!-- Log Entries -->
                                <div class="space-y-3">
                                    <div v-for="(entry, idx) in log.entries" :key="idx"
                                        class="grid grid-cols-[100px_auto_50px] gap-4">
                                        <!-- Time Column -->
                                        <div class="text-gray-500 text-sm whitespace-nowrap self-start pt-1">
                                            {{ entry.time }}
                                        </div>

                                        <!-- Activity Column -->
                                        <div class="text-gray-700 dark:text-gray-300 break-words leading-relaxed">
                                            <strong>{{ entry.user }}</strong> {{ entry.action }}
                                            <span class="dark:text-gray-300" :class="entry.color">{{ entry.item }}</span>.
                                        </div>

                                        <!-- Restore Button Column -->
                                        <div class="flex justify-end self-start pt-1">
                                            <button @click="removeLog(log, idx)" v-tooltip.right="'Remove'"
                                                class="p-1 rounded-lg text-dprimary dark:text-dtext hover:bg-blue-200 transition">
                                                <span class="material-symbols-rounded font-medium">remove</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else class="flex justify-center items-center py-10">
                            <div class="text-center">
                                <span class="material-symbols-rounded text-4xl text-gray-400">history</span>
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
    { name: "University MIS", key: "mis" },
    { name: "Super Admin", key: "super_admin" },
    { name: "Coordinators", key: "coordinator" },
    { name: "Cashier", key: "cashier" },
    { name: "Scholars", key: "student" },
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