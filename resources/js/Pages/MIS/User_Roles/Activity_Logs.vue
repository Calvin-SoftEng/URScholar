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
                    <a v-for="item in menuItems" :key="item.key"
                        href="#"
                        @click.prevent="selectMenu(item.key)"
                        :class="[
                            'flex-1 text-center whitespace-nowrap px-6 py-3 text-sm font-medium',
                            selectedMenu === item.key
                                ? 'text-blue-700 border-b-4 border-blue-700 dark:text-white dark:border-white'
                                : 'text-gray-900 border-transparent hover:border-gray-200 hover:text-blue-700 dark:text-white dark:hover:bg-gray-700'
                        ]"
                    >
                        {{ item.name }}
                    </a>
                </div>


                <!-- Content Area -->
                <div class="bg-white relative overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-b-lg border-t-0">
                    <div class="flex items-center justify-between bg-white dark:bg-gray-900 m-5">
                        <h1 class="text-xl font-semibold font-quicksand text-primary">
                            Activities
                        </h1>
                    </div>

                    <div v-if="selectedMenu === 'all_users'" class="max-w-3xl mx-auto bg-white py-5">
                        
                        <div class="space-y-6">
                            <div v-for="(log, index) in activityLogs" :key="index">
                            <!-- Display Day Only Once -->
                            <div class="text-gray-500 text-sm font-semibold mb-2">{{ log.date }}</div>

                            <div class="grid grid-cols-[15%_75%_10%] gap-4 items-center">
                                <!-- Time & Activity Column -->
                                <div class="flex flex-col space-y-4">
                                <span v-for="(entry, idx) in log.entries" :key="idx" class="text-gray-500 text-sm">{{ entry.time }}</span>
                                </div>

                                <div class="flex flex-col space-y-4">
                                <p v-for="(entry, idx) in log.entries" :key="idx" class="text-gray-700">
                                    <strong>{{ entry.user }}</strong> {{ entry.action }} <span :class="entry.color">{{ entry.item }}</span>.
                                </p>
                                </div>

                                <!-- Restore Button Column -->
                                <div class="flex flex-col space-y-4">
                                <button v-for="(_, idx) in log.entries" :key="idx" @click="toggleEditRole" v-tooltip.right="'Remove'">
                                    <span class="material-symbols-rounded p-1 font-medium text-primary dark:text-blue-500 bg-blue-100 rounded-lg">
                                    remove
                                    </span>
                                </button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="selectedMenu === 'admin'" class="max-w-3xl mx-auto bg-white py-5">
                        
                        <div class="space-y-6">
                            <div v-for="(log, index) in activityLogs" :key="index">
                            <!-- Display Day Only Once -->
                            <div class="text-gray-500 text-sm font-semibold mb-2">{{ log.date }}</div>

                            <div class="grid grid-cols-[15%_75%_10%] gap-4 items-center">
                                <!-- Time & Activity Column -->
                                <div class="flex flex-col space-y-4">
                                <span v-for="(entry, idx) in log.entries" :key="idx" class="text-gray-500 text-sm">{{ entry.time }}</span>
                                </div>

                                <div class="flex flex-col space-y-4">
                                <p v-for="(entry, idx) in log.entries" :key="idx" class="text-gray-700">
                                    <strong>{{ entry.user }}</strong> {{ entry.action }} <span :class="entry.color">{{ entry.item }}</span>.
                                </p>
                                </div>

                                <!-- Restore Button Column -->
                                <div class="flex flex-col space-y-4">
                                <button v-for="(_, idx) in log.entries" :key="idx" @click="toggleEditRole" v-tooltip.right="'Remove'">
                                    <span class="material-symbols-rounded p-1 font-medium text-primary dark:text-blue-500 bg-blue-100 rounded-lg">
                                    remove
                                    </span>
                                </button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="inline-flex rounded-t-lg shadow-xs border-x border-gray-200 dark:border-gray-700">
                    <a href="#" aria-current="page" class="px-4 py-2 text-sm font-medium text-blue-700 bg-white border-r border-gray-200 rounded-t-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                        All User Activities
                    </a>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white rounded-t-lg border-r border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                        Admin
                    </a>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white rounded-t-lg border-r border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                        Coordinators
                    </a>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white rounded-t-lg border-r border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                        Cashiers
                    </a>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white rounded-t-lg border-r border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                        Scholars
                    </a>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white rounded-t-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                        Guest
                    </a>
                </div> -->


                <!-- adding roles -->
                <div v-if="isCreating || isEditing"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
                    <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <span class="text-xl font-semibold text-gray-900 dark:text-white">
                                <h2 class="text-2xl font-bold">
                                    {{ isEditing ? 'Edit Role' : 'Add New Role'}}
                                </h2>
                            </span>
                            <button type="button" @click="closeModal"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="default-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="submitForm" class="p-4 flex flex-col gap-3">
                            <div class="w-full flex flex-col space-y-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    Role Name</h3>
                                <input v-model="form.name" type="text" id="name"
                                    placeholder="Enter Scholarship Name"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                            </div>
                            <div class="mt-2">
                                <button type="submit"
                                    class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                                    {{ isEditing ? 'Update Role' : 'Add Role' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Tooltip } from 'primevue';
import { User } from 'lucide-vue-next';

defineProps({
    campuses: Array,
    coor: Array,
    cashier: Array,
});


const isCreating = ref(false);
const isEditing = ref(false);


const toggleAddRole = () => {
    isCreating.value = !isCreating.value;
};

const closeModal = () => {
    isCreating.value = false;
    isEditing.value = false;
    resetForm();
};

const toggleEditRole = () => {
    isEditing.value = true;
    isCreating.value = false;
    form.value = { ...scholarship };
};


const menuItems = [
    { name: "All User Activities", key: "all_users" },
    { name: "Admin", key: "admin" },
    { name: "Coordinators", key: "coordinators" },
    { name: "Sponsors", key: "sponsors" },
    { name: "Cashier", key: "cashier" },
    { name: "Scholars", key: "Scholars" },
    { name: "Guest", key: "guest" },
];

// Track the selected menu
const selectedMenu = ref("stakeholders");

// Function to change the selected menu
const selectMenu = (key) => {
    selectedMenu.value = key;
};

const activityLogs = ref([
  {
    date: "Monday, Jan 1",
    entries: [
      { time: "09:45 AM", user: "John Doe", action: "deleted", item: "report.pdf", color: "text-blue-500" },
      { time: "10:30 AM", user: "Jane Smith", action: "restored", item: "archive.zip", color: "text-green-500" }
    ]
  },
  {
    date: "Tuesday, Jan 2",
    entries: [
      { time: "11:15 AM", user: "Admin", action: "updated", item: "user roles", color: "text-gray-700" },
      { time: "01:00 PM", user: "Alex", action: "uploaded", item: "presentation.pptx", color: "text-purple-500" }
    ]
  }
]);

const resetForm = () => {
    form.value = { id: null, name: '', description: '' };
};


const form = ref({
    name: '',
    location: '',
});

const assign = ref({
    campus_id: null,
    coor_id: null,
    cashier_id: null,
});

const submitForm = async () => {
    try {
        router.post("/mis/univ-settings/campuses/store", form.value);
        closeModal();
    } catch (error) {
        console.error("Error submitting form:", error);
    }
};

const submitAssign = async () => {
    try {
        router.post("/mis/univ-settings/campuses/assign", assign.value);
        closeModal();
    } catch (error) {
        console.error("Error submitting form:", error);
    }
};

</script>