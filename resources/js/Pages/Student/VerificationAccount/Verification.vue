<template>

    <Head title="Verification" />
    <div class="w-full h-screen box-border bg-gray-100">
        <form @submit.prevent="submit">
            <div class="w-full flex flex-row justify-between bg-white shadow-sm items-center px-10">
                <h1 class="text-3xl font-bold font-soratext-left p-3 mx-10">Set up your Profile</h1>
                <div class="flex flex-row gap-2">
                    <Link :href="route('logout')" method="post" as="button">
                    <button class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Exit
                    </button>
                    </Link>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded">
                        Set Up
                    </button>
                </div>
            </div>
            <div class="py-3 h-full box-border bg-gray-100">
                <div class="mx-auto h-full max-w-5xl sm:px-6 lg:px-8 ">
                    <div class="flex flex-col space-y-5">
                        <div class="bg-primary text-white text-3xl font-sans font-bold p-7 rounded-lg">
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
                                    class="material-symbols-rounded text-2xl flex items-center justify-center w-10 h-10 rounded-full transition-all z-10"
                                    :class="{
                                        'text-white bg-blue-900': activeStep === index,
                                        'text-yellow-600 bg-yellow-100': activeStep > index,
                                        'text-gray-400 bg-gray-100': activeStep < index
                                    }">
                                    {{ step.icon }}
                                </span>

                                <!-- Step Label -->
                                <div class="mt-2 text-sm font-medium"
                                    :class="{ 'text-blue-900': activeStep >= index, 'text-gray-500': activeStep < index }">
                                    {{ step.label }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-border h-full mx-auto max-w-7xl sm:px-6 lg:px-8 overflow-auto">
                    <div class="h-full flex flex-col space-y-5 mt-10">

                        <div class="stepper-container max-w-full">
                            <!-- Step Content -->
                            <div class="flex-grow">
                                <div v-if="activeStep === 0">
                                    <div
                                        class="bg-white grid grid-cols-3 gap-6 rounded-lg h-1/2 items-center justify-start p-10">
                                        <div class="col-span-3">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">
                                                Account Information</h3>
                                            <p
                                                class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                Please fill-up missing required fields</p>
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">First Name</Label>
                                            <Input id="email" type="text" placeholder="First Name"
                                                v-model="form.first_name" class="w-full border border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Last Name</Label>
                                            <Input id="email" type="text" placeholder="Last Name"
                                                v-model="form.last_name" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Middle Name</Label>
                                            <Input id="email" type="text" placeholder="Middle Name"
                                                v-model="form.middle_name" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Suffix Name</Label>
                                            <Input id="email" type="text" placeholder="Suffix Name"
                                                v-model="form.suffix" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="birthdate">Date of Birth</Label>
                                            <Popover>
                                                <PopoverTrigger as-child>
                                                    <Button variant="outline"
                                                        class="w-full h-10 justify-start text-left font-normal">
                                                        <CalendarIcon class="mr-2 h-4 w-4" />
                                                        {{ formatDate(form.birthdate) }}
                                                    </Button>
                                                </PopoverTrigger>
                                                <PopoverContent class="w-auto p-0">
                                                    <Calendar v-model="form.birthdate" initial-focus />
                                                </PopoverContent>
                                            </Popover>
                                        </div>

                                        <!-- <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="birthdate">Date of Birth</Label>
                                            <div class="relative max-w-sm">
                                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                                </svg>
                                            </div>
                                            <input v-model="form.birthdate" datepicker id="default-datepicker" type="text" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
                                            </div>
                                        </div> -->

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Place of Birth</Label>
                                            <Input id="email" type="text" placeholder="Place of Birth"
                                                v-model="form.birthplace" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Age</Label>
                                            <Input id="email" type="number" placeholder="Age" v-model="form.age"
                                                class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Gender</Label>
                                            <Select v-model="form.gender">
                                                <SelectTrigger class="w-full">
                                                    <SelectValue placeholder="Select Gender" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectGroup>
                                                        <!-- <SelectLabel>Gender</SelectLabel> -->
                                                        <SelectItem value="Male">
                                                            Male
                                                        </SelectItem>
                                                        <SelectItem value="Female">
                                                            Female
                                                        </SelectItem>
                                                    </SelectGroup>
                                                </SelectContent>
                                            </Select>
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Civil Status</Label>
                                            <Select v-model="form.civil_status">
                                                <SelectTrigger class="w-full">
                                                    <SelectValue placeholder="Select Civil Status" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectGroup>
                                                        <!-- <SelectLabel>Gender</SelectLabel> -->
                                                        <SelectItem value="Single">
                                                            Single
                                                        </SelectItem>
                                                        <SelectItem value="widowed">
                                                            Widowed
                                                        </SelectItem>
                                                    </SelectGroup>
                                                </SelectContent>
                                            </Select>
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Religion</Label>
                                            <Input id="email" type="text" placeholder="Religion" v-model="form.religion"
                                                class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Guardian Name</Label>
                                            <Input id="email" type="text" placeholder="Guardian Name"
                                                v-model="form.guardian_name" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Relationship</Label>
                                            <Input id="email" type="text" placeholder="Relationship"
                                                v-model="form.relationship" class="w-full border-gray-200" />
                                        </div>

                                        <div class="col-span-3 flex justify-end mt-4">
                                            <button type="submit" @click="nextStep"
                                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                                Next</button>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="activeStep === 1" class="overflow-x-auto w-full max-w-full border-box">
                                    <div
                                        class="bg-white border-box overflow-x-auto grid grid-cols-3 gap-2 rounded-lg h-1/2 items-center justify-start p-10">
                                        <div class="col-span-3">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">
                                                Educational Background</h3>
                                            <p
                                                class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white">
                                                Please fill-up missing required fields. Put N/A if not applicable</p>
                                        </div>

                                        <!-- elementary -->
                                        <div
                                            class="col-span-3 gap-2 relative flex flex-row justify-center items-center mt-4 mb-2">
                                            <h3
                                                class="font-semibold text-[12px] text-blue-900 dark:text-white text-center">
                                                Elementary
                                            </h3>
                                            <div
                                                class="pl-2 w-full h-0.5 bg-gray-200 justify-center items-center rounded-lg">
                                            </div>
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Name of School, College or University</Label>
                                            <Input id="email" type="text" placeholder="Elementary School"
                                                v-model="form.education.elementary.name" class="w-full border-gray-200" />
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Years Attended</Label>
                                            <Input id="email" type="text" placeholder="Ex. 2016-2020"
                                                v-model="form.education.elementary.years" class="w-full border-gray-200" />
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Honors/Awards Recieved</Label>
                                            <Input id="email" type="text" placeholder="Ex. Academic Awards"
                                                v-model="form.education.elementary.honors" class="w-full border-gray-200" />
                                        </div>

                                        <!-- junior -->
                                        <div
                                            class="col-span-3 gap-2 relative flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                Junior High School
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Name of School, College or University</Label>
                                            <Input id="email" type="text" placeholder="Junior High School"
                                                v-model="form.education.junior.name" class="w-full border-gray-200" />
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Years Attended</Label>
                                            <Input id="email" type="text" placeholder="Ex. 2016-2020"
                                                v-model="form.education.junior.years" class="w-full border-gray-200" />
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Honors/Awards Recieved</Label>
                                            <Input id="email" type="text" placeholder="Ex. Academic Awards"
                                                v-model="form.education.junior.honors" class="w-full border-gray-200" />
                                        </div>

                                        <!-- senior -->
                                        <div
                                            class="col-span-3 gap-2 relative flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                Senior High School
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Name of School, College or University</Label>
                                            <Input id="email" type="text" placeholder="Senior High School"
                                                v-model="form.education.senior.name" class="w-full border-gray-200" />
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Years Attended</Label>
                                            <Input id="email" type="text" placeholder="Ex. 2016-2020"
                                                v-model="form.education.senior.years" class="w-full border-gray-200" />
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Honors/Awards Recieved</Label>
                                            <Input id="email" type="text" placeholder="Ex. Academic Awards"
                                                v-model="form.education.senior.honors" class="w-full border-gray-200" />
                                        </div>

                                        <!-- college -->
                                        <div
                                            class="col-span-3 gap-2 relative flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                College
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Name of School, College or University</Label>
                                            <Input id="email" type="text" placeholder="University"
                                                v-model="form.education.college.name" class="w-full border-gray-200" />
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Years Attended</Label>
                                            <Input id="email" type="text" placeholder="Ex. 2016-2020"
                                                v-model="form.education.college.years" class="w-full border-gray-200" />
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Honors/Awards Recieved</Label>
                                            <Input id="email" type="text" placeholder="Ex. Academic Awards"
                                                v-model="form.education.college.honors" class="w-full border-gray-200" />
                                        </div>

                                        <!-- vocational -->
                                        <div
                                            class="col-span-3 gap-2 relative flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                Vocational
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Name of School, College or University</Label>
                                            <Input id="email" type="text" placeholder="School or College University"
                                                v-model="form.education.vocational.name" class="w-full border-gray-200" />
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Years Attended</Label>
                                            <Input id="email" type="text" placeholder="Ex. 2016-2020"
                                                v-model="form.education.vocational.years" class="w-full border-gray-200" />
                                        </div>

                                        <div class=" max-w-sm items-center gap-1.5">
                                            <Label for="email">Honors/Awards Recieved</Label>
                                            <Input id="email" type="text" placeholder="Ex. Academic Awards"
                                                v-model="form.education.vocational.honors" class="w-full border-gray-200" />
                                        </div>

                                        <!-- post -->
                                        <div
                                            class="col-span-3 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                Post Graduate
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Name of School, College or University</Label>
                                            <Input id="email" type="text" placeholder="School or College University"
                                                v-model="form.education.postgrad.name" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Years Attended</Label>
                                            <Input id="email" type="text" placeholder="Ex. 2016-2020"
                                                v-model="form.education.postgrad.years" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Honors/Awards Recieved</Label>
                                            <Input id="email" type="text" placeholder="Ex. Academic Awards"
                                                v-model="form.education.postgrad.honors" class="w-full border-gray-200" />
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
                                    <div
                                        class="bg-white grid grid-cols-4 gap-4 rounded-lg h-1/2 items-center justify-start p-10">
                                        <div class="col-span-4">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">
                                                Family Background</h3>
                                            <p
                                                class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                Please fill-up missing required fields</p>
                                        </div>

                                        <!-- mother -->
                                        <div
                                            class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                MOTHER (Mark (+) if deceased)
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">First Name</Label>
                                            <Input id="email" type="text" placeholder="First Name"
                                                v-model="form.mother.first_name"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Last Name</Label>
                                            <Input id="email" type="text" placeholder="Last Name"
                                                v-model="form.mother.last_name" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Middle Name</Label>
                                            <Input id="email" type="text" placeholder="Middle Name"
                                                v-model="form.mother.middle_name" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Age</Label>
                                            <Input id="email" type="text" placeholder="Age" v-model="form.mother.age"
                                                class="w-full border-gray-200" />
                                        </div>

                                        <div class="col-span-3 grid w-full items-center gap-1.5">
                                            <Label for="email">Address</Label>
                                            <Input id="email" type="text" placeholder="Permanent Address"
                                                v-model="form.mother.address" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Citizenship</Label>
                                            <Input id="email" type="text" placeholder="Ex. Filipino"
                                                v-model="form.mother.citizenship" class="w-full border-gray-200" />
                                        </div>


                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Occupation</Label>
                                            <Input id="email" type="text" placeholder="Occupation"
                                                v-model="form.mother.occupation" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Educational Attainment</Label>
                                            <Input id="email" type="text" placeholder="Ex. College Graudate"
                                                v-model="form.mother.education" class="w-full border-gray-200" />
                                        </div>

                                        <div class="col-span-2 grid w-full items-center gap-1.5">
                                            <Label for="email">Batch (If Alumna of this High School/University)</Label>
                                            <Input id="email" type="text" placeholder="Type N/A if none"
                                                v-model="form.mother.batch" class="w-full border-gray-200" />
                                        </div>

                                        <!-- father -->
                                        <div
                                            class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                FATHER (Mark (+) if deceased)
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">First Name</Label>
                                            <Input id="email" type="text" placeholder="First Name"
                                                v-model="form.father.first_name"
                                                class="w-full border border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Last Name</Label>
                                            <Input id="email" type="text" placeholder="Last Name"
                                                v-model="form.father.last_name" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Middle Name</Label>
                                            <Input id="email" type="text" placeholder="Middle Name"
                                                v-model="form.father.middle_name" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Age</Label>
                                            <Input id="email" type="text" placeholder="Age" v-model="form.father.age"
                                                class="w-full border-gray-200" />
                                        </div>

                                        <div class="col-span-3 grid w-full items-center gap-1.5">
                                            <Label for="email">Address</Label>
                                            <Input id="email" type="text" placeholder="Permanent Address"
                                                v-model="form.father.address" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Citizenship</Label>
                                            <Input id="email" type="text" placeholder="Ex. Filipino"
                                                v-model="form.father.citizenship" class="w-full border-gray-200" />
                                        </div>


                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Occupation</Label>
                                            <Input id="email" type="text" placeholder="Occupation"
                                                v-model="form.father.occupation" class="w-full border-gray-200" />
                                        </div>

                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label for="email">Educational Attainment</Label>
                                            <Input id="email" type="text" placeholder="Ex. College Graduate"
                                                v-model="form.father.education" class="w-full border-gray-200" />
                                        </div>

                                        <div class="col-span-2 grid w-full items-center gap-1.5">
                                            <Label for="email">Batch (If Alumna of this High School/University)</Label>
                                            <Input id="email" type="text" placeholder="Type N/A if none"
                                                v-model="form.father.batch" class="w-full border-gray-200" />
                                        </div>

                                        <!-- fam  info -->
                                        <div
                                            class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                PLEASE PUT A CHECK MARK (✓) THE APPROPRIATE BOX.
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div class="col-span-2 grid w-full items-center gap-1.5">
                                            <Label for="marital-status" class="text-gray-500">C.1 Marital Status of
                                                Parents</Label>
                                            <RadioGroup default-value="comfortable" class="grid grid-cols-2 gap-2"
                                                v-model="form.marital_status">
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="m1" value="Married" />
                                                    <Label for=",1">Married</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id=",2" value="living_together" />
                                                    <Label for=",2">Living Together</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id=",3" value="not_married" />
                                                    <Label for=",3">Not Married</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id=",4" value="seperated" />
                                                    <Label for="r,4">Seperated</Label>
                                                </div>
                                            </RadioGroup>
                                        </div>


                                        <div class="col-span-2 grid w-full items-center gap-1.5">
                                            <Label for="marital-status" class="text-gray-500">C.2 Monthly Family
                                                Income</Label>
                                            <RadioGroup default-value="comfortable" class="grid grid-cols-2 gap-2"
                                                v-model="form.monthly_income">
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i1" value="below" />
                                                    <Label for="i1">10,000 and below</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="r2" value="mid" />
                                                    <Label for="ri2">20,001 - 30,000</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i3" value="average" />
                                                    <Label for="i3">10,001 - 20,000</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i4" value="above" />
                                                    <Label for="i4">30,001 and above</Label>
                                                </div>
                                            </RadioGroup>
                                        </div>

                                        <div class="col-span-1 grid w-full items-center gap-1.5">
                                            <Label for="email" class="text-gray-500">C.3 Other Source of Income</Label>
                                            <Input id="email" type="text" placeholder="Income Sources"
                                                v-model="form.other_income" class="w-full border-gray-200" />
                                        </div>

                                        <div class="col-span-3 grid w-full items-center gap-1.5">
                                            <Label for="marital-status" class="text-gray-500">C.4 Family Type of
                                                Housing</Label>
                                            <RadioGroup default-value="comfortable" class="flex flex-row gap-2"
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
                                                    <input v-if="family_housing === 'other'" type="text"
                                                        v-model="otherText" placeholder="Type here..."
                                                        class="border-b border-gray-400 outline-none w-32 px-2 text-sm" />
                                                </div>
                                            </RadioGroup>
                                        </div>

                                        <!-- siblings info -->
                                        <div
                                            class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                                C.6 Name of Siblings in the Family
                                            </h3>
                                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                        </div>

                                        <div class="col-span-4 grid w-full items-center gap-1.5 space-y-4">
                                            <div v-for="(entry, index) in form.formEntries" :key="index"
                                                class="entry border border-gray-200 p-3 col-span-4 grid grid-cols-3 w-full items-center gap-3">
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
                                                <button v-if="formEntries.length > 1" @click="removeEntry(index)"
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
                                    <div
                                        class="bg-white grid grid-cols-4 gap-2 rounded-lg h-1/2 items-center justify-start p-10">
                                        <div class="col-span-2">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">
                                                Organizational Membership</h3>
                                            <p
                                                class="font-semibold text-[11px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                Please fill-up missing required fields</p>
                                        </div>

                                        <div class="col-span-4 grid w-full items-center gap-1.5 space-y-4">
                                            <div v-for="(entry, index) in form.organizations" :key="index"
                                                class="border border-gray-200 p-3 col-span-4 grid grid-cols-4 w-full items-center gap-3">

                                                <!-- Name of Organization -->
                                                <div class="col-span-2 grid w-full items-center gap-1.5">
                                                    <Label :for="'organization_' + index">Name of Organization</Label>
                                                    <Input :id="'organization_' + index" type="text"
                                                        placeholder="Ex. USSG" v-model="entry.name"
                                                        class="w-full border border-gray-200" />
                                                </div>

                                                <!-- Membership -->
                                                <div class="col-span-1 grid w-full items-center gap-1.5">
                                                    <Label :for="'membership_' + index">Inclusive Dates of
                                                        Membership</Label>
                                                    <Input :id="'membership_' + index" type="text"
                                                        placeholder="Ex. 2022 - Present"
                                                        v-model="entry.membership_dates"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Position -->
                                                <div class="col-span-1 grid w-full items-center gap-1.5">
                                                    <Label :for="'position_' + index">Position Held</Label>
                                                    <Input :id="'position_' + index" type="text"
                                                        placeholder="Ex. Representative" v-model="entry.position"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Remove Button -->
                                                <button v-if="organizations.length > 1"
                                                    @click="removeOrganization(index)"
                                                    class="bg-red-900 text-white px-3 py-1 rounded h-10 flex items-center space-x-5">
                                                    <span class="material-symbols-rounded mr-2">
                                                        remove
                                                    </span> Remove
                                                </button>
                                            </div>

                                            <!-- Add Another Button -->
                                            <button @click="addOrganization"
                                                class="bg-blue-900 text-white px-3 py-1 rounded h-10 flex items-center space-x-5">
                                                <span class="material-symbols-rounded mr-2">
                                                    add_circle
                                                </span> Add Another
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
                                        <div
                                            class="bg-white grid grid-cols-2 gap-2 rounded-lg h-1/2 items-center justify-start p-10">
                                            <div class="col-span-2">
                                                <h3
                                                    class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">
                                                    Account Setup</h3>
                                                <p
                                                    class="font-semibold text-[11px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                    Please change and update you password to your preference</p>
                                            </div>

                                            <div class="col-span-2 flex px-24 gap-10">
                                                <!-- Image Upload Column (20%) -->
                                                <div class="w-[30%] flex flex-col items-center gap-1.5">
                                                    <Label for="pic">Insert Profile Picture</Label>
                                                    <label for="dropzone-img"
                                                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
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

                                                <!-- Form Fields Column (80%) -->
                                                <div class="w-[70%] flex flex-col gap-3">
                                                    <div class="grid grid-cols-2 gap-3">
                                                        <div class="flex flex-col gap-1.5">
                                                            <Label for="first_name">First Name</Label>
                                                            <Input id="first_name" type="text" placeholder="First Name"
                                                                v-model="form.first_name"
                                                                class="w-full border border-gray-200" />
                                                        </div>

                                                        <div class="flex flex-col gap-1.5">
                                                            <Label for="last_name">Last Name</Label>
                                                            <Input id="last_name" type="text" placeholder="Last Name"
                                                                v-model="form.last_name"
                                                                class="w-full border border-gray-200" />
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-col gap-1.5">
                                                        <Label for="email">Email</Label>
                                                        <Input id="email" type="email" placeholder="Email"
                                                            v-model="form.email" readonly
                                                            class="w-full border border-gray-200" />
                                                    </div>

                                                    <div class="flex flex-col gap-1.5">
                                                        <Label for="password">Password</Label>
                                                        <Input id="password" type="text" placeholder="Password"
                                                            v-model="form.password" class="w-full border-gray-200" />
                                                    </div>

                                                    <div class="flex flex-col gap-1.5">
                                                        <Label for="confirm_password">Confirm Password</Label>
                                                        <Input id="confirm_password" type="text"
                                                            placeholder="Confirm Password"
                                                            v-model="form.confirm_password"
                                                            class="w-full border-gray-200" />
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-span-2 space-x-2 flex justify-end mt-4">
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
import { ref, nextTick, onMounted } from 'vue';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Calendar } from '@/Components/ui/calendar'

import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover'
import { cn } from '@/lib/utils'
import { DateFormatter, getLocalTimeZone, } from '@internationalized/date'
import { Calendar as CalendarIcon } from 'lucide-vue-next'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'

const df = new DateFormatter('en-US', {
    dateStyle: 'long',
})

const user = usePage().props.auth.user;

const form = ref({
    name: user.name,
    email: user.email,
    first_name: user.first_name,
    middle_name: user.middle_name,
    last_name: user.last_name,
    password: '',
    confirm_password: '',
    suffix: '',
    birthdate: '',
    birthplace: '',
    age: '',
    gender: '',
    civil_status: '',
    religion: '',
    guardian_name: '',
    relationship: '',
    education: {
        elementary: { name: '', years: '', honors: '' },
        junior: { name: '', years: '', honors: '' },
        senior: { name: '', years: '', honors: '' },
        college: { name: '', years: '', honors: '' },
        vocational: { name: '', years: '', honors: '' },
        postgrad: { name: '', years: '', honors: '' },
    },
    mother: { first_name: '', last_name: '', middle_name: '', age: '', address: '', citizenship: '', occupation: '', education: '', batch: '' },
    father: { first_name: '', last_name: '', middle_name: '', age: '', address: '', citizenship: '', occupation: '', education: '', batch: '' },
    marital_status: '',
    monthly_income: '',
    other_income: '',
    family_housing: '',
    otherText: '',
    formEntries: [{ first_name: '', last_name: '', middle_name: '', age: '', occupation: '' }],
    organizations: [{ name: '', membership_dates: '', position: '' }],
    img: null,
    imgName: null,
    imgPreview: null,
});

const formatDate = (date) => {
    if (!date) return "Pick a date";
    return new Intl.DateTimeFormat("en-US", { dateStyle: "medium" }).format(new Date(date));
};


// const submit = () => {
//     form.post(route('student.verify-account.verifying'), {
//         onFinish: () => form.reset(),
//     });
// };

const submit = async () => {
    try {
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
};

const nextStep = () => {
    if (activeStep.value < steps.value.length - 1) {
        activeStep.value++;
    }
};

const prevStep = () => {
    if (activeStep.value > 0) {
        activeStep.value--;
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
const addEntry = () => {
    saveScrollPosition(); // Save scroll position before adding entry
    form.value.formEntries = formEntries.value;
    formEntries.value.push({ first_name: '', last_name: '', middle_name: '', age: '', occupation: '' });

    nextTick(() => restoreScrollPosition()); // Restore scroll position after DOM updates
};

const removeEntry = (index) => {
    saveScrollPosition(); // Save scroll position before removing entry
    formEntries.value.splice(index, 1);
    nextTick(() => restoreScrollPosition()); // Restore scroll position after DOM updates
};

// This can be used to restore the scroll position when the page first loads
onMounted(() => {
    restoreScrollPosition(); // Make sure to restore scroll position after initial load
});

const organizations = ref([
    { name: '', membership_dates: '', position: '' } // Initial entry
]);

// Add a new organization entry
const addOrganization = () => {
    saveScrollPosition(); // Save scroll position before adding entry
    form.value.organizations = organizations.value;
    organizations.value.push({ name: '', membership_dates: '', position: '' });
    nextTick(() => restoreScrollPosition()); // Restore scroll position after DOM updates
};

// Remove an organization entry smoothly
const removeOrganization = (index) => {
    if (organizations.value.length > 1) {
        organizations.value.splice(index, 1);
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