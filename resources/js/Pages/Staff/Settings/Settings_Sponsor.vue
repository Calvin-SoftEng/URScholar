<template>
    <SettingsLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <h1 class="text-2xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                    <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>URS Scholarship
                    Partners
                </h1>

                <div v-if="!isTableVisible && !UpdateMOA" class="flex justify-end items-center w-full gap-3">
                    <button @click="toggleTable"
                        class="text-sm gap-1 font-medium text-gray-900 bg-white border border-gray-300 rounded-lg 
                        focus:ring-blue-500 focus:border-blue-500 
                        dark:bg-dprimary dark:text-dtext dark:border-gray-600 dark:placeholder-gray-400 
                        dark:focus:border-blue-500 dark:hover:bg-primary flex items-center justify-center px-4 py-2">
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
                                    class="p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                                    placeholder="Search Scholarship" />
                                <button type="submit"
                                    class="absolute top-0 end-0 p-2 text-sm font-medium h-full text-white bg-blue-900 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sponsors Table -->
                <div v-if="!isTableVisible && !UpdateMOA" class="w-full mt-5">
                    <div class="relative overflow-x-auto border border-gray-200 rounded-lg">
                        <table
                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-lg">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr class="dark:text-dtext dark:bg-dcontainer tracking-wider">
                                    <th scope="col" class="px-1 py-3">
                                        <span class="sr-only">Image</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Sponsor Name
                                    </th>
                                    <th scope="col" class="px-3 py-3">
                                        Since
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Focal Person
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date Created
                                    </th>

                                    <th scope="col" class="px-6 py-3">
                                        Latest Agreement
                                    </th>

                                    <th scope="col" class="px-6 py-3">
                                        Validity
                                    </th>
                                    <th>
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="sponsor in sponsors" :key="sponsor.id">
                                    <tr
                                        class="bg-white text-lg border-b dark:bg-dcontainer dark:border-gray-700 border-gray-200">
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <img :src="`/storage/sponsor/logo/${sponsor.logo}`" alt="logo"
                                                class="w-14 h-14 rounded-full" />
                                        </th>
                                        <td class="px-6 py-4 text-sm">
                                            {{ sponsor.name }} <span>({{ sponsor.abbreviation }})</span>
                                        </td>
                                        <td class="px-3 py-4 text-sm">
                                            {{ sponsor.since }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span v-if="!sponsor.assign.first_name && !sponsor.assign.last_name">
                                                Not Registered
                                            </span>
                                            <span v-else>
                                                {{ sponsor.assign.first_name }} {{ sponsor.assign.last_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            {{ formatDate(sponsor.created_at) }}
                                        </td>
                                        <td class="px-3 py-4 text-sm">
                                            August 1, 2023
                                        </td>
                                        <td class="px-3 py-4 text-sm">
                                            August 1, 2023
                                        </td>
                                        <td class="px-6 py-4">
                                            <button @click="ViewSponsor(sponsor)"
                                                class="text-sm gap-1 font-medium text-gray-900 bg-white border border-gray-300 rounded-lg 
                                                focus:ring-blue-500 focus:border-blue-500 
                                                dark:bg-dprimary dark:text-dtext dark:border-gray-600 dark:placeholder-gray-400 
                                                dark:focus:border-blue-500 dark:hover:bg-primary flex items-center justify-center px-4 py-2">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- View Sponsor Details -->
                <div v-if="UpdateMOA && !isEditMode" class="w-full h-full space-y-5 mb-3">
                    <div
                        class="w-full bg-white rounded-lg dark:bg-dsecondary dark:border dark:border-gray-200 border pb-10">
                        <div class="w-full dark:bg-primary flex items-center justify-between rounded-t-lg px-5 py-5">
                            <h2 class="text-lg font-semibold text-primary">Sponsor Information</h2>
                            <div class="flex items-center gap-2">
                                <button @click="goback" type="button"
                                    class="text-sm gap-1 font-medium text-gray-900 bg-white border border-gray-300 rounded-lg 
                                    focus:ring-blue-500 focus:border-blue-500 
                                    dark:bg-dprimary dark:text-dtext dark:border-gray-600 dark:placeholder-gray-400 
                                    dark:focus:border-blue-500 dark:hover:bg-primary flex items-center justify-center px-4 py-3">
                                    Go Back
                                </button>
                                <button v-if="form.created_id == $page.props.auth.user.id" @click="updateSponsor"
                                    class="btn bg-primary text-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary px-5">
                                    Edit
                                </button>
                            </div>
                        </div>
                        <div class="w-[80%] mx-auto flex flex-col gap-2 px-10 items-stretch h-full">
                            <div class="w-full">
                                <!-- Sponsor Image and Description Section -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="w-full">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Image</h3>
                                        <div class="mb-2 flex flex-col items-center gap-4">
                                            <div
                                                class="w-60 h-60 p-2 border rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                                <img :src="form.imgPreview || (form.imgName ? `/storage/sponsor/logo/${form.imgName}` : '')"
                                                    alt="Sponsor Logo" class="object-cover w-full h-full">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sponsor Info -->
                                    <div class="flex flex-col gap-4">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Name</h3>
                                            <p class="mt-1 text-lg font-poppins text-gray-800 dark:text-dtext">{{
                                                form.name || 'N/A' }}
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Abbreviation
                                                </h3>
                                                <p class="mt-1 text-lg font-poppins text-gray-800 dark:text-dtext">{{
                                                    form.abbreviation
                                                    ||
                                                    'N/A' }}</p>
                                            </div>

                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Partnered Since
                                                </h3>
                                                <p class="mt-1 text-lg font-poppins text-gray-800 dark:text-dtext">{{
                                                    form.since ||
                                                    'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Background
                                                Information</h3>
                                            <p
                                                class="mt-1 text-base font-poppins text-gray-800 dark:text-dtext bg-gray-50 dark:bg-gray-900 p-3 rounded-lg max-h-44 overflow-y-auto">
                                                {{ form.description || 'No description available.' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="h-0.5 bg-gray-200 dark:bg-gray-700 my-6"></div>

                            <div
                                class="pt-4 text-left text-lg font-semibold text-gray-900 dark:text-white flex justify-between items-center">
                                <span>Memorandum of Agreements History</span>
                                <button v-if="form.created_id == $page.props.auth.user.id" @click="toggleUploadMOA"
                                    class="inline-flex items-center gap-1 text-sm font-medium bg-primary hover:bg-blue-700 text-white py-1.5 px-3 rounded-lg transition duration-200">
                                    <span class="material-symbols-rounded text-base">upload_file</span>
                                    Add new MOA
                                </button>
                            </div>

                            <!-- MOA History Section -->
                            <div v-if="moa && moa.length > 0">
                                <div v-for="moaItem in moa.filter(m => m.sponsor_id === form.id)" :key="moaItem.id"
                                    class="grid grid-cols-1 md:grid-cols-4 gap-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <!-- Date column -->
                                    <div class="w-full md:col-span-2 flex items-center">
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            {{ formatDate(moaItem.created_at) }} - {{ formatDate(moaItem.validity) }}
                                        </span>
                                    </div>

                                    <!-- File name column -->
                                    <div class="w-full md:col-span-2 flex items-center">
                                        <span class="text-gray-700 dark:text-gray-300">{{ moaItem.moa }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Empty state -->
                            <div v-else class="text-center py-4 text-gray-500 dark:text-gray-400">
                                No previous MOA records found.
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Add New Sponsor Form -->
                <div v-if="isTableVisible && !UpdateMOA" class="w-full h-full space-y-5 mb-3">
                    <form @submit.prevent="submitForm">
                        <div
                            class="w-full bg-white rounded-lg dark:bg-dsecondary dark:border dark:border-gray-200 border pb-10">
                            <div
                                class="w-full dark:bg-primary flex items-center justify-between rounded-t-lg px-5 py-5">
                                <h2 class="text-lg font-semibold text-primary">Sponsor Information</h2>
                                <div class="flex items-center gap-2">
                                    <button @click="addingcancel" type="button"
                                        class="btn bg-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary px-5">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="btn bg-primary text-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary px-5">
                                        Publish
                                    </button>
                                </div>
                            </div>
                            <div class="w-[80%] mx-auto flex flex-col gap-2 px-10 items-stretch h-full">

                                <div class="mx-auto flex flex-col w-full gap-5 items-stretch h-full">
                                    <div class="w-full flex gap-5 h-full items-stretch">
                                        <div class="w-[40%] flex space-y-5 items-stretch h-full">
                                            <div class="w-full flex flex-col items-stretch h-full">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Upload
                                                    Photo (Optional
                                                    for Displaying)</h3>
                                                <InputError v-if="errors?.img" :message="errors.img"
                                                    class="text-2xs text-red-500" />
                                                <label for="dropzone-img"
                                                    class="flex flex-col justify-center w-full h-[13.5rem] border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
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
                                                            <span class="font-semibold">Click to upload</span> or
                                                            drag and drop
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG
                                                            (MAX. 800x400px
                                                            - 2MB-4MB)</p>
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
                                        <div class="w-[60%] space-y-5">
                                            <div class="w-full">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor</h3>
                                                <InputError v-if="errors?.name" :message="errors.name"
                                                    class="text-2xs text-red-500" />
                                                <input v-model="form.name" type="text" id="name"
                                                    placeholder="Enter a Partnership or Sponsor"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                            </div>
                                            <div class="w-full">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Abbreviation
                                                </h3>
                                                <InputError v-if="errors?.abbreviation" :message="errors.abbreviation"
                                                    class="text-2xs text-red-500" />
                                                <input v-model="form.abbreviation" type="text" id="name"
                                                    placeholder="e.g., CHED"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                            </div>
                                            <div class="w-full">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Partnered
                                                    Since</h3>
                                                <InputError v-if="errors?.since" :message="errors.since"
                                                    class="text-2xs text-red-500" />
                                                <input v-model="form.since" type="text" id="name"
                                                    placeholder="e.g., Since 2012"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full flex flex-col space-y-1">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor
                                            Background
                                            Information</h3>
                                        <InputError v-if="errors?.description" :message="errors.description"
                                            class="text-2xs text-red-500" />
                                        <textarea v-model="form.description" id="description"
                                            placeholder="Enter Description" rows="3"
                                            class="textarea textarea-bordered bg-gray-50 w-full border-gray-300 dark:bg-gray-900 dark:text-dtext"></textarea>

                                    </div>
                                    <div class="w-full space-y-5">
                                        <div
                                            class="col-span-4 gap-2 relative w-full flex items-center mt-4 whitespace-nowrap">
                                            <h3 class="font-semibold text-sm text-blue-900 dark:text-white">
                                                Sponsor Scholarship Effectivity
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div class="flex w-full items-stretch h-full gap-5">
                                            <div class="w-[40%] flex flex-col">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Attach
                                                    Memorandum of
                                                    Agreement</h3>
                                                <InputError v-if="errors?.file" :message="errors.file"
                                                    class="text-2xs text-red-500" />
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
                                                            <span class="font-semibold">Click to upload</span> or
                                                            drag and drop
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG,
                                                            PNG, JPG, DOCX
                                                            (MAX. 2MB-4MB)</p>
                                                    </div>
                                                    <div v-else class="flex flex-col items-center justify-center">
                                                        <template
                                                            v-if="form.filePreview && !form.fileName.endsWith('.docx')">
                                                            <img :src="form.filePreview" alt="Uploaded Preview"
                                                                class="h-32 mb-2 rounded-lg" />
                                                        </template>
                                                        <template v-else>
                                                            <img src="../../../../assets/images/previewdocs.png"
                                                                alt="Document Icon" class="h-32 mb-2" />
                                                        </template>
                                                        <p class="text-sm text-gray-500">{{ form.fileName }}</p>
                                                    </div>
                                                    <input id="dropzone-file" type="file" class="hidden"
                                                        accept=".svg, .png, .jpg, .docx"
                                                        @change="(e) => handleFile(e.target.files[0])" />
                                                </label>
                                            </div>

                                            <div class="w-[60%]">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                                    Effectivity</h3>
                                                <DatePicker v-model:modelValueStart="startDate" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full space-y-5">
                                        <div
                                            class="col-span-4 gap-2 relative w-full flex items-center mt-4 whitespace-nowrap">
                                            <h3 class="font-semibold text-sm text-blue-900 dark:text-white">
                                                Assigning Focal Person
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-5">
                                            <div class="w-full">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor First
                                                    Name</h3>
                                                <InputError v-if="errors?.sponsor_first_name" :message="errors.sponsor_first_name"
                                                    class="text-2xs text-red-500" />
                                                <input v-model="form.sponsor_first_name" type="text" id="name"
                                                    placeholder="First Name"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                            </div>
                                            <div class="w-full">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Middle
                                                    Name</h3>
                                                <InputError v-if="errors?.sponsor_middle_name" :message="errors.sponsor_middle_name"
                                                    class="text-2xs text-red-500" />
                                                <input v-model="form.sponsor_middle_name" type="text" id="name"
                                                    placeholder="Middle Name"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                            </div>
                                            <div class="w-full">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Last
                                                    Name</h3>
                                                <InputError v-if="errors?.sponsor_last_name" :message="errors.sponsor_last_name"
                                                    class="text-2xs text-red-500" />
                                                <input v-model="form.sponsor_last_name" type="text" id="name"
                                                    placeholder="Last Name"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                            </div>
                                            <div class="w-full">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Email
                                                </h3>
                                                <InputError v-if="errors?.email" :message="errors.email"
                                                    class="text-2xs text-red-500" />
                                                <input v-model="form.email" type="email" id="name"
                                                    placeholder="sponsor@test.com"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                            </div>
                                            <div class="w-full">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Contact No.
                                                </h3>
                                                <InputError v-if="errors?.sponsor_number" :message="errors.sponsor_number"
                                                    class="text-2xs text-red-500" />
                                                <input v-model="form.sponsor_number" type="text" id="name"
                                                    placeholder="e.g. 09XXXXXXXXX"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Edit Sponsor Form -->
                <div v-if="UpdateMOA && isEditMode" class="w-full h-full space-y-5 mb-3">
                    <form @submit.prevent="submitForm">
                        <div
                            class="w-full bg-white rounded-lg dark:bg-dsecondary dark:border dark:border-gray-200 border pb-10">
                            <div
                                class="w-full dark:bg-primary flex items-center justify-between rounded-t-lg px-5 py-5">
                                <h2 class="text-lg font-semibold text-primary">Sponsor Information</h2>
                                <div class="flex items-center gap-2">
                                    <button @click="updatecancel" type="button"
                                        class="btn bg-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary px-5">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="btn bg-primary text-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary px-5">
                                        Save
                                    </button>
                                </div>
                            </div>
                            <div class="w-[80%] mx-auto flex flex-col gap-2 px-10 items-stretch h-full">
                                <div class="w-full">
                                    <!-- Sponsor Image and Name Section -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                        <div class="w-full">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Image</h3>
                                            <div class="mb-2 flex flex-col items-center gap-4">
                                                <div
                                                    class="w-40 h-32 p-2 border rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                                    <img :src="form.imgPreview || (form.imgName ? `/storage/sponsor/logo/${form.imgName}` : '')"
                                                        alt="Sponsor Logo" class="object-cover w-full h-full">
                                                </div>
                                                <div class="w-full">
                                                    <input type="file" accept="image/*"
                                                        class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                        @change="(e) => handleImg(e.target.files[0])">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full flex flex-col">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Background
                                                Information</h3>
                                            <textarea v-model="form.description" id="description"
                                                placeholder="Enter Description"
                                                class="textarea textarea-bordered h-44 bg-gray-50 w-full border-gray-300 dark:bg-gray-900 dark:text-dtext"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Abbreviation, Partnered Since, and Background Section -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="w-full space-y-2">
                                        <div class="w-full">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Sponsor Name</h3>
                                            <input v-model="form.name" type="text" id="name"
                                                placeholder="Enter a Partnership or Sponsor"
                                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                        </div>
                                        <div class="w-full">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Abbreviation</h3>
                                            <input v-model="form.abbreviation" type="text" id="abbreviation"
                                                placeholder="e.g., CHED"
                                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                        </div>
                                        <div class="w-full">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Partnered Since</h3>
                                            <input v-model="form.since" type="text" id="since"
                                                placeholder="e.g., Since 2012"
                                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:bg-gray-900 dark:text-dtext" />
                                        </div>
                                    </div>

                                    <!-- Memorandum of Agreement Section -->
                                    <div class="w-full flex flex-col">
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Attach Memorandum
                                            of Agreement</h3>
                                        <label for="update-dropzone-file"
                                            class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                            :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                            @dragover.prevent="handleFileDragOver" @dragleave="handleFileDragLeave"
                                            @drop.prevent="handleFileDrop">
                                            <div v-if="!form.file && !form.filePreview"
                                                class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 20 16">
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
                                                <template
                                                    v-if="form.filePreview && !form.fileName?.endsWith('.docx') && !form.fileName?.endsWith('.doc') && !form.fileName?.endsWith('.pdf')">
                                                    <img :src="form.filePreview" alt="Uploaded Preview"
                                                        class="h-32 mb-2 rounded-lg" />
                                                </template>
                                                <template v-else>
                                                    <img src="../../../../assets/images/previewdocs.png"
                                                        alt="Document Icon" class="h-32 mb-2" />
                                                </template>
                                                <p class="text-sm text-gray-500">{{ form.fileName || 'Current MOA File'
                                                }}</p>
                                            </div>
                                            <input id="update-dropzone-file" type="file" class="hidden"
                                                accept=".svg, .png, .jpg, .docx, .doc, .pdf"
                                                @change="(e) => handleFile(e.target.files[0])" />
                                        </label>
                                    </div>
                                </div>

                                <div class="h-0.5 bg-gray-200 dark:bg-gray-700 my-3"></div>

                                <!-- Title after the line -->
                                <div class="pt-4 text-left text-lg font-semibold text-gray-900 dark:text-white">
                                    Memorandum of Agreements History
                                </div>

                                <!-- MOA History Section -->
                                <div v-if="moa && moa.length > 0">
                                    <div v-for="moaItem in moa.filter(m => m.sponsor_id === form.id)" :key="moaItem.id"
                                        class="grid grid-cols-1 md:grid-cols-3 gap-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                        <!-- Date column -->
                                        <div class="w-full md:col-span-1 flex items-center">
                                            <span class="font-semibold text-gray-900 dark:text-white">{{
                                                formatDate(moaItem.created_at)
                                            }}</span>
                                        </div>

                                        <!-- File name column -->
                                        <div class="w-full md:col-span-2 flex items-center">
                                            <span class="text-gray-700 dark:text-gray-300">{{ moaItem.moa }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div v-else class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    No previous MOA records found.
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


            </div>
        </div>
        <!-- creating a sponsor -->
        <div v-if="uploadMOA"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-3/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">

                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                New Memorandum of Agreement
                            </h2>
                            <!-- <span class="text-sm text-gray-600 dark:text-gray-400">
                                Provide the necessary details to set up a scholarship.
                            </span> -->
                        </div>
                    </div>


                    <!-- Close Button -->
                    <button type="button" @click="closeAdding"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <!-- Form -->
                <form @submit.prevent="submitMOA" class="p-6 flex flex-col gap-5">

                    <!-- Page 1: Basic Information -->
                    <div>
                        <div class="flex flex-col gap-3">
                            <div class="w-full flex flex-col space-y-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Upload Memorandum of Agreement
                                </h3>
                                <input
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                    @change="moaupload" type="file">
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            Effectivity</h3>
                        <DatePicker v-model:modelValueStart="startDate" />
                    </div>
                    <!-- Buttons -->
                    <div class="mt-2 flex justify-between gap-2">
                        <div class="flex w-full justify-end gap-2">
                            <!-- Cancel Button (Always Visible) -->
                            <button type="button" @click="closeAdding"
                                class="text-gray-700 bg-gray-200 w-full hover:bg-gray-300 focus:ring-4 focus:ring-gray-400 shadow-sm rounded-lg text-sm px-5 py-2.5">
                                Cancel
                            </button>

                            <!-- Direct "Create Scholarship" Button if Grant-Based is Selected -->
                            <button type="submit"
                                class="text-white bg-gradient-to-r w-full from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 shadow-lg rounded-lg text-sm px-5 py-2.5">
                                Upload
                            </button>
                        </div>
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
    </SettingsLayout>
</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import { ref, watchEffect, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SettingsLayout from '@/Layouts/Settings_Layout.vue';
import { usePage } from "@inertiajs/vue3";
import { Tooltip } from 'primevue';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'
import InputError from '@/Components/InputError.vue';
import DatePicker from '@/Components/DatePickerManual.vue';


const props = defineProps({
    sponsors: Array,
    moa: Array,
    errors: Object,
    flash: Object,
});

const directives = {
    Tooltip,
    DatePicker,
};

const startDate = ref(null);

const endDate = ref(null);

// Helper functions
function formatModelDate(date) {
    if (!date) return '';
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}/${month}/${day}`;
}

const formattedStartDate = computed(() => formatModelDate(startDate.value));
const formattedEndDate = computed(() => formatModelDate(endDate.value));

const isTableVisible = ref(false);

const uploadMOA = ref(false);

const toggleUploadMOA = () => {
    uploadMOA.value = !uploadMOA.value;
};

const closeAdding = () => {
    uploadMOA.value = false;
}

// const toggleupdateMOA = () => {
//     UpdateMOA.value = !UpdateMOA.value;
// };

// const addingcancel = () => {
//     isTableVisible.value = !isTableVisible.value;

// };

const isEditMode = ref(false);
const UpdateMOA = ref(false);

// Toggle views
const toggleTable = () => {
    isTableVisible.value = true;
    UpdateMOA.value = false;
    isEditMode.value = false;
    resetForm();
};

const ViewSponsor = (sponsor) => {
    // Fill form with all sponsor data for viewing and later editing
    form.value = {
        id: sponsor.id,
        name: sponsor.name,
        description: sponsor.description,
        abbreviation: sponsor.abbreviation,
        since: sponsor.since,
        imgName: sponsor.logo,
        fileName: sponsor.moa_file,
        sponsor_name: sponsor.sponsor_name,
        email: sponsor.email,
        created_id: sponsor.created_id,
        // Set these to null as they are for file upload handling
        file: null,
        filePreview: null,
        img: null,
        imgPreview: null
    };

    // Show view mode
    UpdateMOA.value = true;
    isTableVisible.value = false;
    isEditMode.value = false;
};

const updateSponsor = () => {
    isEditMode.value = true;
};

const addingcancel = () => {
    isTableVisible.value = false;
    resetForm();
};

const updatecancel = () => {
    isEditMode.value = false;
};

const goback = () => {
    isTableVisible.value = false;
    isEditMode.value = false
    UpdateMOA.value = false
};





const isCreating = ref(false);
const isEditing = ref(false);
const Showcase = ref(false);

function moaupload(event) {
    form.value.file = event.target.files[0]
}

const form = ref({
    validity: formattedStartDate,
    id: null,
    name: null,
    description: null,
    file: null,
    fileName: null,
    filePreview: null,
    img: null,
    created_id: null,
    imgName: null,
    imgPreview: null,
    abbreviation: null,
    since: null,
    sponsor_first_name: null,
    sponsor_middle_name: null,
    sponsor_last_name: null,
    sponsor_number: null,
    email: null,

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

const canManageSponsor = computed(() => {
    const currentUser = usePage().props.auth.user;

    // Find the current sponsor from props
    const currentSponsor = props.sponsors.find(s => s.id === form.value.id);

    // Check if current user is the assigned user for this sponsor
    return currentSponsor && currentSponsor.assign_id === currentUser.id;
});

const submitMOA = async () => {
    try {
        // Create FormData to handle file upload
        const formData = new FormData();
        formData.append('sponsor_id', form.value.id);
        formData.append('moa_file', form.value.file);
        formData.append('validity', formattedStartDate.value);

        // Post the MOA to the server
        router.post("/settings/sponsors/moa", formData, {
            onSuccess: () => {
                // Close the modal and show success message
                uploadMOA.value = false;
                form.value.file = null;
            }
        });
    } catch (error) {
        console.error("Error uploading MOA:", error);
    }
};

const closeModal = () => {
    isCreating.value = false;
    isEditing.value = false;
    isPublishing.value = false;
    resetForm();
};

// Update the resetForm function to reset all fields
const resetForm = () => {
    form.value = {
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
        since: null
    };
};

const editScholarship = (scholarship) => {
    isEditing.value = true;
    isCreating.value = false;
    form.value = { ...scholarship };
};


const submitForm = async () => {
    form.value.validity = formattedStartDate.value; // Use .value to get the actual computed value
    try {
        if (UpdateMOA.value) {
            router.put(`/settings/sponsors/${form.value.id}`, form.value);
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