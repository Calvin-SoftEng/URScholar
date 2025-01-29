<template>

    <Head title="Scholarships" />
    <AuthenticatedLayout>
        <div class="w-full h-full px-10 py-5 bg-[#F8F8FA]">
            <div class="w-full mx-auto p-3 rounded-xl text-white">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li>
                            <a>
                                <span class="material-symbols-rounded mr-2" style="color: #0D47A1; font-size: 20px;">
                                    dashboard
                                </span>
                                Home
                            </a>
                        </li>
                        <li>
                            <a>
                                <Link :href="route('sponsor.index')">
                                    <span class="text-blue-400 font-semibold">View Sponsors</span>
                                </Link>
                                
                            </a>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Create Scholarships</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <h1
                        class="text-3xl font-poppins font-extrabold text-[darkblue] text-left underline underline-offset-4">
                        Fetch mo yung Name dito</h1>

                    <!-- <button class="btn" @click="toggleCreate">
                        <span class="material-symbols-rounded">
                            library_add
                        </span>
                        New Sponsor
                    </button> -->
                </div>

                <!-- List of Scholarships -->
                <div v-if="!isCreating && !isEditing">
                    <div class="container mx-auto py-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div v-for="sponsor in sponsors" :key="sponsor.id"
                                class="card border bg-white hover:shadow-xl hover:border-gray-400">
                                <div class="card-body p-5 space-y-2">
                                    <p class="text-xs text-gray-500">Created on: {{ new
                                        Date(sponsor.created_at).toLocaleDateString() }}</p>
                                    <p class="text-xs text-gray-500">Sponsoring Since: {{ new
                                        Date(sponsor.created_at).toLocaleDateString('en-US', {
                                            year: 'numeric', month:
                                        'long', day: 'numeric' }) }}</p>
                                    <h2 class="card-title text-3xl text-gray-800 font-sora font-semibold">{{
                                        sponsor.name }}</h2>
                                    <div class="badge badge-primary text-[12px] badge-outline">DEPED</div>
                                    <p class="text-md text-gray-600 mb-4 text-justify overflow-hidden text-overflow-truncate line-clamp-4 h-24 max-w-full"
                                        style=" display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ sponsor.description }}
                                    </p>
                                    <!-- <div class="card-actions justify-end">
                                        <Link :href="`/scholarships/${scholarship.id}`" class="btn btn-primary btn-sm">
                                        View</Link>
                                    </div>
                                    <div class="card-actions justify-end">
                                        <Link :href="`/scholarships/${scholarship.id}/applicants`" class="btn btn-primary btn-sm">
                                            Applicants</Link>
                                    </div>
                                    <button @click="editScholarship(scholarship)"
                                        class="btn btn-warning btn-sm">Edit</button> -->
                                    <div class="flex justify-end space-x-4">
                                        <div class="text-sm text-gray-500 cursor-pointer" @click="toggleSetActive">
                                            <span
                                                class="material-symbols-rounded text-blue-900 bg-blue-100 p-3 border rounded-lg">
                                                open_in_browser
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <span
                                                class="material-symbols-rounded text-blue-900 bg-blue-100 p-3 border rounded-lg">
                                                cancel
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 cursor-pointer"
                                            @click="editScholarship(scholarship)" type="button">
                                            <span
                                                class="material-symbols-rounded text-blue-900 bg-blue-100 p-3 border rounded-lg">
                                                settings
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- creating a sponsor -->
            <div v-if="isCreating || isEditing"
                class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40 transition-opacity-ease-in duration-300 ">
                <div class="bg-white rounded-lg shadow-xl w-4/12">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <span class="text-xl font-semibold text-gray-900 dark:text-white">
                            <h2 class="text-2xl font-bold">{{ isEditing ? 'Edit Sponsor Information' : 'Add New Sponsor'
                                }}</h2>
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

                    <form @submit.prevent="submitForm" class="space-y-4 p-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor</h3>
                            <input v-model="form.name" type="text" id="name"
                                placeholder="Enter a Partnership or Sponsor"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full" />
                        </div>
                        <!-- file input -->
                        <div class="w-full h-full flex flex-col">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Attach Memorandum of Agreement</h3>
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                @dragover.prevent="handleFileDragOver" @dragleave="handleFileDragLeave"
                                @drop.prevent="handleFileDrop">
                                <div v-if="!form.file" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG, DOCX (MAX.
                                        800x400px - 2MB-4MB)</p>
                                </div>
                                <div v-else class="flex flex-col items-center justify-center">
                                    <img :src="form.filePreview" alt="Uploaded Preview"
                                        class="max-h-24 mb-2 rounded-lg" />
                                    <p class="text-sm text-gray-500">{{ form.fileName }}</p>
                                </div>
                                <input id="dropzone-file" type="file" class="hidden" accept="image/file"
                                    @change="previewFile" />
                            </label>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Background Information</h3>
                            <textarea v-model="form.description" id="description" placeholder="Enter Description"
                                class="textarea textarea-bordered w-full"></textarea>
                        </div>
                        <!-- photo input -->
                        <div class="w-full h-full flex flex-col">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Upload Photo (Optional for
                                Displaying)</h3>
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                @dragover.prevent="handleImgDragOver" @dragleave="handleImgDragLeave"
                                @drop.prevent="handleImgDrop">
                                <div v-if="!form.img" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG, DOCX (MAX.
                                        800x400px - 2MB-4MB)</p>
                                </div>
                                <div v-else class="flex flex-col items-center justify-center">
                                    <img :src="form.imgPreview" alt="Uploaded Preview"
                                        class="max-h-24 mb-2 rounded-lg" />
                                    <p class="text-sm text-gray-500">{{ form.imgName }}</p>
                                </div>
                                <input id="dropzone-file" type="file" class="hidden" accept="image/*"
                                    @change="previewImg" />
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">
                            {{ isEditing ? 'Update Scholarship' : 'Create Scholarship' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- setting a scholarship -->
            <div v-if="isPublishing"
                class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40 transition-opacity-ease-in duration-300 ">
                <div class="bg-white rounded-lg shadow-xl w-5/12">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <span class="text-xl font-semibold text-gray-900 dark:text-white">
                            <h2 class="text-2xl font-bold">Set New Scholarship</h2>
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

                    <!-- modal content -->
                    <!-- page 1 -->
                    <form @submit.prevent="activeateForm">
                        <div v-if="currentPage === 1">
                            <div class="w-full bg-blue-900 h-8 flex items-center justify-center">
                                <h2 class="text-base font-semibold text-white font-quicksand">Scholarship Information
                                </h2>
                            </div>
                            <div class="flex flex-col gap-2 px-4 pt-2 pb-4">
                                <div class="h-full w-full flex flex-row gap-3">
                                    <div class="w-full">
                                        <div class="relative">
                                            <div class="flex flex-row gap-3 w-full mb-3">
                                                <div class="w-full flex flex-col">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship
                                                        Name</h3>
                                                    <input v-model="scholarships.name" type="text" id="name"
                                                        placeholder="Enter Scholarship Name"
                                                        class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full" />
                                                </div>
                                                <div class="w-full flex flex-col">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship
                                                        Type</h3>
                                                    <select v-model="scholarships.scholarshipType" id="scholarshipType"
                                                        class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full">
                                                        <option value="" disabled>Select Scholarship Type</option>
                                                        <option value="merit">Merit-based</option>
                                                        <option value="need">Need-based</option>
                                                        <option value="athletic">Athletic</option>
                                                        <option value="artistic">Artistic</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="h-full w-full flex flex-row gap-3">
                                    <div class="w-full">
                                        <div class="flex flex-row gap-3 w-full mb-3">
                                            <div class="w-full flex flex-col">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">School Year</h3>
                                                <input v-model="scholarships.school_year" type="text" id="name"
                                                    placeholder="School Year"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full" />
                                            </div>
                                            <div class="w-full flex flex-col">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Semester</h3>
                                                <select v-model="scholarships.semester" id="scholarshipType"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full">
                                                    <option value="" disabled>Select Semester</option>
                                                    <option value="merit">First Semester</option>
                                                    <option value="need">Second Semester</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Set Application
                                                Timeline</h3>
                                            <div class="flex flex-row gap-3 w-full">
                                                <div class="relative w-full">
                                                    <DatePicker class="w-full" v-model="scholarships.appplication"
                                                        placeholder="Application Start" />
                                                </div>
                                                <div class="relative w-full">
                                                    <DatePicker class="w-full" v-model="scholarships.deadline"
                                                        placeholder="Application Deadline" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-row w-full gap-3">
                                    <div class="w-full">
                                        <button type="submit" class="btn btn-primary w-full">
                                            Cancel
                                        </button>
                                    </div>
                                    <div class="w-full">
                                        <button type="submit" class="btn btn-primary w-full">
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- page 2 -->
                        <div v-else-if="currentPage === 2">
                            <div class="w-full bg-blue-900 h-8 flex items-center justify-center">
                                <h2 class="text-base font-semibold text-white font-quicksand">Others</h2>
                            </div>
                            <div class="flex flex-col gap-2 px-4 pt-2 pb-4">
                                <div class="h-full w-full flex flex-row gap-3">
                                    <div class="w-full">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">Limit
                                            Applicants/Recipients</h3>
                                        <ul
                                            class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            <li
                                                class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between px-3">
                                                    <label for="vue-checkbox"
                                                        class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">All
                                                        Campuses</label>
                                                    <div class="relative">
                                                        <span
                                                            class="material-symbols-rounded absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-gray-400">
                                                            person
                                                        </span>
                                                        <input type="text"
                                                            class="w-24 pl-8 h-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between px-3">
                                                    <label for="vue-checkbox"
                                                        class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">Angono</label>
                                                    <div class="relative">
                                                        <span
                                                            class="material-symbols-rounded absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-gray-400">
                                                            person
                                                        </span>
                                                        <input type="text"
                                                            class="w-24 pl-8 h-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between px-3">
                                                    <label for="vue-checkbox"
                                                        class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">Antipolo</label>
                                                    <div class="relative">
                                                        <span
                                                            class="material-symbols-rounded absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-gray-400">
                                                            person
                                                        </span>
                                                        <input type="text"
                                                            class="w-24 pl-8 h-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between px-3">
                                                    <label for="vue-checkbox"
                                                        class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">Binangonan</label>
                                                    <div class="relative">
                                                        <span
                                                            class="material-symbols-rounded absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-gray-400">
                                                            person
                                                        </span>
                                                        <input type="text"
                                                            class="w-24 pl-8 h-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between px-3">
                                                    <label for="vue-checkbox"
                                                        class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">Cardona</label>
                                                    <div class="relative">
                                                        <span
                                                            class="material-symbols-rounded absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-gray-400">
                                                            person
                                                        </span>
                                                        <input type="text"
                                                            class="w-24 pl-8 h-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between px-3">
                                                    <label for="vue-checkbox"
                                                        class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">Cainta</label>
                                                    <div class="relative">
                                                        <span
                                                            class="material-symbols-rounded absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-gray-400">
                                                            person
                                                        </span>
                                                        <input type="text"
                                                            class="w-24 pl-8 h-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between px-3">
                                                    <label for="vue-checkbox"
                                                        class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">Morong</label>
                                                    <div class="relative">
                                                        <span
                                                            class="material-symbols-rounded absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-gray-400">
                                                            person
                                                        </span>
                                                        <input type="text"
                                                            class="w-24 pl-8 h-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between px-3">
                                                    <label for="vue-checkbox"
                                                        class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">Pililla</label>
                                                    <div class="relative">
                                                        <span
                                                            class="material-symbols-rounded absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-gray-400">
                                                            person
                                                        </span>
                                                        <input type="text"
                                                            class="w-24 pl-8 h-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between px-3">
                                                    <label for="vue-checkbox"
                                                        class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">Rodriguez</label>
                                                    <div class="relative">
                                                        <span
                                                            class="material-symbols-rounded absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-gray-400">
                                                            person
                                                        </span>
                                                        <input type="text"
                                                            class="w-24 pl-8 h-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between px-3">
                                                    <label for="vue-checkbox"
                                                        class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tanay</label>
                                                    <div class="relative">
                                                        <span
                                                            class="material-symbols-rounded absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-gray-400">
                                                            person
                                                        </span>
                                                        <input type="text"
                                                            class="w-24 pl-8 h-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                </div>
                                            </li>
                                            <li
                                                class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                                <div class="flex items-center justify-between px-3">
                                                    <label for="vue-checkbox"
                                                        class="py-2 text-sm font-medium text-gray-900 dark:text-gray-300">Taytay</label>
                                                    <div class="relative">
                                                        <span
                                                            class="material-symbols-rounded absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none text-gray-400">
                                                            person
                                                        </span>
                                                        <input type="text"
                                                            class="w-24 pl-8 h-7 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="w-full">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">Requirements</h3>
                                        <ul
                                            class="w-full text-sm font-medium text-gray-900 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            <div class="flex items-center mb-4">
                                                <form @submit.prevent="addItem" class="flex items-center">
                                                    <input v-model="newItem" type="text" placeholder="Enter an item"
                                                        class="border border-gray-300 rounded-lg px-4 py-2 w-full" />
                                                    <button type="submit"
                                                        class="bg-blue-500 text-white px-4 py-2 ml-2 rounded-lg hover:bg-blue-600">
                                                        Add
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- List Items -->
                                            <form @submit.prevent="removeItem">
                                                <ul>
                                                    <li v-for="(item, index) in items" :key="index"
                                                        class="flex items-center justify-between text-base bg-gray-100 px-4 py-2 mb-1 rounded-lg">
                                                        <span>{{ item }}</span>
                                                        <button @click="removeItem(index)"
                                                            class="flex items-center text-red-500 hover:text-red-700">
                                                            <span class="material-symbols-rounded text-red-600">
                                                                delete
                                                            </span>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </form>
                                        </ul>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="mb-3">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">Add Note</h3>
                                        <textarea id="message" rows="4"
                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Write additional informations here..."></textarea>
                                    </div>
                                </div>
                                <div class="flex flex-row w-full gap-3">
                                    <div class="w-full">
                                        <button @click="prevPage" class="btn btn-primary w-full">
                                            Previous
                                        </button>
                                    </div>
                                    <div class="w-full">
                                        <button type="submit" class="btn btn-primary w-full">
                                            Activate
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import { Head, useForm, Link, } from '@inertiajs/vue3';
import { Tooltip } from 'primevue';
import { set } from 'date-fns';
import { DatePicker } from 'primevue';


defineProps({
    sponsors: Array,
});

const directives = {
    Tooltip,
    DatePicker,
};

const isCreating = ref(false);
const isEditing = ref(false);

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
});

const scholarships = ref({
    name: null,
    scholarshipType: null,
    school_year: null,
    semester: null,
    application: null,
    deadline: null,
});


// watch(isPublishing, (newValue) => {
//     if (newValue) {
//         setTimeout(() => {
//             const datepickerStart = new Flowbite.Datepicker('#datepicker-range-start');
//             const datepickerEnd = new Flowbite.Datepicker('#datepicker-range-end');
//         }, 300);
//     }
// });

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
    form.value.file = file;
    if (file) {

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
        form.value.img = img;
        form.value.imgName = img.name;
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
            await useForm(form.value).put(`/sponsors/${form.value.id}`);
        } else {
            await useForm(form.value).post('/sponsors');
        }

        closeModal();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const activeateForm = async () => {
    try {
        await useForm(scholarships.value).post(route('scholarships.store'));
        closeModal();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const isPublishing = ref(false);

const toggleSetActive = () => {
    isPublishing.value = !isPublishing.value;
    currentPage.value = 1;
    if (isPublishing.value) {
        resetForm();
    }
};

// const previewFile = (event) => {
//     const file = event.target.files[0];
//     form.value.fileName = file.name;
// }

// const deleteScholarship = async (scholarship) => {
//   // Confirm the action first
//   if (confirm(`Are you sure you want to delete the scholarship "${scholarship.name}"?`)) {
//     try {
//       // Use Inertia's delete request to remove the scholarship
//       await useForm().delete(`/scholarships/${scholarship.id}`);
//       // On success, remove the item from the UI (without refreshing the page)
//       const index = scholarships.value.findIndex(item => item.id === scholarship.id);
//       if (index !== -1) {
//         scholarships.value.splice(index, 1);
//       }

//       // Optionally, display a success message (e.g., with a toast)
//     } catch (error) {
//       console.error('Error deleting scholarship:', error);
//       alert('An error occurred while deleting the scholarship.');
//     }
//   }
// };

// dynamic requirements
const newItem = ref('');
const items = ref([]);

const addItem = () => {
    if (newItem.value.trim() !== '') {
        items.value.push(newItem.value.trim());
        newItem.value = '';
    }
};

const removeItem = (index) => {
    items.value = items.value.filter((_, i) => i !== index);
};

const currentPage = ref(1);

const nextPage = () => {
    if (currentPage.value < 3) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

</script>


<style scoped>
.p-tooltip-text {
    margin-left: 0px;
    font-size: 13px !important;
}
</style>