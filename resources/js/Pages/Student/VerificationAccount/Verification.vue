<template>

    <Head title="Verification" />
    <div class="w-full h-screen box-border bg-gray-100">

        <!-- Reminder Dialog -->
        <div v-if="showDialog" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70">
            <div class="bg-primary p-6 rounded-lg shadow-xl max-w-md w-full animate-fade-in">

                <!-- Branding inside dialog -->
                <div class="w-full flex items-center justify-center gap-2 mb-4">
                    <img src="../../../../assets/images/main_logo_white.png" alt="URScholar Logo"
                        class="w-12 h-12 dark:hidden">
                    <span class="font-poppins text-3xl font-bold text-white tracking-tight">URScholar</span>
                </div>

                <h2 class="text-lg font-semibold text-white mb-3">Student Reminders</h2>
                <ul class="text-white list-disc list-inside space-y-3">
                    <li v-for="(reminder, index) in reminders" :key="index">{{ reminder }}</li>
                </ul>

                <div class="mt-5 flex flex-col gap-2">
                    <button @click="closeDialog"
                        class="w-full bg-white text-primary font-semibold py-2 rounded-lg hover:bg-gray-200 transition">
                        Got it!
                    </button>
                    <button @click="dontShowAgain" class="w-full text-white text-sm underline">
                        Don't show again
                    </button>
                </div>
            </div>
        </div>


        <form @submit.prevent="submit">
            <div class="w-full flex flex-row justify-between bg-white shadow-sm items-center px-10 sm:px-5">
                <h1 class="xl:text-2xl sm:text-sm font-bold font-sora text-left p-3 mx-10 sm:mx-3">Set up your Profile
                </h1>
                <div class="flex flex-row gap-2">
                    <Link :href="route('logout')" method="post" as="button">
                    <button class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded 
                    sm:py-1 sm:px-3 sm:text-xs">
                        Exit
                    </button>
                    </Link>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded 
                    sm:text-xs sm:py-1
                    xl:px-5">
                        Set Up
                    </button>
                </div>
            </div>
            <div class="py-3 h-full box-border bg-gray-100">
                <div class="mx-auto h-full max-w-5xl sm:px-3 lg:px-8 ">
                    <div class="flex flex-col space-y-5">
                        <div class="bg-primary font-sans font-bold rounded-lg p-7 
                                    2xl:text-3xl 2xl:text-white
                                    xl:text-3xl xl:text-red-500
                                    lg:p-6 lg:text-2xl lg:text-green-500
                                    md:p-5 md:text-2xl md:text-blue-500
                                    sm:p-4 sm:text-base sm:text-yellow-500">
                            Greetings! {{ user.name }}
                        </div>

                        <!-- Stepper Navigation -->
                        <div class="flex flex-row items-center justify-center mt-5 relative w-full">
                            <!-- Background Gray Line (Full Width) -->
                            <div class="absolute top-[30%] left-0 w-full h-1 bg-gray-100 rounded-lg"></div>

                            <div v-for="(step, index) in steps" :key="index"
                                class="relative flex flex-col items-center w-full" @click="goToStep(index)">
                                <!-- Progress Line (Only for Steps > 0) -->
                                <div v-if="index !== 0" class="absolute top-[30%] left-[-50%] h-1 w-full transition-all"
                                    :class="{
                                        'bg-blue-900': activeStep >= index,
                                        'bg-gray-200': activeStep < index
                                    }"></div>

                                <!-- Step Circle with Icon -->
                                <span
                                    class="material-symbols-rounded text-base xl:text-3xl flex items-center justify-center w-8 h-8 xl:w-10 xl:h-10 rounded-full transition-all z-10"
                                    :class="{
                                        'text-white bg-blue-900': activeStep === index,
                                        'text-yellow-600 bg-yellow-100': activeStep > index,
                                        'text-gray-400 bg-gray-100': activeStep < index
                                    }">
                                    {{ step.icon }}
                                </span>

                                <!-- Step Label -->
                                <div class="mt-2 text-xs xl:text-sm font-medium"
                                    :class="{ 'text-blue-900': activeStep >= index, 'text-gray-500': activeStep < index }">
                                    {{ step.label }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-border h-full mx-auto max-w-7xl sm:px-3 lg:px-8 overflow-auto">
                    <div class="h-full flex flex-col space-y-5 mt-5 xl:mt-10">

                        <div class="stepper-container max-w-full">
                            <!-- Step Content -->
                            <div class="flex-grow">
                                <div v-if="activeStep === 0">
                                    <div class="bg-white grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3
                                        gap-6 rounded-lg h-1/2 items-center justify-start p-10 sm:p-5 xl:p-10 ">

                                        <div class="sm:col-span-3 md:col-span-2 lg:col-span-3 xl:col-span-3">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4 sm:text-white">
                                                Account Information</h3>
                                            <p
                                                class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                Please fill-up missing required fields. Leave N/A if not applicable</p>
                                        </div>

                                        <!-- <div class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>First Name
                                                </Label>
                                                <InputError v-if="errors?.first_name" :message="errors.first_name" class="items-center flex text-xs mb-1" />
                                            </div>
                                            <Input id="first_name" type="text" placeholder="First Name" v-model="form.first_name" class="w-full border border-gray-200" />
                                        </div> -->

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>First Name
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="first_name" type="text" placeholder="First Name"
                                                    v-model="form.first_name" @focus="errors.first_name = null"
                                                    class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.first_name" :message="errors.first_name"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="last_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Last Name
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="last_name" type="text" placeholder="First Name"
                                                    v-model="form.last_name" @focus="errors.last_name = null"
                                                    class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.last_name" :message="errors.last_name"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="middle_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Middle Name
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="middle_name" type="text" placeholder="Middle Name"
                                                    v-model="form.middle_name" @focus="errors.middle_name = null"
                                                    class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.middle_name" :message="errors.middle_name"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <!-- <div class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <Label for="email" class="items-center flex mb-1">Suffix Name</Label>
                                            <Input id="email" type="text" placeholder="Suffix Name"
                                                v-model="form.suffix" class="w-full border-gray-200" />
                                        </div> -->

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="suffix" class="items-center flex mb-1">
                                                    Suffix
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="suffix" type="text" placeholder="Suffix"
                                                    @focus="clearMainField('suffix')" @blur="restoreMainField('suffix')"
                                                    v-model="form.suffix" class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="birthdate" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Birthdate
                                                </Label>
                                            </div>

                                            <div class="relative max-w-sm">
                                                <div
                                                    class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                    </svg>
                                                </div>
                                                <input v-model="form.birthdate" id="datepicker-autohide" type="text"
                                                    autocomplete="off"
                                                    class="bg-white border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Select Birthdate" />

                                                <InputError v-if="errors?.birthdate" :message="errors.birthdate"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>


                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="place_of_birth" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Place of
                                                    Birth
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="place_of_birth" type="text" placeholder="Place of Birth"
                                                    v-model="form.birthplace" @focus="errors.birthplace = null"
                                                    class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.birthplace" :message="errors.birthplace"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="age" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Age
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="age" type="text" placeholder="Age" v-model="form.age"
                                                    @focus="errors.age = null"
                                                    class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.age" :message="errors.age"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <!-- <div class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="gender" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Sex
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="gender" type="text" placeholder="Age" v-model="form.gender" @focus="errors.gender = null" class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.gender" :message="errors.gender" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div> -->

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="gender" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Sex
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Select v-model="form.gender">
                                                    <SelectTrigger id="gender" class="w-full border pr-10"
                                                        :class="{ 'border-gray-200': !errors?.gender }"
                                                        @focus="errors.gender = null">
                                                        <SelectValue placeholder="Select Sex" />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        <SelectGroup>
                                                            <SelectItem value="Male">Male</SelectItem>
                                                            <SelectItem value="Female">Female</SelectItem>
                                                        </SelectGroup>
                                                    </SelectContent>
                                                </Select>

                                                <InputError v-if="errors?.gender" :message="errors.gender"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="civil_status" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Civil Status
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Select v-model="form.civil_status">
                                                    <SelectTrigger id="civil_status" class="w-full border pr-10"
                                                        :class="{ 'border-gray-200': !errors?.civil_status }"
                                                        @focus="errors.civil_status = null">
                                                        <SelectValue placeholder="Select Civil Status" />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        <SelectGroup>
                                                            <SelectItem value="Single">
                                                                Single
                                                            </SelectItem>
                                                            <SelectItem value="widowed">
                                                                Widowed
                                                            </SelectItem>
                                                        </SelectGroup>
                                                    </SelectContent>
                                                </Select>

                                                <InputError v-if="errors?.civil_status" :message="errors.civil_status"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Street
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="first_name" type="text" placeholder="First Name"
                                                    v-model="form.street" @focus="errors.street = null"
                                                    class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.street" :message="errors.street"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="last_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Municipality
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="last_name" type="text" placeholder="First Name"
                                                    v-model="form.municipality" @focus="errors.municipality = null"
                                                    class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.municipality" :message="errors.municipality"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="middle_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Province
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="middle_name" type="text" placeholder="Middle Name"
                                                    v-model="form.province" @focus="errors.province = null"
                                                    class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.province" :message="errors.province"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="guardian_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Religion
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="Religion" type="text" placeholder="Religion"
                                                    v-model="form.religion" @focus="errors.religion = null"
                                                    class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.religion" :message="errors.religion"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="guardian_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Guardian
                                                    Name
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="guardian_name" type="text" placeholder="Guardian Name"
                                                    v-model="form.guardian_name" @focus="errors.guardian_name = null"
                                                    class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.guardian_name" :message="errors.guardian_name"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="guardian_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Relationship
                                                </Label>
                                            </div>

                                            <div class="relative w-full">
                                                <Input id="guardian_name" type="text" placeholder="Guardian Name"
                                                    v-model="form.relationship" @focus="errors.relationship = null"
                                                    class="w-full border border-gray-200 pr-10" />
                                                <InputError v-if="errors?.relationship" :message="errors.relationship"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                            </div>
                                        </div>

                                        <div class="col-span-3 flex justify-end mt-4">
                                            <button type="submit" @click="nextStep"
                                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                                Next</button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="activeStep === 1" class="overflow-x-auto w-full max-w-full border-box">
                                    <div class="bg-white border-box overflow-x-auto grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 
                                        gap-6 rounded-lg h-1/2 items-center justify-start p-10 sm:p-5 xl:p-10">

                                        <div class="sm:col-span-3 md:col-span-2 lg:col-span-3 xl:col-span-3">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4 sm:text-white">
                                                Educational Background</h3>
                                            <p
                                                class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                Please fill-up missing required fields. Leave N/A if not applicable</p>
                                        </div>

                                        <!-- gwa -->
                                        <div
                                            class="col-span-3 whitespace-nowrap gap-2 relative flex flex-row justify-center items-center mb-2">
                                            <h3
                                                class="font-semibold whitespace-nowrap text-[12px] text-blue-900 dark:text-white text-center">
                                                General Weighted Average
                                            </h3>
                                            <div
                                                class="pl-2 w-full h-0.5 bg-gray-200 rounded-lg relative flex items-center justify-center">

                                            </div>
                                        </div>

                                        <div v-if="scholar" class="col-span-3 md:col-span-2 lg:col-span-3 w-full">
                                            <span class="text-sm text-gray-500 italic">* You can set this up
                                                later</span>
                                            <div
                                                class="col-span-1 md:col-span-2 lg:col-span-3 w-full flex flex-col md:flex-row md:items-center gap-4">
                                                <!-- GWA Input -->
                                                <div
                                                    class="col-span-1 md:col-span-2 lg:col-span-3 w-full md:w-2/3 flex flex-col gap-1.5">
                                                    <Label for="gwa">Enter General Weighted Average
                                                        <span class="italic text-gray-500">*must be {{
                                                            props.batch_semester }} semester {{ school_year.year
                                                            }}
                                                        </span>
                                                    </Label>
                                                    <input id="gwa" v-model="form.grade" type="text"
                                                        placeholder="Enter your GWA (e.g., 2.0)"
                                                        class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200" />
                                                </div>

                                                <!-- File Upload -->
                                                <div
                                                    class="col-span-1 md:col-span-2 lg:col-span-3 w-full md:w-1/3 flex flex-col gap-1.5">
                                                    <Label for="file_upload">Upload Certificate of Grade</Label>
                                                    <input id="file_upload" type="file" @change="handleFile"
                                                        class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 
                                                        dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else class="col-span-3 md:col-span-2 lg:col-span-3 w-full">
                                            <span class="text-sm text-red-500 italic ">* You can set this up
                                                after verification</span>

                                            <div
                                                class="col-span-1 md:col-span-2 lg:col-span-3 w-full flex flex-col md:flex-row md:items-center gap-4">
                                                <!-- GWA Input -->
                                                <div
                                                    class="col-span-1 md:col-span-2 lg:col-span-3 w-full md:w-2/3 flex flex-col gap-1.5">
                                                    <Label for="gwa">Enter General Weighted Average
                                                        <span class="italic text-gray-500">*must be {{
                                                            props.batch_semester }} semester {{ school_year.year
                                                            }}
                                                        </span>
                                                    </Label>
                                                    <input id="gwa" v-model="form.grade" type="text" disabled
                                                        placeholder="Enter your GWA (e.g., 2.0)"
                                                        class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200" />
                                                </div>

                                                <!-- File Upload -->
                                                <div
                                                    class="col-span-1 md:col-span-2 lg:col-span-3 w-full md:w-1/3 flex flex-col gap-1.5">
                                                    <Label for="file_upload">Upload Certificate of Grade</Label>
                                                    <input id="file_upload" type="file" disabled
                                                        class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 
                                                        dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                </div>
                                            </div>
                                        </div>



                                        <!-- elementary -->
                                        <div
                                            class="col-span-3 gap-2 relative flex flex-row justify-center items-center mt-4 mb-2">
                                            <h3
                                                class="font-semibold text-[12px] text-blue-900 dark:text-white text-center">
                                                Elementary
                                            </h3>
                                            <div
                                                class="pl-2 w-full h-0.5 bg-gray-200 rounded-lg relative flex items-center justify-center">
                                                <span v-if="props.errors['education.elementary']"
                                                    class="text-red-500 text-xs absolute bg-white px-1">
                                                    {{ props.errors['education.elementary'] }}
                                                </span>
                                            </div>
                                        </div>


                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Name of School,
                                                    College or University
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Elementary School"
                                                v-model="form.education.elementary.name"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Years Attended
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. 2016-2020"
                                                v-model="form.education.elementary.years"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    Honors/Awards Recieved
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. Academic Awards"
                                                @focus="clearDefault('education', 'elementary', 'honors')"
                                                @blur="restoreDefault('education', 'elementary', 'honors')"
                                                v-model="form.education.elementary.honors"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <!-- junior -->
                                        <div
                                            class="col-span-3 gap-2 relative flex flex-row justify-center items-center mt-4 mb-2">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white text-center 
                                                    sm:whitespace-normal md:whitespace-nowrap">
                                                Junior High School
                                            </h3>

                                            <div
                                                class="pl-2 w-full h-0.5 bg-gray-200 rounded-lg relative flex items-center justify-center">
                                                <span v-if="props.errors['education.junior']"
                                                    class="text-red-500 text-xs absolute bg-white px-1">
                                                    {{ props.errors['education.junior'] }}
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Name of School,
                                                    College or University
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Junior High School"
                                                v-model="form.education.junior.name"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Years Attended
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. 2016-2020"
                                                v-model="form.education.junior.years"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    Honors/Awards Recieved
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. Academic Awards"
                                                @focus="clearDefault('education', 'junior', 'honors')"
                                                @blur="restoreDefault('education', 'junior', 'honors')"
                                                v-model="form.education.junior.honors"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <!-- senior -->
                                        <div
                                            class="col-span-3 gap-2 relative flex flex-row justify-center items-center mt-4 mb-2">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white text-center 
                                                    sm:whitespace-normal md:whitespace-nowrap">
                                                Senior High School
                                            </h3>

                                            <div
                                                class="pl-2 w-full h-0.5 bg-gray-200 rounded-lg relative flex items-center justify-center">
                                                <span v-if="props.errors['education.senior']"
                                                    class="text-red-500 text-xs absolute bg-white px-1">
                                                    {{ props.errors['education.senior'] }}
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Name of School,
                                                    College or University
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Senior High School"
                                                v-model="form.education.senior.name"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Years Attended
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. 2016-2020"
                                                v-model="form.education.senior.years"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    Honors/Awards Recieved
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. Academic Awards"
                                                @focus="clearDefault('education', 'senior', 'honors')"
                                                @blur="restoreDefault('education', 'senior', 'honors')"
                                                v-model="form.education.senior.honors"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <!-- college -->
                                        <div
                                            class="col-span-3 gap-2 relative flex flex-row justify-center items-center mt-4 mb-2">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white text-center 
                                                    sm:whitespace-normal md:whitespace-nowrap">
                                                College
                                            </h3>

                                            <div
                                                class="pl-2 w-full h-0.5 bg-gray-200 rounded-lg relative flex items-center justify-center">
                                                <span v-if="props.errors['education.college']"
                                                    class="text-red-500 text-xs absolute bg-white px-1">
                                                    {{ props.errors['education.college'] }}
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Name of School,
                                                    College or University
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Senior High School"
                                                v-model="form.education.college.name"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Years Attended
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. 2016-2020"
                                                v-model="form.education.college.years"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    Honors/Awards Recieved
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. Academic Awards"
                                                @focus="clearDefault('education', 'college', 'honors')"
                                                @blur="restoreDefault('education', 'college', 'honors')"
                                                v-model="form.education.college.honors"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <!-- vocational -->
                                        <div
                                            class="col-span-3 gap-2 relative flex flex-row justify-center items-center mt-4 mb-2">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white text-center 
                                                    sm:whitespace-normal md:whitespace-nowrap">
                                                Vocational
                                            </h3>

                                            <div
                                                class="pl-2 w-full h-0.5 bg-gray-200 rounded-lg relative flex items-center justify-center">
                                                <span v-if="props.errors['vocational']"
                                                    class="text-red-500 text-xs absolute bg-white px-1">
                                                    {{ props.errors['vocational'] }}
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    Name of School,
                                                    College or University
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Senior High School"
                                                @focus="clearDefault('education', 'vocational', 'name')"
                                                @blur="restoreDefault('education', 'vocational', 'name')"
                                                v-model="form.education.vocational.name"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    Years Attended
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. 2016-2020"
                                                @focus="clearDefault('education', 'vocational', 'years')"
                                                @blur="restoreDefault('education', 'vocational', 'years')"
                                                v-model="form.education.vocational.years"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    Honors/Awards Recieved
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. Academic Awards"
                                                @focus="clearDefault('education', 'vocational', 'honors')"
                                                @blur="restoreDefault('education', 'vocational', 'honors')"
                                                v-model="form.education.vocational.honors"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <!-- post -->
                                        <div
                                            class="col-span-3 gap-2 relative flex flex-row justify-center items-center mt-4 mb-2">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white text-center 
                                                    sm:whitespace-normal md:whitespace-nowrap">
                                                Post Graduate
                                            </h3>

                                            <div
                                                class="pl-2 w-full h-0.5 bg-gray-200 rounded-lg relative flex items-center justify-center">
                                                <span v-if="props.errors['postgrad']"
                                                    class="text-red-500 text-xs absolute bg-white px-1">
                                                    {{ props.errors['postgrad'] }}
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    Name of School,
                                                    College or University
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Senior High School"
                                                @focus="clearDefault('education', 'postgrad', 'name')"
                                                @blur="restoreDefault('education', 'postgrad', 'name')"
                                                v-model="form.education.postgrad.name"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    Years Attended
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. 2016-2020"
                                                @focus="clearDefault('education', 'postgrad', 'years')"
                                                @blur="restoreDefault('education', 'postgrad', 'years')"
                                                v-model="form.education.postgrad.years"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div
                                            class=" w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label for="first_name" class="items-center flex mb-1">
                                                    Honors/Awards Recieved
                                                </Label>
                                            </div>
                                            <Input id="first_name" type="text" placeholder="Ex. Academic Awards"
                                                @focus="clearDefault('education', 'postgrad', 'honors')"
                                                @blur="restoreDefault('education', 'postgrad', 'honors')"
                                                v-model="form.education.postgrad.honors"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div class="col-span-3 space-x-2 flex justify-end mt-4">
                                            <!-- Back Button -->
                                            <button type="button" @click="prevStep" :disabled="activeStep === 0"
                                                class="text-white bg-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none 
                                                focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg 
                                                text-sm px-5 py-2.5 text-center mb-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                                Back
                                            </button>

                                            <!-- Next Button -->
                                            <button type="submit" @click="nextStep"
                                                :disabled="activeStep === steps.length - 1" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                                                hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                focus:ring-blue-300 dark:focus:ring-blue-800 font-medium 
                                                rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                                Next
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="activeStep === 2">
                                    <div class="bg-white grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 
                                            gap-6 rounded-lg h-1/2 items-center justify-start p-10 sm:p-5 xl:p-10">

                                        <div class="sm:col-span-4 md:col-span-2 lg:col-span-3 xl:col-span-3">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4 sm:text-white">
                                                Family Background
                                            </h3>
                                            <p
                                                class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                Please fill-up missing required fields. Leave N/A if not applicable</p>
                                        </div>

                                        <!-- Mother -->
                                        <div
                                            class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                MOTHER (Type "N/A" if deceased)
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <!-- Form Fields -->
                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>First Name
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="First Name"
                                                    v-model="form.mother.first_name" @input="handleMotherFirstNameInput"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Last Name
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Last Name"
                                                    v-model="form.mother.last_name" :disabled="form.mother.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.mother.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Middle Name
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Middle Name"
                                                    v-model="form.mother.middle_name" :disabled="form.mother.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.mother.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Age
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="number" placeholder="Age" v-model="form.mother.age"
                                                    :disabled="form.mother.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.mother.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <!-- Address should take 3 columns on larger screens -->
                                        <div
                                            class="w-full items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-3 xl:col-span-3 2xl:col-span-3">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Address
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Permanent Address"
                                                    v-model="form.mother.address" :disabled="form.mother.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.mother.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Citizenship
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Ex. Filipino"
                                                    v-model="form.mother.citizenship" :disabled="form.mother.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.mother.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Occupation
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Occupation"
                                                    v-model="form.mother.occupation" :disabled="form.mother.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.mother.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>


                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Educational
                                                    Attainment
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Ex. College Graudate"
                                                    v-model="form.mother.education" :disabled="form.mother.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.mother.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>


                                        <div
                                            class="w-full items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1 xl:col-span-2 ">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    Batch (If Alumna
                                                    of this High School/University)
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Leave blank if none"
                                                    @focus="clearSubField('mother', 'batch')"
                                                    @blur="restoreSubField('mother', 'batch')"
                                                    v-model="form.mother.batch" :disabled="form.mother.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.mother.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>


                                        <!-- father -->
                                        <div
                                            class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                FATHER (Type "N/A" if deceased)
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>First Name
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="First Name"
                                                    v-model="form.father.first_name" @input="handleFatherFirstNameInput"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Last Name
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Last Name"
                                                    v-model="form.father.last_name" :disabled="form.father.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.father.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Middle Name
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Middle Name"
                                                    v-model="form.father.middle_name" :disabled="form.father.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.father.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Age
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="number" placeholder="Age" v-model="form.father.age"
                                                    :disabled="form.father.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.father.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <!-- Address should take 3 columns on larger screens -->
                                        <div
                                            class="w-full items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-3 xl:col-span-3 2xl:col-span-3">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Address
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Permanent Address"
                                                    v-model="form.father.address" :disabled="form.father.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.father.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Citizenship
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Ex. Filipino"
                                                    v-model="form.father.citizenship" :disabled="form.father.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.father.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Occupation
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Occupation"
                                                    v-model="form.father.occupation" :disabled="form.father.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.father.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>


                                        <!-- <div class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <Label for="email">Occupation</Label>
                                            <Input id="email" type="text" placeholder="Occupation"
                                                v-model="form.mother.occupation" class="w-full border-gray-200" />
                                        </div> -->

                                        <div
                                            class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>Educational
                                                    Attainment
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Ex. College Graudate"
                                                    v-model="form.father.education" :disabled="form.father.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.father.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <!-- <div class="w-full max-w-sm items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1">
                                            <Label for="email">Educational Attainment</Label>
                                            <Input id="email" type="text" placeholder="Ex. College Graudate"
                                                v-model="form.mother.education" class="w-full border-gray-200" />
                                        </div> -->

                                        <div
                                            class="w-full items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1 xl:col-span-2 ">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    Batch (If Alumna
                                                    of this High School/University)
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="Leave blank if none"
                                                    @focus="clearSubField('father', 'batch')"
                                                    @blur="restoreSubField('father', 'batch')"
                                                    v-model="form.father.batch" :disabled="form.father.isDeceased"
                                                    :class="{ 'bg-gray-200 text-gray-500 cursor-not-allowed': form.father.isDeceased }"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                        </div>

                                        <!-- fam  info -->
                                        <div
                                            class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                PLEASE PUT A CHECK MARK (✓) THE APPROPRIATE BOX.
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div
                                            class="col-span-4 sm:col-span-4 xl:col-span-2 grid w-full items-center gap-1.5">
                                            <Label for="marital-status" class="text-gray-500">C.1 Marital Status of
                                                Parents</Label>
                                            <RadioGroup default-value="comfortable"
                                                class="grid sm:grid-cols-1 md:grid-cols-2 gap-2"
                                                v-model="form.marital_status">
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="m1" value="Married" />
                                                    <Label for="m1">Married</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="m2" value="living_together" />
                                                    <Label for="m2">Living Together</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="m3" value="not_married" />
                                                    <Label for="m3">Not Married</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="m4" value="separated" />
                                                    <Label for="m4">Separated</Label>
                                                </div>
                                            </RadioGroup>
                                            <InputError v-if="errors?.marital_status" :message="errors.marital_status"
                                                class="items-center flex text-xs" />
                                        </div>

                                        <div
                                            class="col-span-4 sm:col-span-4 xl:col-span-2 grid w-full items-center gap-1.5">
                                            <Label for="marital-status" class="text-gray-500">C.2 Monthly Family
                                                Income</Label>
                                            <RadioGroup default-value="comfortable"
                                                class="grid sm:grid-cols-1 md:grid-cols-2 gap-2"
                                                v-model="form.monthly_income">
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i1" value="10,000 and below" />
                                                    <Label for="i1">10,000 and below</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i2" value="20,001 - 30,000" />
                                                    <Label for="i2">20,001 - 30,000</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i3" value="10,001 - 20,000" />
                                                    <Label for="i3">10,001 - 20,000</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i4" value="30,001 and above" />
                                                    <Label for="i4">30,001 and above</Label>
                                                </div>
                                            </RadioGroup>
                                            <InputError v-if="errors?.monthly_income" :message="errors.monthly_income"
                                                class="items-center flex text-xs" />
                                        </div>

                                        <div
                                            class="w-full items-center gap-1.5 col-span-4 sm:col-span-4 md:col-span-1 lg:col-span-1 xl:col-span-2 ">
                                            <div class="flex flex-row items-center gap-2">
                                                <Label class="items-center flex mb-1">
                                                    <span class="text-red-900 font-bold mr-1">*</span>C.3 Other Source
                                                    of Income
                                                </Label>
                                            </div>
                                            <div class="relative w-full">
                                                <Input type="text" placeholder="e.g. Sari-sari Store"
                                                    @focus="clearMainField('other_income')"
                                                    @blur="restoreMainField('other_income')" v-model="form.other_income"
                                                    class="w-full border border-gray-200 pr-10" />
                                            </div>
                                            <InputError v-if="errors?.other_income" :message="errors.other_income"
                                                class="items-center flex text-xs" />
                                        </div>


                                        <div
                                            class="col-span-4 sm:col-span-4 xl:col-span-2 grid w-full items-center gap-1.5">
                                            <Label for="marital-status" class="text-gray-500">C.4 Family Type of
                                                Housing</Label>
                                            <RadioGroup default-value="comfortable"
                                                class="flex sm:flex-col md:flex-row gap-2"
                                                v-model="form.family_housing">

                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="h1" value="owned" />
                                                    <Label for="h1">Owned</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="h2" value="settler" />
                                                    <Label for="h2">Settler</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="h3" value="rental" />
                                                    <Label for="h3">Rental</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="h4" value="other" />
                                                    <Label for="h4">Others, please specify:</Label>
                                                    <input v-if="form.family_housing === 'other'" type="text"
                                                        v-model="otherText" placeholder="Type here..."
                                                        class="border border-gray-200 focus:outline-none w-32 px-2 text-sm" />
                                                </div>

                                            </RadioGroup>

                                            <InputError v-if="errors?.family_housing" :message="errors.family_housing"
                                                class="items-center flex text-xs" />
                                        </div>

                                        <!-- siblings info -->
                                        <div
                                            class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                C.5 Name of Siblings in the Family (Leave blank if none)
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div class="col-span-4 grid w-full items-center gap-1.5 space-y-4">
                                            <div v-for="(entry, index) in form.siblings" :key="index"
                                                class="entry border border-gray-200 p-3 col-span-4 grid sm:grid-cols-1 md:grid-cols-3 w-full items-end gap-3 justify-end">

                                                <!-- First Name -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label :for="'first_name_' + index">First Name</Label>
                                                    <Input :id="'first_name_' + index" type="text"
                                                        placeholder="First Name" v-model="entry.first_name"
                                                        class="w-full border border-gray-200" />
                                                </div>

                                                <!-- Last Name -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label :for="'last_name_' + index">Last Name</Label>
                                                    <Input :id="'last_name_' + index" type="text"
                                                        placeholder="Last Name" v-model="entry.last_name"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Middle Name -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label :for="'middle_name_' + index">Middle Name</Label>
                                                    <Input :id="'middle_name_' + index" type="text"
                                                        placeholder="Middle Name" v-model="entry.middle_name"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Age -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label :for="'age_' + index">Age</Label>
                                                    <Input :id="'age_' + index" type="number" placeholder="Age"
                                                        v-model="entry.age" class="w-full border-gray-200" />
                                                </div>

                                                <!-- Occupation -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label :for="'occupation_' + index">Occupation</Label>
                                                    <Input :id="'occupation_' + index" type="text"
                                                        placeholder="Occupation" v-model="entry.occupation"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Remove Button -->
                                                <button v-if="form.siblings.length > 1" @click="removeEntry(index)"
                                                    class="bg-red-900 text-white px-3 py-1 rounded h-10 flex items-center space-x-5">
                                                    <span class="material-symbols-rounded mr-2">remove</span> Remove
                                                </button>
                                            </div>

                                            <!-- Add Another Button -->
                                            <button @click="addEntry"
                                                class="bg-blue-900 text-white px-3 py-1 rounded h-10 flex items-center space-x-5">
                                                <span class="material-symbols-rounded mr-2"> add_circle </span> Add
                                                Another
                                            </button>
                                        </div>



                                        <div class="col-span-4 space-x-2 flex justify-end mt-4">
                                            <!-- Back Button -->
                                            <button type="button" @click="prevStep" :disabled="activeStep === 0"
                                                class="text-white bg-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none 
                                                focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg 
                                                text-sm px-5 py-2.5 text-center mb-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                                Back
                                            </button>

                                            <!-- Next Button -->
                                            <button type="submit" @click="nextStep"
                                                :disabled="activeStep === steps.length - 1" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                                                hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                focus:ring-blue-300 dark:focus:ring-blue-800 font-medium 
                                                rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                                Next
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="activeStep === 3">
                                    <div class="bg-white border-box overflow-x-auto grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 
                                        gap-6 rounded-lg h-1/2 items-center justify-start p-10 sm:p-5 xl:p-10">

                                        <div class="sm:col-span-3 md:col-span-2 lg:col-span-3 xl:col-span-3">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4 sm:text-white">
                                                Organizational Membership</h3>
                                            <p
                                                class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                Please fill-up missing required fields. Leave Blank if none</p>
                                        </div>

                                        <div class="col-span-4 grid w-full items-center gap-1.5 space-y-4">
                                            <div v-for="(entry, index) in form.organizations" :key="index"
                                                class="border border-gray-200 p-3 col-span-4 grid sm:grid-cols-1 md:grid-cols-4 w-full items-center gap-3">

                                                <!-- Name of Organization -->
                                                <div
                                                    class="col-span-4 sm:col-span-4 xl:col-span-2 grid w-full items-center gap-1.5">
                                                    <Label :for="'organization_' + index">Name of Organization</Label>
                                                    <Input :id="'organization_' + index" type="text"
                                                        placeholder="Ex. USSG" v-model="entry.name"
                                                        class="w-full border border-gray-200" />
                                                </div>

                                                <!-- Membership -->
                                                <div
                                                    class="col-span-4 sm:col-span-4 xl:col-span-1 grid w-full items-center gap-1.5">
                                                    <Label :for="'membership_' + index">Inclusive Dates of
                                                        Membership</Label>
                                                    <Input :id="'membership_' + index" type="text"
                                                        placeholder="Ex. 2022 - Present"
                                                        v-model="entry.membership_dates"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Position -->
                                                <div
                                                    class="col-span-4 sm:col-span-4 xl:col-span-1 grid w-full items-center gap-1.5">
                                                    <Label :for="'position_' + index">Position Held</Label>
                                                    <Input :id="'position_' + index" type="text"
                                                        placeholder="Ex. Representative" v-model="entry.position"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Remove Button -->
                                                <button v-if="form.organizations.length > 1"
                                                    @click="removeOrganization(index)"
                                                    class="bg-red-900 text-white px-3 py-1 rounded h-10 flex items-center space-x-5">
                                                    <span class="material-symbols-rounded mr-2">remove</span> Remove
                                                </button>
                                            </div>

                                            <!-- Add Another Button -->
                                            <button @click="addOrganization"
                                                class="bg-blue-900 text-white px-3 py-1 rounded h-10 flex items-center space-x-5">
                                                <span class="material-symbols-rounded mr-2">add_circle</span> Add
                                                Another
                                            </button>
                                        </div>


                                        <div class="col-span-4 space-x-2 flex justify-end mt-4">
                                            <!-- Back Button -->
                                            <button type="button" @click="prevStep" :disabled="activeStep === 0"
                                                class="text-white bg-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none 
                                                focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg 
                                                text-sm px-5 py-2.5 text-center mb-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                                Back
                                            </button>

                                            <!-- Next Button -->
                                            <button type="submit" @click="nextStep"
                                                :disabled="activeStep === steps.length - 1" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                                                hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                focus:ring-blue-300 dark:focus:ring-blue-800 font-medium 
                                                rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                                Next
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="activeStep === 4">
                                    <form @submit.prevent="submit">
                                        <div class="bg-white border-box overflow-x-auto grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 
                                            gap-6 rounded-lg h-1/2 items-center justify-start p-10 sm:p-5 xl:p-10">

                                            <div class="sm:col-span-3 md:col-span-2 lg:col-span-2 xl:col-span-2">
                                                <h3
                                                    class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4 sm:text-white">
                                                    Account Setup</h3>
                                                <p
                                                    class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                    Please change and update you password to your preference</p>
                                            </div>
                                            <!-- <div
                                            class="bg-white grid grid-cols-2 gap-2 rounded-lg h-1/2 items-center justify-start p-10">
                                            <div class="col-span-2">
                                                <h3
                                                    class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">
                                                    Account Setup</h3>
                                                <p
                                                    class="font-semibold text-[11px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                    Please change and update you password to your preference</p>
                                            </div> -->

                                            <div class="col-span-3 flex flex-col sm:flex-row px-4 sm:px-24 gap-6">
                                                <!-- Image Upload Column -->
                                                <div class="w-full sm:w-[30%] flex flex-col items-center gap-1.5">
                                                    <Label for="pic">Insert Profile Picture</Label>
                                                    <InputError v-if="errors?.img" :message="errors.img"
                                                        class="items-center flex text-xs" />
                                                    <label for="dropzone-img"
                                                        class="flex flex-col items-center justify-center w-64 h-64 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
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
                                                                (MAX. 800x400px - 2MB-4MB)</p>
                                                        </div>
                                                        <div v-else class="flex flex-col items-center justify-center">
                                                            <img :src="form.imgPreview" alt="Uploaded Preview"
                                                                class="max-h-56 mb-2 rounded-lg" />
                                                        </div>
                                                        <input id="dropzone-img" type="file" class="hidden"
                                                            accept=".svg, .png, .jpg, .jpeg"
                                                            @change="(e) => handleImg(e.target.files[0])" />
                                                    </label>
                                                </div>

                                                <!-- Form Fields Column -->
                                                <div class="w-full sm:w-[80%] flex flex-col gap-3">
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                        <div class="flex flex-col gap-1.5">
                                                            <div class="flex flex-row items-center gap-2">
                                                                <Label for="first_name" class="items-center flex">
                                                                    <span
                                                                        class="text-red-900 font-bold mr-1">*</span>First
                                                                    Name
                                                                </Label>
                                                                <InputError v-if="errors?.first_name"
                                                                    :message="errors.first_name"
                                                                    class="items-center flex text-xs" />
                                                            </div>
                                                            <Input id="first_name" type="text" placeholder="First Name"
                                                                v-model="form.first_name"
                                                                class="w-full border border-gray-200" />
                                                        </div>

                                                        <div class="flex flex-col gap-1.5">
                                                            <div class="flex flex-row items-center gap-2">
                                                                <Label for="last_name" class="items-center flex">
                                                                    <span
                                                                        class="text-red-900 font-bold mr-1">*</span>Last
                                                                    Name
                                                                </Label>
                                                                <InputError v-if="errors?.last_name"
                                                                    :message="errors.last_name"
                                                                    class="items-center flex text-xs" />
                                                            </div>
                                                            <Input id="last_name" type="text" placeholder="Last Name"
                                                                v-model="form.last_name"
                                                                class="w-full border border-gray-200" />
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-col gap-1.5">
                                                        <div class="flex flex-row items-center gap-2">
                                                            <Label for="email" class="items-center flex">
                                                                <span class="text-red-900 font-bold mr-1">*</span>Email
                                                            </Label>
                                                            <InputError v-if="errors?.email" :message="errors.email"
                                                                class="items-center flex text-xs" />
                                                        </div>
                                                        <Input id="email" type="email" placeholder="Email"
                                                            v-model="form.email" readonly
                                                            class="w-full border border-gray-200" />
                                                    </div>

                                                    <!-- <div class="w-full max-w-sm items-center gap-1.5 col-span-3 sm:col-span-1 md:col-span-2">
                                                        <div class="flex flex-row items-center gap-2">
                                                            <Label for="middle_name" class="items-center flex mb-1">
                                                                <span class="text-red-900 font-bold mr-1">*</span>Middle Name
                                                            </Label>
                                                        </div>

                                                        <div class="relative w-full">
                                                            <Input id="middle_name" type="text" placeholder="Middle Name" v-model="form.middle_name" @focus="errors.middle_name = null" class="w-full border border-gray-200 pr-10" />
                                                            <InputError v-if="errors?.middle_name" :message="errors.middle_name" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                        </div>
                                                    </div> -->

                                                    <div class="flex flex-col gap-1.5">
                                                        <div class="flex flex-row items-center gap-2">
                                                            <Label for="password" class="items-center flex">
                                                                <span
                                                                    class="text-red-900 font-bold mr-1">*</span>Password
                                                            </Label>
                                                        </div>
                                                        <div class="relative w-full">
                                                            <Input id="middle_name" type="text"
                                                                placeholder="Enter Password" v-model="form.password"
                                                                @focus="errors.password = null"
                                                                class="w-full border border-gray-200 pr-10" />
                                                            <InputError v-if="errors?.password"
                                                                :message="errors.password"
                                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-col gap-1.5">
                                                        <div class="flex flex-row items-center gap-2">
                                                            <Label for="password" class="items-center flex">
                                                                <span
                                                                    class="text-red-900 font-bold mr-1">*</span>Confirm
                                                                Password
                                                            </Label>
                                                        </div>
                                                        <div class="relative w-full">
                                                            <Input id="middle_name" type="text"
                                                                placeholder="Enter Password"
                                                                v-model="form.confirm_password"
                                                                @focus="errors.confirm_password = null"
                                                                class="w-full border border-gray-200 pr-10" />
                                                            <InputError v-if="errors?.confirm_password"
                                                                :message="errors.confirm_password"
                                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                        </div>
                                                    </div>

                                                    <!-- <div class="flex flex-col gap-1.5">
                                                        <div class="flex flex-row items-center gap-2">
                                                            <Label for="confirm_password" class="items-center flex">
                                                                <span class="text-red-900 font-bold mr-1">*</span>Confirm Password
                                                            </Label>
                                                            <InputError v-if="errors?.confirm_password" :message="errors.confirm_password"
                                                                class="items-center flex text-xs" />
                                                        </div>
                                                        <Input id="confirm_password" type="password" placeholder="Confirm Password" v-model="form.confirm_password"
                                                            class="w-full border-gray-200" />
                                                    </div> -->
                                                </div>
                                            </div>



                                            <div class="col-span-3 space-x-2 flex justify-end mt-4">
                                                <!-- Back Button -->
                                                <button type="button" @click="prevStep" :disabled="activeStep === 0"
                                                    class="text-white bg-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none 
                                                    focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg 
                                                    text-sm px-5 py-2.5 text-center mb-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                                    Back
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script setup>
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import { ref, nextTick, onMounted, watch, reactive } from 'vue';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Calendar } from '@/Components/ui/calendar'
import InputError from '@/Components/InputError.vue';

import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover'
import { cn } from '@/lib/utils'
import { DateFormatter, getLocalTimeZone, } from '@internationalized/date'
import { Calendar as CalendarIcon } from 'lucide-vue-next'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'
import { initFlowbite } from 'flowbite';


const user = usePage().props.auth.user;

const props = defineProps({
    errors: Object,
    flash: Object,
    user: Object,
    scholar: Object,
    batch: Object,
    batch_semester: Object,
    school_year: Object,
    studentData: Object,
});

const form = ref({
    name: user.name,
    email: user.email,
    first_name: user.first_name,
    middle_name: usePage().props.scholar?.middle_name ?? '',
    last_name: user.last_name,
    password: '',
    confirm_password: '',
    suffix: 'N/A',
    birthdate: usePage().props.scholar?.birthdate ?? '',
    birthplace: props.studentData?.birthplace ?? '',
    age: props.studentData?.age ?? '',
    gender: usePage().props.scholar?.sex ?? '',
    civil_status: props.studentData?.civil_status ?? '',
    street: usePage().props.scholar?.street ?? '',
    municipality: usePage().props.scholar?.municipality ?? '',
    province: usePage().props.scholar?.province ?? '',
    religion: props.studentData?.religion ?? '',
    guardian_name: '',
    relationship: '',
    grade: '',
    cog: '',
    semester: '',
    school_year: '',
    education: {
        elementary: { name: '', years: '', honors: 'N/A' },
        junior: { name: '', years: '', honors: 'N/A' },
        senior: { name: '', years: '', honors: 'N/A' },
        college: { name: '', years: '', honors: 'N/A' },
        vocational: { name: 'N/A', years: 'N/A', honors: 'N/A' },
        postgrad: { name: 'N/A', years: 'N/A', honors: 'N/A' },
    },
    mother: { first_name: '', last_name: '', middle_name: '', age: '', address: '', citizenship: '', occupation: '', education: '', batch: '', isDeceased: false },
    father: { first_name: '', last_name: '', middle_name: '', age: '', address: '', citizenship: '', occupation: '', education: '', batch: '', isDeceased: false },
    marital_status: '',
    monthly_income: '',
    other_income: 'N/A',
    family_housing: '',
    otherText: '',
    siblings: [{ first_name: '', last_name: '', middle_name: '', age: '', occupation: '' }],
    organizations: [{ name: '', membership_dates: '', position: '' }],
    img: null,
    imgName: null,
    imgPreview: null,
});

// Remove "N/A" when input is focused
const clearDefault = (mainField, subField, field) => {
    if (form.value[mainField][subField][field] === "N/A") {
        form.value[mainField][subField][field] = "";
    }
};

// Restore "N/A" if input is empty
const restoreDefault = (mainField, subField, field) => {
    if (form.value[mainField][subField][field].trim() === "") {
        form.value[mainField][subField][field] = "N/A";
    }
};

const clearSubField = (mainField, subField) => {
    if (form.value[mainField][subField] === "N/A") {
        form.value[mainField][subField] = "";
    }
};

const restoreSubField = (mainField, subField) => {
    if (form.value[mainField][subField].trim() === "") {
        form.value[mainField][subField] = "N/A";
    }
};

// Remove "N/A" when input is focused
const clearMainField = () => {
    if (form.value.suffix === "N/A") {
        form.value.suffix = "";
    }

    if (form.value.other_income === "N/A") {
        form.value.other_income = "";
    }
};

// Restore "N/A" if input is empty
const restoreMainField = () => {
    if (form.value.suffix.trim() === "") {
        form.value.suffix = "N/A";
    }

    if (form.value.other_income.trim() === "") {
        form.value.other_income = "N/A";
    }
};

function handleMotherFirstNameInput(event) {
    const value = event.target.value;

    if (value.trim().toUpperCase() === 'N/A') {
        // Update our data model using .value with ref
        form.value.mother.isDeceased = true;
        form.value.mother.last_name = 'N/A';
        form.value.mother.middle_name = 'N/A';
        form.value.mother.age = 'N/A';
        form.value.mother.address = 'N/A';
        form.value.mother.citizenship = 'N/A';
        form.value.mother.occupation = 'N/A';
        form.value.mother.education = 'N/A';
        form.value.mother.batch = 'N/A';
    } else if (form.value.mother.isDeceased) {
        // If it was previously N/A
        form.value.mother.isDeceased = false;

        // Only clear if they contain "N/A"
        if (form.value.mother.last_name === 'N/A') form.value.mother.last_name = '';
        if (form.value.mother.middle_name === 'N/A') form.value.mother.middle_name = '';
        if (form.value.mother.age === 'N/A') form.value.mother.age = '';
        if (form.value.mother.address === 'N/A') form.value.mother.address = '';
        if (form.value.mother.citizenship === 'N/A') form.value.mother.citizenship = '';
        if (form.value.mother.occupation === 'N/A') form.value.mother.occupation = '';
        if (form.value.mother.education === 'N/A') form.value.mother.education = '';
        if (form.value.mother.batch === 'N/A') form.value.mother.batch = '';
    }
}

function handleFatherFirstNameInput(event) {
    const value = event.target.value;

    if (value.trim().toUpperCase() === 'N/A') {
        // Update our data model using .value with ref
        form.value.father.isDeceased = true;
        form.value.father.last_name = 'N/A';
        form.value.father.middle_name = 'N/A';
        form.value.father.age = 'N/A';
        form.value.father.address = 'N/A';
        form.value.father.citizenship = 'N/A';
        form.value.father.occupation = 'N/A';
        form.value.father.education = 'N/A';
        form.value.father.batch = 'N/A';
    } else if (form.value.father.isDeceased) {
        // If it was previously N/A
        form.value.father.isDeceased = false;

        // Only clear if they contain "N/A"
        if (form.value.father.last_name === 'N/A') form.value.father.last_name = '';
        if (form.value.father.middle_name === 'N/A') form.value.father.middle_name = '';
        if (form.value.father.age === 'N/A') form.value.first_name.age = '';
        if (form.value.father.address === 'N/A') form.value.father.address = '';
        if (form.value.father.citizenship === 'N/A') form.value.father.citizenship = '';
        if (form.value.father.occupation === 'N/A') form.value.father.occupation = '';
        if (form.value.father.education === 'N/A') form.value.father.education = '';
        if (form.value.father.batch === 'N/A') form.value.father.batch = '';
    }
}

const handleFile = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.value.cog = file;
    }
};

// Re-attach the selected file when switching steps
const restoreFileInput = () => {
    const fileInput = document.getElementById("file_upload");
    if (fileInput && form.value.cog) {
        // Create a new FileList and assign it to the input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(form.value.cog);
        fileInput.files = dataTransfer.files;
    }
};


// Dialog state
const showDialog = ref(false);
const reminders = [
    "Complete your profile information.",
    "Check for new scholarship postings regularly.",
    "Ensure your documents are updated before applying.",
    "Monitor your application status on your dashboard."
];

// Close dialog
const closeDialog = () => {
    showDialog.value = false;
};

// Set "Don't Show Again" and save preference
const dontShowAgain = () => {
    localStorage.setItem('seenReminderDialog', 'true');
    closeDialog();
};
// const submit = () => {
//     form.post(route('student.verify-account.verifying'), {
//         onFinish: () => form.reset(),
//     });
// };

const submit = async () => {

    try {
        if (props.scholar != null) {
            form.value.semester = props.batch_semester;
            form.value.school_year = props.school_year.id;
        }

        router.post(`/verify-account/verifying`, form.value);
        //await useForm(form.value).post(`/sponsors/create-scholarship`);
        // await form.post(`/sponsors/${props.sponsor.id}/create`)
        // resetForm();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const activeStep = ref(0);
const steps = ref([
    { label: 'Personal', icon: 'person' },
    { label: 'Education', icon: 'history_edu' },
    { label: 'Family', icon: 'diversity_2' },
    { label: 'Organization', icon: 'groups' },
    { label: 'Account', icon: 'identity_platform' },
]);

const goToStep = (index) => {
    activeStep.value = index;
    nextTick(() => {
        initDatepicker(); // Reinitialize datepicker after the step changes
        restoreFileInput();
    });
};

const nextStep = () => {
    if (activeStep.value < steps.value.length - 1) {
        activeStep.value++;
        nextTick(() => {
            initDatepicker(); // Reinitialize datepicker after the step changes
            restoreFileInput();
        });
    }
};

const prevStep = () => {
    if (activeStep.value > 0) {
        activeStep.value--;
        nextTick(() => {
            initDatepicker(); // Reinitialize datepicker after the step changes
            restoreFileInput();
        });
    }
};

const finishStep = () => {
    alert('Step completed!');
};

const submitStep1 = () => {
    // Add your logic to submit the first step's form
    nextStep();
};

const scrollPosition = ref(0);

// Save the scroll position
const saveScrollPosition = () => {
    scrollPosition.value = window.scrollY;
};

// Restore the scroll position
const restoreScrollPosition = () => {
    window.scrollTo(0, scrollPosition.value);
};

//adding siblings
const formEntries = ref([
    { first_name: '', last_name: '', middle_name: '', age: '', occupation: '' }
]);

// Method to add a new entry
const addEntry = (event) => {
    event.preventDefault(); // Prevent form submission
    saveScrollPosition();
    form.value.siblings.push({ first_name: '', last_name: '', middle_name: '', age: '', occupation: '' });
    restoreScrollPosition();
};

const removeEntry = (index) => {
    saveScrollPosition(); // Save scroll position before removing entry
    form.value.siblings.splice(index, 1);
    nextTick(() => restoreScrollPosition()); // Restore scroll position after DOM updates
};

const initDatepicker = () => {
    const datepickerEl = document.getElementById("datepicker-autohide");

    if (datepickerEl) {
        if (!datepickerEl.dataset.initialized) {
            const datepicker = new window.Datepicker(datepickerEl, {
                autohide: true,
                format: "yyyy-mm-dd",
            });

            datepickerEl.dataset.initialized = "true";

            // Store selected date when user types or selects a date
            datepickerEl.addEventListener("input", () => {
                form.value.birthdate = datepickerEl.value;
            });

            datepickerEl.addEventListener("blur", () => {
                form.value.birthdate = datepickerEl.value;
            });
        }

        // 🔥 Restore value manually when switching steps
        if (form.value.birthdate) {
            datepickerEl.value = form.value.birthdate;
        }
    }
};

// This can be used to restore the scroll position when the page first loads
onMounted(() => {
    initFlowbite();
    initDatepicker();
    if (!localStorage.getItem('seenReminderDialog')) {
        showDialog.value = true;
    }

    // const datepickerEl = document.getElementById("datepicker-autohide");

    // if (datepickerEl) {
    //     const datepicker = new window.Datepicker(datepickerEl, {
    //         autohide: true,
    //         format: "yyyy-mm-dd", // Ensure YYYY-MM-DD format
    //     });

    //     datepickerEl.addEventListener("changeDate", (event) => {

    //         const selectedDate = datepicker.getDate();
    //         if (selectedDate) {

    //             const year = selectedDate.getFullYear();
    //             const month = String(selectedDate.getMonth() + 1).padStart(2, "0"); // Month is 0-based
    //             const day = String(selectedDate.getDate()).padStart(2, "0");

    //             form.value.birthdate = `${year}-${month}-${day}`;
    //         }
    //     });
    // }

    restoreScrollPosition();
});



const organizations = ref([
    { name: '', membership_dates: '', position: '' } // Initial entry
]);

// Add a new organization entry
const addOrganization = (event) => {
    event.preventDefault(); // Prevent form submission
    saveScrollPosition(); // Save scroll position before adding entry

    // Directly push into form.value.organizations instead of reassigning
    form.value.organizations.push({ name: '', membership_dates: '', position: '' });

    nextTick(() => restoreScrollPosition()); // Restore scroll position after DOM updates
};

// Remove an organization entry smoothly
const removeOrganization = (index) => {
    if (form.value.organizations.length > 1) {
        form.value.organizations.splice(index, 1);
    }
};


const isImgDragging = ref(false);
const previewImg = (event) => {
    const img = event.target.files[0];
    handleImg(img);
};
const handleImgDragOver = () => {
    isImgDragging.value = true;
};
const handleImgDragLeave = () => {
    isImgDragging.value = false;
};
const handleImgDrop = (event) => {
    isImgDragging.value = false;
    const img = event.dataTransfer.files[0];
    handleImg(img);
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

</script>



<style scoped>
.stepper-container {
    display: flex;
    flex-direction: column;
}

.step-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
}

.step-title {
    margin-left: 10px;
}

.step.active .step-number {
    background-color: #4CAF50;
    color: white;
}

.step.completed .step-number {
    background-color: #2196F3;
    color: white;
}

.stepper-nav .step:hover {
    background-color: #f0f0f0;
}


/* form {
  display: flex;
  flex-direction: column;
}

form input {
  margin: 10px 0;
  padding: 10px;
  border: 1px solid #ccc;
}

form button {
  margin-top: 20px;
  padding: 10px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
} */
</style>