<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout :branding="branding">
        <div class="bg-dirtywhite dark:bg-dprimary p-6 h-full w-full space-y-2">
            <div>
                <h1 class="text-2xl font-bold mb-5 dark:text-dtext">Campus</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of the University's campuses. You can add and edit campuses here, and assign
                stakeholders.
            </p>
            <div class="w-full mt-5">
                <div v-if="!isTableVisible" class="bg-white dark:bg-dcontainer relative overflow-x-auto border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between pb-4 bg-white dark:bg-dcontainer m-5">
                        <h1 class="text-xl font-semibold font-quicksand text-dprimary dark:text-dtext">
                            University of Rizal System
                        </h1>
                        <button @click="toggleTable"
                            class="btn bg-primary text-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary">
                            Add Campus
                        </button>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-lg">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-dnavy dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Campus
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Location
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Scholarship Coordinator
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Campus Cashier
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    <span class="sr-only">Image</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="campus in campuses" :key="campus.id">
                                <tr class="bg-white border-b dark:bg-dcontainer dark:border-gray-700 border-gray-200">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ campus.name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ campus.location }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ campus.coordinator ? campus.coordinator.first_name + ' ' +
                                            campus.coordinator.last_name : 'Not Assigned' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ campus.cashier ? campus.cashier.first_name + ' ' + campus.cashier.last_name :
                                            'Not Assigned' }}
                                    </td>
                                    <td class="px-6 py-4 flex flex-row gap-2">
                                        <button @click="toggleEdit(campus)" v-tooltip.right="'Edit Campus'">
                                            <span
                                                class="material-symbols-rounded p-2 font-medium text-white dark:text-blue-500 bg-blue-900 rounded-lg">
                                                edit
                                            </span>
                                        </button>
                                        <button @click="toggleAssign(campus.id, campus.name)"
                                            v-tooltip.right="'Assign Focal Person'">
                                            <span
                                                class="material-symbols-rounded p-2 font-medium text-white dark:text-blue-500 bg-yellow-400 rounded-lg">
                                                assignment_ind
                                            </span>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div v-if="isTableVisible"
                    class="relative overflow-x-auto border bg-white dark:bg-dprimary border-gray-200 rounded-lg p-4">
                    <form @submit.prevent="submitForm">
                        <div class="flex w-full items-end justify-end">
                            <button type="button" @click="toggleTable"
                                class="text-primary bg-white border border-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                Back
                            </button>
                            <button type="submit"
                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                Save
                            </button>
                        </div>
                        <div class="flex flex-col w-full gap-4">
                            <h1 class="text-lg font-bold font-quicksand text-dprimary dark:text-dtext">
                                Enter the Campus Details
                            </h1>
                            <div>
                                <label for="first_name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Campus
                                    Name</label>
                                <input type="text" id="first_name" v-model="form.name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Binangonan, Campus" required />
                            </div>
                            <div>
                                <label for="last_name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location</label>
                                <input type="text" id="last_name" v-model="form.location"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Street" required />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Campus Modal -->
        <div v-if="isEditing"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">Edit Campus: {{ editForm.name }}</h2>
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

                <form @submit.prevent="submitEditForm" class="p-4 flex flex-col gap-3">
                    <div class="w-full flex flex-col space-y-2">
                        <label for="edit_campus_name" class="font-semibold text-gray-900 dark:text-white">Campus
                            Name</label>
                        <input id="edit_campus_name" v-model="editForm.name" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Campus Name" required />
                    </div>
                    <div class="w-full flex flex-col space-y-2">
                        <label for="edit_campus_location"
                            class="font-semibold text-gray-900 dark:text-white">Location</label>
                        <input id="edit_campus_location" v-model="editForm.location" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Campus Location" required />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Update Campus</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Assign Focal Person Modal -->
        <div v-if="Assigning"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">Assign Focal Person for {{ campusName }}</h2>
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

                <form @submit.prevent="submitAssign" class="p-4 flex flex-col gap-3">
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship Coordinator</h3>
                        <select v-model="assign.coor_id" id="scholarshipType"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                            <option value="" disabled>Coordinator List</option>
                            <option v-for="coordinator in availableCoordinators" :key="coordinator.id"
                                :value="coordinator.id">
                                {{ coordinator.first_name }} {{ coordinator.last_name }}
                            </option>
                        </select>
                    </div>
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Campus Cashier</h3>
                        <select v-model="assign.cashier_id" id="scholarshipType"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                            <option value="" disabled>Cashier List</option>
                            <option v-for="cash in availableCashiers" :key="cash.id" :value="cash.id">
                                {{ cash.first_name }} {{ cash.last_name }}
                            </option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Assign</button>
                    </div>
                </form>
            </div>
        </div>
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
import { ref, computed,watchEffect } from 'vue';
import { Tooltip } from 'primevue';
import { User } from 'lucide-vue-next';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';

const props = defineProps({
    campuses: Array,
    coor: Array,
    cashier: Array,
    branding: Object,
});

const isTableVisible = ref(false);
const isEditing = ref(false);

const toggleTable = () => {
    isTableVisible.value = !isTableVisible.value;
};

const cancel = () => {
    isTableVisible.value = !isTableVisible.value;
};

const Assigning = ref(false);
const campusid = ref(null);
const campusName = ref('');
const currentCampus = ref(null);

// Form for editing campus
const editForm = ref({
    id: null,
    name: '',
    location: '',
});

const toggleEdit = (campus) => {
    editForm.value = {
        id: campus.id,
        name: campus.name,
        location: campus.location
    };
    isEditing.value = true;
};

const toggleAssign = (campusID, name) => {
    Assigning.value = !Assigning.value;

    if (Assigning.value) {
        campusid.value = campusID;
        campusName.value = name;
        assign.value.campus_id = campusID;

        // Find the current campus to check assigned coordinators and cashiers
        currentCampus.value = props.campuses.find(campus => campus.id === campusID);
    }
};

// Filter coordinators that match the current campus
const availableCoordinators = computed(() => {
    if (!currentCampus.value) return [];

    return props.coor.filter(coordinator => {
        // Only include coordinators assigned to this campus
        return coordinator.campus_id === currentCampus.value.id ||
            // Or if they're already the coordinator for this campus
            (currentCampus.value.coordinator && currentCampus.value.coordinator.id === coordinator.id);
    });
});

// Filter cashiers that match the current campus
const availableCashiers = computed(() => {
    if (!currentCampus.value) return [];

    return props.cashier.filter(cashier => {
        // Only include cashiers assigned to this campus
        return cashier.campus_id === currentCampus.value.id ||
            // Or if they're already the cashier for this campus
            (currentCampus.value.cashier && currentCampus.value.cashier.id === cashier.id);
    });
});

const closeModal = () => {
    Assigning.value = false;
    isTableVisible.value = false;
    isEditing.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = { id: null, name: '', location: '' };
    editForm.value = { id: null, name: '', location: '' };
    assign.value = { campus_id: null, coor_id: null, cashier_id: null };
    currentCampus.value = null;
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
        router.post("/system_admin/univ-settings/campuses/store", form.value);
        closeModal();
    } catch (error) {
        console.error("Error submitting form:", error);
    }
};

const submitEditForm = async () => {
    try {
        router.post("/system_admin/univ-settings/campuses/update", editForm.value);
        closeModal();
    } catch (error) {
        console.error("Error updating campus:", error);
    }
};

const submitAssign = async () => {
    try {
        router.post("/system_admin/univ-settings/campuses/assign", assign.value);
        closeModal();
    } catch (error) {
        console.error("Error submitting form:", error);
    }
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