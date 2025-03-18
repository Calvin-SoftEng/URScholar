<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite p-6 h-full w-full space-y-2">
            <div>
                <h1 class="text-2xl font-bold mb-5">Stakeholders</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of the University's campuses. You can add and edit campuses here, and assign
                stakeholders.
            </p>
            <div class="w-full mt-5">

                <div class="relative overflow-x-auto border border-gray-200 rounded-lg">
                    <div
                        class="p-3 flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
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
                                <input type="text" id="table-search-users"
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
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    User Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Role
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Campus
                                </th>
                                <th scope="col" class="sr-only px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="user in users" :key="user.id">
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row"
                                        class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div v-if="$page.props.auth.user.picture">
                                            <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown"
                                                data-dropdown-placement="bottom-start"
                                                class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer"
                                                :src="`/storage/user/profile/${$page.props.auth.user.picture}`"
                                                alt="picture">
                                        </div>
                                        <div v-else>
                                            <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown"
                                                data-dropdown-placement="bottom-start"
                                                class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer"
                                                :src="`/storage/user/profile/male.png`" alt="picture">
                                        </div>
                                        <div class="ps-3">
                                            <div class="text-base font-semibold">{{ user.first_name }}</div>
                                            <div class="font-normal text-gray-500">{{ user.email }}</div>
                                        </div>
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ getRoleName(user.usertype) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ user.campus.name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <button @click="editUser(user)" class="" v-tooltip.right="'Edit User'">
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


        <!-- adding user -->
        <div v-if="AddUser || EditUser"
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
                                <option
                                    v-if="form.role === 'system_admin'"
                                    value="system_admin">System Admin</option>
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
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            {{ EditUser ? 'Update' : 'Register' }}
                        </button>
                    </div>
                </form>
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
    users: Array,
});

const cancel = () => {
    isTableVisible.value = !isTableVisible.value;
};

const AddUser = ref(false);
const EditUser = ref(false);

const campusid = ref(null);

const toggleAddUser = () => {
    AddUser.value = !AddUser.value;
};

const toggleEditUser = () => {
    EditUser.value = !EditUser.value;
};

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

// Updated form structure with id field for editing
const form = ref({
    id: null,
    first_name: '',
    last_name: '',
    email: '',
    campus_id: '',
    role: ''
});

// Function to display role name based on role value
const getRoleName = (role) => {
    switch (role) {
        case 'system_admin': return 'System Admin';
        case 'super_admin': return 'Head Administrator';
        case 'coordinator': return 'Coordinator';
        case 'cashier': return 'Cashier';
        default: return role;
    }
};

// Function to edit user
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

// Function to update user
const updateUser = async () => {
    try {
        router.put(`/system_admin/user-settings/users/${form.value.id}/update`, form.value);
        closeModal();
    } catch (error) {
        console.error("Error updating user:", error);
    }
};

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
        router.post("/system_admin/user-settings/users/create", form.value);
        closeModal();
    } catch (error) {
        console.error("Error submitting form:", error);
    }
};
</script>