<template>
    <SettingsLayout>
        <div class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <h1 class="text-2xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                    <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>URS Scholarship Partners
                </h1>

                <div v-if="!isTableVisible && !UpdateMOA" class="flex justify-end items-center w-full gap-3">
                    <button @click="toggleTable"
                        class="btn bg-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary">
                        <span class="material-symbols-rounded dark:text-dtext">
                            library_add
                        </span>
                        New Sponsor
                    </button>

                    <div class="w-3/12">
                        <form class="max-w-md mx-auto">
                            <label for="default-search"
                                class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                            <div class="relative w-full">
                                <input type="search" id="search-dropdown"
                                    class="p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                                    placeholder="Search Scholarship" required />
                                <button type="submit"
                                    class="absolute top-0 end-0 p-2 text-sm font-medium h-full text-white bg-blue-900 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div v-if="!isTableVisible && !UpdateMOA" class="w-full mt-5">
                    <div class="relative overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-lg">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr class="dark:text-dtext dark:bg-dcontainer">
                                    <th scope="col" class="px-1 py-3">
                                        <span class="sr-only">Image</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Sponsor Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Since
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Memorandum of Agreement
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date Created
                                    </th>
                                    <th>
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="sponsor in sponsors" :key="sponsor.id">
                                    <tr class="bg-white border-b dark:bg-dcontainer dark:border-gray-700 border-gray-200">
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <img :src="`/storage/sponsor/logo/${sponsor.logo}`" alt="logo"
                                                class="w-20 h-20 rounded-full" />
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ sponsor.name }} <span>({{ sponsor.abbreviation }})</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ sponsor.since }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ sponsor.moa_file }}
                                        </td>
                                        <td class="px-6 py-4">
                                        {{ formatDate(sponsor.created_at) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <button @click="toggleupdateMOA(sponsor)"
                                                class="btn bg-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary">
                                                Update MOA
                                            </button>
                                        </td>

                                    </tr>
                                </template>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-if="isTableVisible && !UpdateMOA" class="w-full h-full space-y-5 mb-3">
                    <!-- creating -->

                    <form @submit.prevent="submitForm">
                        <div class="w-full bg-white rounded-lg dark:bg-dsecondary dark:border dark:border-gray-200 border">
                            <div class="w-full dark:bg-primary flex items-center justify-between rounded-t-lg px-6 py-4">
                                <h2 class="text-lg font-semibold text-primary">Scholarship Information</h2>
                                <div class="flex items-center gap-2">
                                    <button @click="addingcancel"
                                        class="btn bg-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary px-5">
                                        Cancel
                                    </button>
                                    <button
                                        class="btn bg-primary text-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary px-5">
                                        Publish
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 px-5 py-2 h-full">
                                <div class="col-span-1">
                                    <div class="flex flex-col w-full gap-2 h-full">
                                        <div class="w-full">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor</h3>
                                            <input v-model="form.name" type="text" id="name"
                                                placeholder="Enter a Partnership or Sponsor"
                                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                        </div>
                                        <div class="w-full grid grid-cols-2 gap-4">
                                            <div class="w-full space-y-5">
                                                <div class="w-full">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">Abbreviation
                                                    </h3>
                                                    <input v-model="form.abbreviation" type="text" id="name"
                                                        placeholder="e.g., CHED"
                                                        class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                                </div>
                                                <div class="w-full">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">Partnered Since
                                                    </h3>
                                                    <input v-model="form.since" type="text" id="name"
                                                        placeholder="e.g., Since 2012"
                                                        class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                                </div>
                                                <div class="w-full flex flex-col space-y-3">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor
                                                        Background Information</h3>
                                                    <textarea v-model="form.description" id="description"
                                                        placeholder="Enter Description"
                                                        class="textarea textarea-bordered h-64 bg-gray-50 w-full border-gray-300 dark:bg-gray-900 dark:text-dtext"></textarea>
                                                </div>
                                            </div>
                                            <div class="space-y-5">
                                                <div class="w-full flex flex-col">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Attach
                                                        Memorandum of Agreement</h3>
                                                    <label for="dropzone-file"
                                                        class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                                        :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                                        @dragover.prevent="handleFileDragOver"
                                                        @dragleave="handleFileDragLeave" @drop.prevent="handleFileDrop">
                                                        <div v-if="!form.file"
                                                            class="flex flex-col items-center justify-center pt-5 pb-6">
                                                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 20 16">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                            </svg>
                                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                                <span class="font-semibold">Click to upload</span> or drag
                                                                and drop
                                                            </p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG,
                                                                JPG, DOCX (MAX. 2MB-4MB)</p>
                                                        </div>
                                                        <div v-else class="flex flex-col items-center justify-center">
                                                            <template v-if="form.filePreview && !form.fileName.endsWith('.docx,.doc,.pdf')">
                                                                <img :src="form.filePreview" alt="Uploaded Preview" class="h-32 mb-2 rounded-lg" />
                                                            </template>
                                                            <template v-else>
                                                                <img src="../../../../assets/images/previewdocs.png" alt="Document Icon" class="h-32 mb-2" />
                                                            </template>
                                                            <p class="text-sm text-gray-500">{{ form.fileName }}</p>
                                                        </div>
                                                        <input id="dropzone-file" type="file" class="hidden"
                                                            accept=".svg, .png, .jpg, .docx"
                                                            @change="(e) => handleFile(e.target.files[0])" />
                                                    </label>
                                                </div>
                                                <div class="w-full flex flex-col">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Upload
                                                        Photo (Optional for
                                                        Displaying)</h3>
                                                    <label for="dropzone-img"
                                                        class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                                        :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                                        @dragover.prevent="handleImgDragOver"
                                                        @dragleave="handleImgDragLeave" @drop.prevent="handleImgDrop">
                                                        <div v-if="!form.img"
                                                            class="flex flex-col items-center justify-center pt-5 pb-6">
                                                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 20 16">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                            </svg>
                                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                                <span class="font-semibold">Click to upload</span> or drag
                                                                and drop
                                                            </p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG
                                                                (MAX. 800x400px - 2MB-4MB)</p>
                                                        </div>
                                                        <div v-else class="flex flex-col items-center justify-center">
                                                            <img :src="form.imgPreview" alt="Uploaded Preview"
                                                                class="max-h-24 mb-2 rounded-lg" />
                                                            <p class="text-sm text-gray-500">{{ form.imgName }}</p>
                                                        </div>
                                                        <input id="dropzone-img" type="file" class="hidden"
                                                            accept=".svg, .png, .jpg, .jpeg"
                                                            @change="(e) => handleImg(e.target.files[0])" />
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div v-if="UpdateMOA && !isTableVisible" class="w-full h-full space-y-5 mb-3">
                    <!-- creating -->
                    <form @submit.prevent="submitForm">
                        <div class="w-full bg-white rounded-lg dark:bg-dsecondary dark:border dark:border-gray-200 border">
                            <div class="w-full dark:bg-primary flex items-center justify-between rounded-t-lg px-6 py-4">
                                <h2 class="text-base font-semibold text-primary font-quicksand">Scholarship Information</h2>
                                <div class="flex items-center gap-2">
                                    <button @click="updatecancel"
                                        class="btn bg-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary px-5">
                                        Cancel
                                    </button>
                                    <button
                                        class="btn bg-primary text-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary px-5">
                                        Save
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 px-5 py-2 h-full">
                                <div class="col-span-1">
                                    <div class="flex flex-col w-full gap-2 h-full">
                                        <div class="w-full">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor</h3>
                                            <input v-model="form.name" type="text" id="name"
                                                placeholder="Enter a Partnership or Sponsor"
                                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                        </div>
                                        <div class="w-full grid grid-cols-2 gap-4">
                                            <div class="w-full space-y-5">
                                                <div class="w-full">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">Abbreviation
                                                    </h3>
                                                    <input v-model="form.abbreviation" type="text" id="name"
                                                        placeholder="e.g., CHED"
                                                        class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                                </div>
                                                <div class="w-full">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">Partnered Since
                                                    </h3>
                                                    <input v-model="form.since" type="text" id="name"
                                                        placeholder="e.g., Since 2012"
                                                        class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                                </div>
                                                <div class="w-full flex flex-col space-y-3">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor
                                                        Background Information</h3>
                                                    <textarea v-model="form.description" id="description"
                                                        placeholder="Enter Description"
                                                        class="textarea textarea-bordered h-64 bg-gray-50 w-full border-gray-300 dark:bg-gray-900 dark:text-dtext"></textarea>
                                                </div>
                                            </div>
                                            <div class="space-y-5">
                                                <div class="w-full flex flex-col">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Attach
                                                        Memorandum of Agreement</h3>
                                                    <label for="dropzone-file"
                                                        class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                                        :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                                        @dragover.prevent="handleFileDragOver"
                                                        @dragleave="handleFileDragLeave" @drop.prevent="handleFileDrop">
                                                        <div v-if="!form.file"
                                                            class="flex flex-col items-center justify-center pt-5 pb-6">
                                                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 20 16">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                            </svg>
                                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                                <span class="font-semibold">Click to upload</span> or drag
                                                                and drop
                                                            </p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG,
                                                                JPG, DOCX (MAX. 2MB-4MB)</p>
                                                        </div>
                                                        <div v-else class="flex flex-col items-center justify-center">
                                                            <template v-if="form.filePreview && !form.fileName.endsWith('.docx,.doc,.pdf')">
                                                                <img :src="form.filePreview" alt="Uploaded Preview" class="h-32 mb-2 rounded-lg" />
                                                            </template>
                                                            <template v-else>
                                                                <img src="../../../../assets/images/previewdocs.png" alt="Document Icon" class="h-32 mb-2" />
                                                            </template>
                                                            <p class="text-sm text-gray-500">{{ form.fileName }}</p>
                                                        </div>
                                                        <input id="dropzone-file" type="file" class="hidden"
                                                            accept=".svg, .png, .jpg, .docx"
                                                            @change="(e) => handleFile(e.target.files[0])" />
                                                    </label>
                                                </div>
                                                <div class="w-full flex flex-col">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Upload
                                                        Photo (Optional for
                                                        Displaying)</h3>
                                                    <label for="dropzone-img"
                                                        class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                                        :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                                        @dragover.prevent="handleImgDragOver"
                                                        @dragleave="handleImgDragLeave" @drop.prevent="handleImgDrop">
                                                        <div v-if="!form.img"
                                                            class="flex flex-col items-center justify-center pt-5 pb-6">
                                                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 20 16">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                            </svg>
                                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                                <span class="font-semibold">Click to upload</span> or drag
                                                                and drop
                                                            </p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG
                                                                (MAX. 800x400px - 2MB-4MB)</p>
                                                        </div>
                                                        <div v-else class="flex flex-col items-center justify-center">
                                                            <img :src="form.imgPreview" alt="Uploaded Preview"
                                                                class="max-h-24 mb-2 rounded-lg" />
                                                            <p class="text-sm text-gray-500">{{ form.imgName }}</p>
                                                        </div>
                                                        <input id="dropzone-img" type="file" class="hidden"
                                                            accept=".svg, .png, .jpg, .jpeg"
                                                            @change="(e) => handleImg(e.target.files[0])" />
                                                    </label>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </SettingsLayout>

</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import { ref, watchEffect, computed  } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SettingsLayout from '@/Layouts/Settings_Layout.vue';
import { usePage } from "@inertiajs/vue3";
import { Tooltip } from 'primevue';
import { DatePicker } from 'primevue';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'


const props = defineProps({
    sponsors: Array,
});

const directives = {
    Tooltip,
    DatePicker,
};

const UpdateMOA = ref(false);

const isTableVisible = ref(false);

const toggleTable = () => {
    isTableVisible.value = !isTableVisible.value;
};

const toggleupdateMOA = () => {
    UpdateMOA.value = !UpdateMOA.value;
};

const addingcancel = () => {
    isTableVisible.value = !isTableVisible.value;
    
};

const updatecancel = () => {
    UpdateMOA.value = !UpdateMOA.value;
    
};



const isCreating = ref(false);
const isEditing = ref(false);
const Showcase = ref(false);

const form = ref({
    id: null,
    name: null,
    description: null,
    file: null,
    fileName: null,
    filePreview: null,
    img: null,
    imgName: null,
    imgPreview: null,
    abbreviation: null,
    since: null,
});

const scholarships = ref({
    name: null,
    scholarshipType: null,
    school_year: null,
    semester: null,
    application: null,
    deadline: null,
});

const isFileDragging = ref(false);
const isImgDragging = ref(false);

const previewFile = (event) => {
    const file = event.target.files[0];
    handleFile(file);
};

const previewImg = (event) => {
    const img = event.target.files[0];
    handleImg(img);
};

const handleFileDragOver = () => {
    isFileDragging.value = true;
};

const handleImgDragOver = () => {
    isImgDragging.value = true;
};

const handleFileDragLeave = () => {
    isFileDragging.value = false;
};

const handleImgDragLeave = () => {
    isImgDragging.value = false;
};

const handleFileDrop = (event) => {
    isFileDragging.value = false;
    const file = event.dataTransfer.files[0];
    handleFile(file);
};

const handleImgDrop = (event) => {
    isImgDragging.value = false;
    const img = event.dataTransfer.files[0];
    handleImg(img);
};

const handleFile = (file) => {
    if (file) {
        form.value.file = file;
        form.value.fileName = file.name;
        const reader = new FileReader();
        reader.onload = (e) => {
            form.value.filePreview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};


const handleImg = (img) => {
    if (img) {
        // Generate a unique filename using timestamp
        const timestamp = new Date().getTime();
        const uniqueFileName = `${timestamp}_${img.name}`;

        form.value.img = img;
        form.value.imgName = uniqueFileName; // Store the generated unique filename

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            form.value.imgPreview = e.target.result;
        };
        reader.readAsDataURL(img);
    }
};

const toggleCreate = () => {
    isCreating.value = !isCreating.value;
    if (isCreating.value) {
        resetForm();
    }
};

const closeModal = () => {
    isCreating.value = false;
    isEditing.value = false;
    isPublishing.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = { id: null, name: '', description: '' };
};

const editScholarship = (scholarship) => {
    isEditing.value = true;
    isCreating.value = false;
    form.value = { ...scholarship };
};

const viewApplicants = (scholarshipId) => {
    Inertia.visit(`/scholarships/${scholarshipId}/applicants`);
};


const submitForm = async () => {
    try {
        if (isEditing.value) {
            router.put(`/sponsors/${form.value.id}`, form.value);
        } else {
            router.post("/settings/sponsors/create", form.value);
        }

        closeModal();
    } catch (error) {
        console.error("Error submitting form:", error);
    }
};

const formatDate = (dateString) => {
  const date = new Date(dateString.replace(" ", "T")); // Ensure proper parsing
  return date.toLocaleString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

const activeateForm = async () => {
    try {
        await useForm(scholarships.value).post(route('scholarships.store'));
        closeModal();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};



// radix vue testing

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

// onMounted(() => {
//     setTimeout(() => {
//         toastMessage.value = "Test Toast!";
//         toastVisible.value = true;

//         setTimeout(() => {
//             toastVisible.value = false;
//         }, 30000);
//     }, 1000);
// });


</script>