<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout :branding="branding">
        <div class="bg-dirtywhite dark:bg-dprimary p-6 h-full w-full space-y-2">
            <div>
                <h1 class="text-2xl font-bold mb-5 dark:text-dtext">System Users</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of the University's campuses. You can add and edit campuses here, and assign
                stakeholders.
            </p>
            <div class="w-full mt-5">

                <div
                    class="p-3 flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 ">
                    <div>
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="text" id="table-search-users" v-model="searchQuery"
                                class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search for users">
                        </div>
                    </div>
                    <div>
                        <button @click="toggleAddUser"
                            class="btn bg-primary text-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary">
                            Add User
                        </button>
                    </div>
                </div>

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

                <!-- Main Container -->
                <div
                    class="bg-white dark:bg-dcontainer relative h-full overflow-x-auto overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-b-lg border-t-0">

                    <!-- Loop Through Roles -->
                    <div v-for="menu in menuItems" :key="menu.key" v-show="selectedMenu === menu.key">
                        <div class="relative overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-dcontainer dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">User Name</th>
                                        <th scope="col" class="px-6 py-3">
                                            {{ menu.key === 'student' ? 'Scholarship' : 'Role' }}
                                        </th>
                                        <th scope="col" class="px-6 py-3">Campus</th>
                                        <th scope="col" class="sr-only px-6 py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="user in filteredUsers" :key="user.id">
                                        <tr
                                            class="bg-white border-b dark:bg-dcontainer dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <!-- User Info -->
                                            <th scope="row"
                                                class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                                <img class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer"
                                                    :src="`/storage/user/profile/${user.picture || 'no_userpic.png'}`"
                                                    alt="User Avatar"
                                                    @error="($event) => $event.target.src = '/storage/user/profile/no_userpic.png'" />

                                                <div class="ps-3">
                                                    <div class="text-base font-semibold">{{ user.first_name }}</div>
                                                    <div class="font-normal text-gray-500">{{ user.email }}</div>
                                                </div>
                                            </th>

                                            <!-- Role or Scholarship -->
                                            <td class="px-6 py-4">
                                                {{ getRoleName(user.usertype) }}
                                            </td>

                                            <!-- Campus -->
                                            <td class="px-6 py-4">
                                                {{ user.campus?.name || 'No Campus' }}
                                            </td>

                                            <!-- Actions -->
                                            <td class="px-6 py-4">
                                                <button v-if="selectedMenu === 'student'"
                                                    @click="showDeactivateModal(user)"
                                                    v-tooltip.right="'Deactivate User'">
                                                    <span
                                                        class="material-symbols-rounded p-2 font-medium text-white dark:text-red-500 bg-red-900 rounded-lg">
                                                        block
                                                    </span>
                                                </button>

                                                <button v-else @click="editUser(user)" v-tooltip.right="'Edit User'">
                                                    <span
                                                        class="material-symbols-rounded p-2 font-medium text-white dark:text-blue-500 bg-blue-900 rounded-lg">
                                                        edit
                                                    </span>
                                                </button>

                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <!-- adding user -->
        <div v-if="AddUser"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                            {{ EditUser ? 'Edit User' : 'Add New User' }}
                        </h2>
                    </span>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="EditUser ? updateUser() : submitAssign()" class="p-4 flex flex-col gap-3">
                    <div class="w-full flex flex-row gap-2">
                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                First Name</h3>
                            <InputError v-if="errors?.first_name" :message="errors.first_name"
                                class="text-2xs text-red-500" />
                            <input v-model="form.first_name" type="text" id="firstname"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                        </div>
                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                Last Name</h3>
                            <InputError v-if="errors?.last_name" :message="errors.last_name"
                                class="text-2xs text-red-500" />
                            <input v-model="form.last_name" type="text" id="lastname"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                        </div>
                    </div>
                    <div class="w-full flex flex-row gap-2">
                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Campus</h3>
                            <InputError v-if="errors?.campus_id" :message="errors.campus_id"
                                class="text-2xs text-red-500" />
                            <select v-model="form.campus_id" id="campusSelect"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                                <option value="" disabled>Select Campus</option>
                                <option v-for="campus in campuses" :key="campus.id" :value="campus.id">{{ campus.name }}
                                </option>
                            </select>
                        </div>
                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Role</h3>
                            <InputError v-if="errors?.role" :message="errors.role" class="text-2xs text-red-500" />
                            <select v-model="form.role" id="roleSelect"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                                <option value="" disabled>Select Role</option>
                                <option v-if="form.role === 'system_admin'" value="system_admin">System Admin</option>
                                <option value="super_admin">Head Administrator</option>
                                <option value="coordinator">Coordinator</option>
                                <option value="cashier">Cashier</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Email</h3>
                        <InputError v-if="errors?.email" :message="errors.email" class="text-2xs text-red-500" />
                        <input v-model="form.email" type="email" id="email"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            {{ EditUser ? 'Update' : 'Register' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- adding user -->
        <div v-if="EditUser"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                            {{ EditUser ? 'Edit User' : 'Add New User' }}
                        </h2>
                    </span>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="EditUser ? updateUser() : submitAssign()" class="p-4 flex flex-col gap-3">
                    <div class="w-full flex flex-row gap-2">
                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                First Name</h3>
                            <input v-model="form.first_name" type="text" id="firstname"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                        </div>
                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                Last Name</h3>
                            <input v-model="form.last_name" type="text" id="lastname"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                        </div>
                    </div>
                    <div class="w-full flex flex-row gap-2">
                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Campus</h3>
                            <select v-model="form.campus_id" id="campusSelect"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                                <option value="" disabled>Select Campus</option>
                                <option v-for="campus in campuses" :key="campus.id" :value="campus.id">{{ campus.name }}
                                </option>
                            </select>
                        </div>
                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Role</h3>
                            <select v-model="form.role" id="roleSelect"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                                <option value="" disabled>Select Role</option>
                                <option v-if="form.role === 'system_admin'" value="system_admin">System Admin</option>
                                <option value="super_admin">Head Administrator</option>
                                <option value="coordinator">Coordinator</option>
                                <option value="cashier">Cashier</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Email</h3>
                        <input v-model="form.email" type="email" id="email"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                    </div>

                    <div class="w-full h-1 bg-gray-200 dark:bg-gray-700 my-2"></div>
                    <div class="mt-2 flex justify-between space-x-2">
                        <button v-if="EditUser" type="button" @click="deactivateFromEdit"
                            class="flex-1 text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Deactivate
                        </button>
                        <button type="submit"
                            class="flex-1 text-white font-sans bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            {{ EditUser ? 'Update' : 'Register' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <Teleport to="body">
            <div v-if="openDeactivateModal"
                class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50">
                <div class="bg-white dark:bg-dcontainer p-6 rounded-lg w-full max-w-md space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Confirm Deactivation</h3>
                    <p class="text-gray-600 dark:text-gray-300">Are you sure you want to deactivate this user?</p>
                    <div class="flex justify-end gap-2 mt-4">
                        <button @click="openDeactivateModal = false"
                            class="px-4 py-2 rounded-lg border dark:border-gray-500 dark:text-white text-gray-700 hover:bg-gray-100 dark:hover:bg-dsecondary">
                            Cancel
                        </button>
                        <button @click="deactivateUser"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
                            Yes, Deactivate
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed, watchEffect } from 'vue';
import InputError from '@/Components/InputError.vue';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';

const props = defineProps({
    errors: Object,
    campuses: Array,
    users: Array,
    userType: String,
    coordinatorCampus: String,
    branding: Object,
});

const searchQuery = ref('');

const filteredUsers = computed(() => {
    if (!props.users) return [];

    // First filter by user type based on selected menu
    let filtered = props.users;

    // Filter users based on the selected tab
    if (selectedMenu.value === "all") {
        // All users - no filtering
    } else if (selectedMenu.value === "mis") {
        // University MIS tab should show system_admin users
        filtered = filtered.filter(user => user.usertype === "system_admin");
    } else if (selectedMenu.value === "super_admin") {
        // Head Admin tab
        filtered = filtered.filter(user => user.usertype === "super_admin");
    } else if (selectedMenu.value === "coordinator") {
        // Coordinators tab
        filtered = filtered.filter(user => user.usertype === "coordinator");
    } else if (selectedMenu.value === "cashier") {
        // Cashiers tab - show both cashier and head_cashier types
        filtered = filtered.filter(user =>
            user.usertype === "cashier" || user.usertype === "head_cashier"
        );
    } else if (selectedMenu.value === "student") {
        // Scholars tab (students)
        filtered = filtered.filter(user => user.usertype === "student");
    }

    // Then apply search filtering if there's a search query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();

        filtered = filtered.filter(user =>
            user.first_name?.toLowerCase().includes(query) ||
            user.last_name?.toLowerCase().includes(query) ||
            user.email?.toLowerCase().includes(query) ||
            user.campus?.name?.toLowerCase().includes(query) ||
            getRoleName(user.usertype).toLowerCase().includes(query)
        );
    }

    return filtered;
});

const AddUser = ref(false);
const EditUser = ref(false);

const toggleAddUser = () => {
    AddUser.value = !AddUser.value;
};

const toggleEditUser = () => {
    EditUser.value = !EditUser.value;
};

const openDeactivateModal = ref(false);

const deactivateUser = () => {
    if (selectedUser.value) {
        router.put(`/system_admin/user-settings/users/${selectedUser.value.id}/deactivate`, {}, {
            onSuccess: () => {
                openDeactivateModal.value = false;
                // Optionally show a success message or toast notification
            },
            onError: (errors) => {
                console.error('Failed to deactivate user:', errors);
                // Optionally show an error message
            }
        });
    }
};
// In the edit form's deactivate button action
// Replace the deactivate button click handler in the edit form
const deactivateFromEdit = () => {
    selectedUser.value = { id: form.value.id }; // Store just the ID of the user being edited
    openDeactivateModal.value = true; // Open the same confirmation modal
    closeModal(); // Close the edit modal to prevent UI stacking
};

const selectedUser = ref(null);

const showDeactivateModal = (user) => {
    selectedUser.value = user;
    openDeactivateModal.value = true;
};

// const deactivateUser = () => {
//     if (selectedUser.value) {
//         console.log("Deactivating user:", selectedUser.value);
//         // Add your API call or logic here

//         openDeactivateModal.value = false;
//     }
// };

const closeModal = () => {
    AddUser.value = false;
    EditUser.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = {
        id: null,
        first_name: '',
        last_name: '',
        email: '',
        campus_id: '',
        role: ''
    };
};

const form = ref({
    id: null,
    first_name: '',
    last_name: '',
    email: '',
    campus_id: '',
    role: ''
});

const getRoleName = (role) => {
    switch (role) {
        case 'system_admin': return 'System Admin';
        case 'super_admin': return 'Head Administrator';
        case 'coordinator': return 'Coordinator';
        case 'head_cashier': return 'University Cashier';
        case 'cashier': return 'Cashier';
        case 'student': return 'Scholar';
        default: return role;
    }
};

const editUser = (user) => {
    form.value = {
        id: user.id,
        first_name: user.first_name,
        last_name: user.last_name,
        email: user.email,
        campus_id: user.campus_id,
        role: user.usertype
    };
    EditUser.value = true;
};

const updateUser = async () => {
    try {
        await router.put(`/system_admin/user-settings/users/${form.value.id}/update`, form.value, {
            onSuccess: () => {
                closeModal();
            },
        });
    } catch (error) {
        console.error("Error updating user:", error);
    }
};

const submitAssign = async () => {
    try {
        await router.post("/system_admin/user-settings/users/create", form.value, {
            onSuccess: () => {
                closeModal();
            },
        });
    } catch (error) {
        console.error("Error submitting form:", error);
    }
};


// Filtered logs based on selected menu

// Updated menu items to match user types
const menuItems = [
    { name: "All Users", key: "all" },
    { name: "University MIS", key: "mis" },
    { name: "Head Admin", key: "super_admin" },
    { name: "Coordinators", key: "coordinator" },
    { name: "Cashiers", key: "cashier" },
    { name: "Scholars", key: "student" },
];
// Track the selected menu
const selectedMenu = ref("all");

// Function to change the selected menu
const selectMenu = (key) => {
    selectedMenu.value = key;
};


const toastVisible = ref(false);
const toastMessage = ref("");

watchEffect(() => {
    const flashMessage = usePage().props.flash?.success;

    if (flashMessage) {
        console.log("Showing toast with message:", flashMessage);
        toastMessage.value = flashMessage;
        toastVisible.value = true;

        setTimeout(() => {
            console.log("Hiding toast...");
            toastVisible.value = false;
        }, 3000);
    }
});
</script>
