<template>
    <Head title="Verification" />
    <div class="w-screen h-screen bg-gray-100">
        <form @submit.prevent="submit">
            <div class="w-full flex flex-row justify-between bg-white shadow-sm items-center px-10">
                <h1 class="text-3xl font-bold font-soratext-left p-3 mx-10">Set up your Profile</h1>
                <Link :href="route('logout')" method="post" as="button">
                <span class="material-symbols-rounded" style="color: black;" v-tooltip.right="!dataOpenSideBar ? 'Logout' : ''">
                    logout
                </span>
                </Link>
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Set Up
                    </button>
                </div>
            </div>
            <div class="py-3 h-full bg-gray-100">
                <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 ">
                    <div class="flex flex-col space-y-5">
                        <div class="bg-primary text-white text-3xl font-sans font-bold p-7 rounded-lg">
                            Greetings! {{ user.name }}
                        </div>

                        <!-- Stepper Navigation -->
                        <div class="flex flex-row items-center justify-center mt-5 relative w-full">
                            <!-- Background Gray Line (Full Width) -->
                            <div class="absolute top-[30%] left-0 w-full h-1 bg-gray-100 rounded-lg"></div>

                            <div 
                            v-for="(step, index) in steps" 
                            :key="index" 
                            class="relative flex flex-col items-center w-full"
                            @click="goToStep(index)"
                            >
                            <!-- Progress Line (Only for Steps > 0) -->
                            <div 
                                v-if="index !== 0" 
                                class="absolute top-[30%] left-[-50%] h-1 w-full transition-all"
                                :class="{ 
                                'bg-blue-900': activeStep >= index, 
                                'bg-gray-200': activeStep < index 
                                }"
                            ></div>

                            <!-- Step Circle with Icon -->
                            <span 
                                class="material-symbols-rounded text-2xl flex items-center justify-center w-10 h-10 rounded-full transition-all z-10"
                                :class="{
                                'text-white bg-blue-900': activeStep === index, 
                                'text-yellow-600 bg-yellow-100': activeStep > index,
                                'text-gray-400 bg-gray-100': activeStep < index
                                }"
                            >
                                {{ step.icon }}
                            </span>

                            <!-- Step Label -->
                            <div class="mt-2 text-sm font-medium" :class="{ 'text-blue-900': activeStep >= index, 'text-gray-500': activeStep < index }">
                                {{ step.label }}
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 ">
                    <div class="flex flex-col space-y-5 mt-10">

                        <div class="stepper-container">
                            <!-- Step Content -->
                            <div class="flex-grow">
                            <div v-if="activeStep === 0">
                                <div class="bg-white grid grid-cols-3 gap-6 rounded-lg h-1/2 items-center justify-start p-10">
                                    <div class="col-span-3">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">Account Information</h3>
                                    <p class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">Please fill-up missing required fields</p>
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">First Name</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.first_name" class="w-full border border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Last Name</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.last_name" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Middle Name</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.middle_name" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Suffix Name</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.suffix" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Date of Birth</Label>
                                        <Popover>
                                            <PopoverTrigger as-child>
                                            <Button
                                                variant="outline"
                                                :class="cn(
                                                'w-full h-10 justify-start text-left font-normal',
                                                !value && 'text-muted-foreground',
                                                )"
                                            >
                                                <CalendarIcon class="mr-2 h-4 w-4" />
                                                {{ value ? df.format(value.toDate(getLocalTimeZone())) : "Pick a date" }}
                                            </Button>
                                            </PopoverTrigger>
                                            <PopoverContent class="w-auto p-0">
                                            <Calendar v-model="form.birthdate" initial-focus />
                                            </PopoverContent>
                                        </Popover>
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Place of Birth</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.birthplace" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Age</Label>
                                        <Input id="email" type="number" placeholder="Firt Name" v-model="form.age" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Gender</Label>
                                        <Select>
                                            <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Select Gender" />
                                            </SelectTrigger>
                                            <SelectContent>
                                            <SelectGroup>
                                                <!-- <SelectLabel>Gender</SelectLabel> -->
                                                <SelectItem value="LGBTQ">
                                                LGBTQ
                                                </SelectItem>
                                                <SelectItem value="banana">
                                                Banana
                                                </SelectItem>
                                            </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Civil Status</Label>
                                        <Select>
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
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.religion" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Guardian Name</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.guardian" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Relationship</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.relationship" class="w-full border-gray-200" />
                                    </div>
                                    
                                    <div class="col-span-3 flex justify-end mt-4">
                                        <button type="submit" @click="nextStep"  class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                            Next</button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="activeStep === 1">
                                <div class="bg-white grid grid-cols-3 gap-2 rounded-lg h-1/2 items-center justify-start p-10">
                                    <div class="col-span-3">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">Educational Background</h3>
                                    <p class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white">Please fill-up missing required fields. Put N/A if not applicable</p>
                                    </div>

                                    <!-- elementary -->
                                    <div class="col-span-3 gap-2 relative w-full flex flex-row justify-center items-center mt-4 mb-2">
                                    <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white text-center">
                                        Elementary
                                    </h3>
                                    <div class="pl-2 w-full h-0.5 bg-gray-200 justify-center items-center rounded-lg"></div>
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Name of School, College or University</Label>
                                        <Input id="email" type="text" placeholder="Elementary School" v-model="form.elementary" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Years Attended</Label>
                                        <Input id="email" type="text" placeholder="Ex. 2016-2020" v-model="form.e_years" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Honors/Awards Recieved</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.e_honors" class="w-full border-gray-200" />
                                    </div>

                                    <!-- junior -->
                                    <div class="col-span-3 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                    <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                        Junior High School
                                    </h3>
                                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Name of School, College or University</Label>
                                        <Input id="email" type="text" placeholder="Junior High School" v-model="form.junior" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Years Attended</Label>
                                        <Input id="email" type="text" placeholder="Ex. 2016-2020" v-model="form.j_years" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Honors/Awards Recieved</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.j_honors" class="w-full border-gray-200" />
                                    </div>

                                    <!-- senior -->
                                    <div class="col-span-3 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                    <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                        Senior High School
                                    </h3>
                                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Name of School, College or University</Label>
                                        <Input id="email" type="text" placeholder="Senior High School" v-model="form.senior" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Years Attended</Label>
                                        <Input id="email" type="text" placeholder="Ex. 2016-2020" v-model="form.s_years" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Honors/Awards Recieved</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.s_honors" class="w-full border-gray-200" />
                                    </div>

                                    <!-- college -->
                                    <div class="col-span-3 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                    <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                        College
                                    </h3>
                                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Name of School, College or University</Label>
                                        <Input id="email" type="text" placeholder="University" v-model="form.college" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Years Attended</Label>
                                        <Input id="email" type="text" placeholder="Ex. 2016-2020" v-model="form.c_years" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Honors/Awards Recieved</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.c_honors" class="w-full border-gray-200" />
                                    </div>

                                    <!-- vocational -->
                                    <div class="col-span-3 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                    <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                        Vocational
                                    </h3>
                                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Name of School, College or University</Label>
                                        <Input id="email" type="text" placeholder="School or College University" v-model="form.vocational" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Years Attended</Label>
                                        <Input id="email" type="text" placeholder="Ex. 2016-2020" v-model="form.v_years" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Honors/Awards Recieved</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.v_honors" class="w-full border-gray-200" />
                                    </div>

                                    <!-- post -->
                                    <div class="col-span-3 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                    <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                        Post Graduate
                                    </h3>
                                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Name of School, College or University</Label>
                                        <Input id="email" type="text" placeholder="School or College University" v-model="form.postgrad" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Years Attended</Label>
                                        <Input id="email" type="text" placeholder="Ex. 2016-2020" v-model="form.p_years" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Honors/Awards Recieved</Label>
                                        <Input id="email" type="text" placeholder="Firt Name" v-model="form.p_honors" class="w-full border-gray-200" />
                                    </div>
                                    
                                    <div class="col-span-3 space-x-2 flex justify-end mt-4">
                                        <!-- Back Button -->
                                        <button 
                                            type="button" 
                                            @click="prevStep" 
                                            :disabled="activeStep === 0"
                                            class="text-white bg-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none 
                                                focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg 
                                                text-sm px-5 py-2.5 text-center mb-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            Back
                                        </button>

                                        <!-- Next Button -->
                                        <button 
                                            type="submit" 
                                            @click="nextStep" 
                                            :disabled="activeStep === steps.length - 1"
                                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                                                hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                focus:ring-blue-300 dark:focus:ring-blue-800 font-medium 
                                                rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"
                                        >
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="activeStep === 2">
                                <div class="bg-white grid grid-cols-4 gap-3 rounded-lg h-1/2 items-center justify-start p-10">
                                    <div class="col-span-4">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">Family Background</h3>
                                    <p class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">Please fill-up missing required fields</p>
                                    </div>

                                    <!-- mother -->
                                    <div class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                    <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                        MOTHER (Mark (+) if deceased)
                                    </h3>
                                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">First Name</Label>
                                        <Input id="email" type="text" placeholder="First Name" v-model="form.m_first_name" class="w-full border border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Last Name</Label>
                                        <Input id="email" type="text" placeholder="Last Name" v-model="form.m_last_name" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Middle Name</Label>
                                        <Input id="email" type="text" placeholder="Middle Name" v-model="form.m_middle_name" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Age</Label>
                                        <Input id="email" type="text" placeholder="Age" v-model="form.m_age" class="w-full border-gray-200" />
                                    </div>

                                    <div class="col-span-3 grid w-full items-center gap-1.5">
                                        <Label for="email">Address</Label>
                                        <Input id="email" type="text" placeholder="Permanent Address" v-model="form.m_address" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Citizenship</Label>
                                        <Input id="email" type="text" placeholder="Ex. Filipino" v-model="form.m_citizenship" class="w-full border-gray-200" />
                                    </div>
                                    

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Occupation</Label>
                                        <Input id="email" type="text" placeholder="Ex. Filipino" v-model="form.m_occupation" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Educational Attainment</Label>
                                        <Input id="email" type="text" placeholder="Ex. Filipino" v-model="form.m_education" class="w-full border-gray-200" />
                                    </div>

                                    <div class="col-span-2 grid w-full items-center gap-1.5">
                                        <Label for="email">Batch (If Alumna of this High School/University)</Label>
                                        <Input id="email" type="text" placeholder="Ex. Filipino" v-model="form.m_batch" class="w-full border-gray-200" />
                                    </div>

                                    <!-- father -->
                                    <div class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                    <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                        FATHER (Mark (+) if deceased)
                                    </h3>
                                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">First Name</Label>
                                        <Input id="email" type="text" placeholder="First Name" v-model="form.f_first_name" class="w-full border border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Last Name</Label>
                                        <Input id="email" type="text" placeholder="Last Name" v-model="form.f_last_name" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Middle Name</Label>
                                        <Input id="email" type="text" placeholder="Middle Name" v-model="form.f_middle_name" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Age</Label>
                                        <Input id="email" type="text" placeholder="Age" v-model="form.f_age" class="w-full border-gray-200" />
                                    </div>

                                    <div class="col-span-3 grid w-full items-center gap-1.5">
                                        <Label for="email">Address</Label>
                                        <Input id="email" type="text" placeholder="Permanent Address" v-model="form.f_address" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Citizenship</Label>
                                        <Input id="email" type="text" placeholder="Ex. Filipino" v-model="form.f_citizenship" class="w-full border-gray-200" />
                                    </div>
                                    

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Occupation</Label>
                                        <Input id="email" type="text" placeholder="Ex. Filipino" v-model="form.f_occupation" class="w-full border-gray-200" />
                                    </div>

                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                        <Label for="email">Educational Attainment</Label>
                                        <Input id="email" type="text" placeholder="Ex. Filipino" v-model="form.f_education" class="w-full border-gray-200" />
                                    </div>

                                    <div class="col-span-2 grid w-full items-center gap-1.5">
                                        <Label for="email">Batch (If Alumna of this High School/University)</Label>
                                        <Input id="email" type="text" placeholder="Ex. Filipino" v-model="form.f_batch" class="w-full border-gray-200" />
                                    </div>

                                    <!-- fam  info -->
                                    <div class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                    <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                        PLEASE PUT A CHECK MARK (✓) THE APPROPRIATE BOX. 
                                    </h3>
                                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                    </div>

                                    <div class="col-span-2 grid w-full items-center gap-1.5">
                                        <Label for="marital-status" class="text-gray-500">C.1 Marital Status of Parents</Label>
                                        <RadioGroup default-value="comfortable" class="grid grid-cols-2 gap-2">
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r1" value="Married" />
                                            <Label for="r1">Married</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r2" value="living_together" />
                                            <Label for="r2">Living Together</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r3" value="not_married" />
                                            <Label for="r3">Not Married</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r4" value="seperated" />
                                            <Label for="r4">Seperated</Label>
                                            </div>
                                        </RadioGroup>
                                    </div>


                                    <div class="col-span-2 grid w-full items-center gap-1.5">
                                        <Label for="marital-status" class="text-gray-500">C.2 Monthly Family Income</Label>
                                        <RadioGroup default-value="comfortable" class="grid grid-cols-2 gap-2">
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r1" value="below" />
                                            <Label for="r1">10,000 and below</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r2" value="mid" />
                                            <Label for="r2">20,001 - 30,000</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r3" value="average" />
                                            <Label for="r3">10,001 - 20,000</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r4" value="above" />
                                            <Label for="r4">30,001 and above</Label>
                                            </div>
                                        </RadioGroup>
                                    </div>

                                    <div class="col-span-1 grid w-full items-center gap-1.5">
                                        <Label for="email" class="text-gray-500">C.3 Other Source of Income</Label>
                                        <Input id="email" type="text" placeholder="Income Sources" v-model="form.income_sources" class="w-full border-gray-200" />
                                    </div>

                                    <div class="col-span-3 grid w-full items-center gap-1.5">
                                        <Label for="marital-status" class="text-gray-500">C.4 Family Type of Housing</Label>
                                        <RadioGroup default-value="comfortable" class="flex flex-row gap-2">
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r1" value="owned" />
                                            <Label for="r1">Owned</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r2" value="settler" />
                                            <Label for="r2">Settler</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r3" value="rental" />
                                            <Label for="r3">Rental</Label>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                            <RadioGroupItem id="r4" value="other" v-model="otherSelected" />
                                            <Label for="r4">Others, please specify:</Label>
                                            <input
                                                v-if="otherSelected === 'other'"
                                                type="text"
                                                v-model="otherText"
                                                placeholder="Type here..."
                                                class="border-b border-gray-400 outline-none w-32 px-2 text-sm"
                                            />
                                            </div>
                                        </RadioGroup>
                                    </div>

                                    <!-- siblings info -->
                                    <div class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                    <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                        C.6 Name of Siblings in the Family 
                                    </h3>
                                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                    </div>

                                    <div class="col-span-4 grid w-full items-center gap-1.5 space-y-4">
                                        <div 
                                        v-for="(entry, index) in formEntries" 
                                        :key="index" 
                                        class="border border-gray-200 p-3 col-span-4 grid grid-cols-3 w-full items-center gap-3"
                                        >
                                        
                                        <!-- First Name -->
                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label :for="'first_name_' + index">First Name</Label>
                                            <Input 
                                            :id="'first_name_' + index" 
                                            type="text" 
                                            placeholder="First Name" 
                                            v-model="entry.first_name" 
                                            class="w-full border border-gray-200"
                                            />
                                        </div>

                                        <!-- Last Name -->
                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label :for="'last_name_' + index">Last Name</Label>
                                            <Input 
                                            :id="'last_name_' + index" 
                                            type="text" 
                                            placeholder="Last Name" 
                                            v-model="entry.last_name" 
                                            class="w-full border-gray-200"
                                            />
                                        </div>

                                        <!-- Middle Name -->
                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label :for="'middle_name_' + index">Middle Name</Label>
                                            <Input 
                                            :id="'middle_name_' + index" 
                                            type="text" 
                                            placeholder="Middle Name" 
                                            v-model="entry.middle_name" 
                                            class="w-full border-gray-200"
                                            />
                                        </div>

                                        <!-- Age -->
                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label :for="'age_' + index">Age</Label>
                                            <Input 
                                            :id="'age_' + index" 
                                            type="number" 
                                            placeholder="Age" 
                                            v-model="entry.age" 
                                            class="w-full border-gray-200"
                                            />
                                        </div>

                                        <!-- Occupation -->
                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                            <Label :for="'occupation_' + index">Occupation</Label>
                                            <Input 
                                            :id="'occupation_' + index" 
                                            type="text" 
                                            placeholder="Occupation" 
                                            v-model="entry.occupation" 
                                            class="w-full border-gray-200"
                                            />
                                        </div>

                                        <!-- Remove Button -->
                                        <button 
                                            v-if="formEntries.length > 1" 
                                            @click="removeEntry(index)" 
                                            class="bg-red-900 text-white px-3 py-1 rounded h-10 flex items-center space-x-5"
                                        >
                                        <span class="material-symbols-rounded mr-2">
                                        remove
                                        </span> Remove
                                        </button>
                                        </div>

                                        <!-- Add Another Button -->
                                        <button 
                                        @click="addEntry" 
                                        class="bg-blue-900 text-white px-3 py-1 rounded h-10 flex items-center space-x-5"
                                        >
                                        <span class="material-symbols-rounded mr-2">
                                        add_circle
                                        </span> Add Another
                                        </button>
                                    </div>
                                    
                                    
                                    <div class="col-span-4 space-x-2 flex justify-end mt-4">
                                        <!-- Back Button -->
                                        <button 
                                            type="button" 
                                            @click="prevStep" 
                                            :disabled="activeStep === 0"
                                            class="text-white bg-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none 
                                                focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg 
                                                text-sm px-5 py-2.5 text-center mb-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            Back
                                        </button>

                                        <!-- Next Button -->
                                        <button 
                                            type="submit" 
                                            @click="nextStep" 
                                            :disabled="activeStep === steps.length - 1"
                                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                                                hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                focus:ring-blue-300 dark:focus:ring-blue-800 font-medium 
                                                rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"
                                        >
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="activeStep === 3">
                                <div class="bg-white grid grid-cols-4 gap-2 rounded-lg h-1/2 items-center justify-start p-10">
                                    <div class="col-span-2">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">Organizational Membership</h3>
                                    <p class="font-semibold text-[11px] font-inter uppercase text-gray-400 dark:text-white mb-4">Please fill-up missing required fields</p>
                                    </div>

                                    <div class="col-span-4 grid w-full items-center gap-1.5 space-y-4">
                                        <div 
                                        v-for="(entry, index) in organizations" 
                                        :key="index" 
                                        class="border border-gray-200 p-3 col-span-4 grid grid-cols-4 w-full items-center gap-3"
                                        >

                                        <!-- Name of Organization -->
                                        <div class="col-span-2 grid w-full items-center gap-1.5">
                                            <Label :for="'organization_' + index">Name of Organization</Label>
                                            <Input 
                                            :id="'organization_' + index" 
                                            type="text" 
                                            placeholder="Ex. USSG" 
                                            v-model="entry.name" 
                                            class="w-full border border-gray-200"
                                            />
                                        </div>

                                        <!-- Membership -->
                                        <div class="col-span-1 grid w-full items-center gap-1.5">
                                            <Label :for="'membership_' + index">Inclusive Dates of Membership</Label>
                                            <Input 
                                            :id="'membership_' + index" 
                                            type="text" 
                                            placeholder="Ex. 2022 - Present" 
                                            v-model="entry.membership_dates" 
                                            class="w-full border-gray-200"
                                            />
                                        </div>

                                        <!-- Position -->
                                        <div class="col-span-1 grid w-full items-center gap-1.5">
                                            <Label :for="'position_' + index">Position Held</Label>
                                            <Input 
                                            :id="'position_' + index" 
                                            type="text" 
                                            placeholder="Ex. Representative" 
                                            v-model="entry.position" 
                                            class="w-full border-gray-200"
                                            />
                                        </div>

                                        <!-- Remove Button -->
                                        <button 
                                            v-if="organizations.length > 1" 
                                            @click="removeOrganization(index)" 
                                            class="bg-red-900 text-white px-3 py-1 rounded h-10 flex items-center space-x-5"
                                        >
                                        <span class="material-symbols-rounded mr-2">
                                        remove
                                        </span> Remove
                                        </button>
                                        </div>

                                        <!-- Add Another Button -->
                                        <button 
                                        @click="addOrganization" 
                                        class="bg-blue-900 text-white px-3 py-1 rounded h-10 flex items-center space-x-5"
                                        >
                                        <span class="material-symbols-rounded mr-2">
                                        add_circle
                                        </span> Add Another
                                        </button>
                                    </div>
                                    
                                    <div class="col-span-4 space-x-2 flex justify-end mt-4">
                                        <!-- Back Button -->
                                        <button 
                                            type="button" 
                                            @click="prevStep" 
                                            :disabled="activeStep === 0"
                                            class="text-white bg-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none 
                                                focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg 
                                                text-sm px-5 py-2.5 text-center mb-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            Back
                                        </button>

                                        <!-- Next Button -->
                                        <button 
                                            type="submit" 
                                            @click="nextStep" 
                                            :disabled="activeStep === steps.length - 1"
                                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                                                hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                focus:ring-blue-300 dark:focus:ring-blue-800 font-medium 
                                                rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"
                                        >
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="activeStep === 4">
                                <form @submit.prevent="submit">
                                    <div class="bg-white grid grid-cols-2 gap-2 rounded-lg h-1/2 items-center justify-start p-10">
                                        <div class="col-span-2">
                                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">Account Setup</h3>
                                            <p class="font-semibold text-[11px] font-inter uppercase text-gray-400 dark:text-white mb-4">Please change and update you password to your preference</p>
                                        </div>

                                        <div class="col-span-2 grid grid-cols-2 gap-3 px-24">
                                            <div class="col-span-1 grid w-full items-center gap-1.5">
                                                <Label for="email">First Name</Label>
                                                <Input id="email" type="text" placeholder="First Name" v-model="form.first_name" class="w-full border border-gray-200" />
                                            </div>

                                            <div class="col-span-1 grid w-full items-center gap-1.5">
                                                <Label for="email">Last Name</Label>
                                                <Input id="email" type="text" placeholder="Last Name" v-model="form.last_name" class="w-full border border-gray-200" />
                                            </div>

                                            <div class="col-span-2 grid w-full items-center gap-1.5">
                                                <Label for="email">Email</Label>
                                                <Input id="email" type="email" placeholder="Firt Name" v-model="form.email" readonly class="w-full border border-gray-200" />
                                            </div>

                                            <div class="col-span-2 grid w-full items-center gap-1.5">
                                                <Label for="email">Password</Label>
                                                <Input id="email" type="text" placeholder="Password" v-model="form.password" class="w-full border-gray-200" />
                                            </div>

                                            <div class="col-span-2 grid w-full items-center gap-1.5">
                                                <Label for="email">Confirm Password</Label>
                                                <Input id="email" type="text" placeholder="Confirm Password" v-model="form.confirm_password" class="w-full border-gray-200" />
                                            </div>

                                        </div>
                                        
                                        <div class="col-span-2 space-x-2 flex justify-end mt-4">
                                            <!-- Back Button -->
                                            <button 
                                                type="button" 
                                                @click="prevStep" 
                                                :disabled="activeStep === 0"
                                                class="text-white bg-gray-400 hover:bg-gray-500 focus:ring-4 focus:outline-none 
                                                    focus:ring-gray-300 dark:focus:ring-gray-800 font-medium rounded-lg 
                                                    text-sm px-5 py-2.5 text-center mb-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                            >
                                                Back
                                            </button>

                                            <!-- Submit Button -->
                                            <button 
                                                type="submit" 
                                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                                                    hover:bg-gradient-to-br focus:ring-4 focus:outline-none 
                                                    focus:ring-blue-300 dark:focus:ring-blue-800 font-medium 
                                                    rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"
                                            >
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </div>
                        </div>

                        <!-- account Verification -->
                        

                        <!-- account Verification -->
                       


                        <!-- <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Middle Name</label>
                            </FloatLabel>

                            <div class="flex flex-col gap-2">
                                <div class="flex flex-row gap-2">
                                    <FloatLabel variant="on" class="w-full">
                                        <InputText id="on_label" v-model="form.name" class="w-full" />
                                        <label for="on_label">First Name</label>
                                    </FloatLabel>

                                    <FloatLabel variant="on" class="w-full">
                                        <InputText id="on_label" v-model="form.name" class="w-full" />
                                        <label for="on_label">First Name</label>
                                    </FloatLabel>
                                </div>
                            </div> -->

                            <!-- <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Middle Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Middle Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full col-span-2">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Middle Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Middle Name</label>
                            </FloatLabel>
                            
                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Middle Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full col-span-2">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Middle Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full col-span-2">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Middle Name</label>
                            </FloatLabel> -->



                        <!-- college education -->
                        <!-- <div class="bg-white grid grid-cols-2 gap-3 rounded-lg h-1/2 items-center justify-start px-4 py-4">
                            <div class="col-span-2"><h3 class="font-semibold text-gray-900 dark:text-white">Personal Information</h3></div>
                            <div class="col-span-2"><h3 class="font-semibold text-gray-900 dark:text-white">College Education</h3></div>

                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Course</label>
                            </FloatLabel>

                            <div class="flex flex-row gap-2">
                                <FloatLabel variant="on" class="w-full">
                                    <InputText id="on_label" v-model="form.name" class="w-full" />
                                    <label for="on_label">Year & Section</label>
                                </FloatLabel>

                                <FloatLabel variant="on" class="w-full">
                                    <InputText id="on_label" v-model="form.name" class="w-full" />
                                    <label for="on_label">Student ID</label>
                                </FloatLabel>
                            </div>
                            
                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Campus</label>
                            </FloatLabel>

                            <div class="flex flex-col gap-2">
                                <div class="flex flex-row gap-2">
                                    <div class="flex items-center">
                                        <input id="default-radio-1" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Default radio</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="default-radio-1" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Default radio</label>
                                    </div>
                                </div>
                                <div class="flex flex-row gap-2">
                                    <div class="flex items-center">
                                        <input id="default-radio-1" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Default radio</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="default-radio-1" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="default-radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Default radio</label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="bg-white grid grid-cols-3 gap-3 rounded-lg h-1/2 items-center justify-start px-4 py-4">
                            <div class="col-span-2"><h3 class="font-semibold text-gray-900 dark:text-white">Education</h3></div>

                            <FloatLabel variant="on" class="w-full col-span-2">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Course</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full col-span-1">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Campus</label>
                            </FloatLabel>
                            
                            <FloatLabel variant="on" class="w-full col-span-2">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Course</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full col-span-1">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Campus</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full col-span-2">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Course</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full col-span-1">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Campus</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full col-span-2">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Course</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full col-span-1">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Campus</label>
                            </FloatLabel>

                        </div>

                        <div class="bg-white grid grid-cols-2 gap-3 rounded-lg h-1/2 items-center justify-start px-4 py-4">
                            <div class="col-span-2"><h3 class="font-semibold text-gray-900 dark:text-white">Family Background</h3></div>
                            <div class="col-span-2"><h3 class="font-semibold text-gray-900 dark:text-white">Mother <span class="font-italic">(Leave Blank if deceased)</span></h3></div>

                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">First Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Last Name</label>
                            </FloatLabel>
                            
                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Course</label>
                            </FloatLabel>

                            <div class="flex flex-row gap-2">
                                <FloatLabel variant="on" class="w-full">
                                    <InputText id="on_label" v-model="form.name" class="w-full" />
                                    <label for="on_label">Year & Section</label>
                                </FloatLabel>

                                <FloatLabel variant="on" class="w-full">
                                    <InputText id="on_label" v-model="form.name" class="w-full" />
                                    <label for="on_label">Student ID</label>
                                </FloatLabel>
                            </div>

                            <FloatLabel variant="on" class="w-full col-span-2">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Course</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">First Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Last Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full col-span-2">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Course</label>
                            </FloatLabel>


                            <div class="col-span-2"><h3 class="font-semibold text-gray-900 dark:text-white">Father <span class="font-italic">(Leave Blank if deceased)</span></h3></div>

                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">First Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Last Name</label>
                            </FloatLabel>
                            
                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Course</label>
                            </FloatLabel>

                            <div class="flex flex-row gap-2">
                                <FloatLabel variant="on" class="w-full">
                                    <InputText id="on_label" v-model="form.name" class="w-full" />
                                    <label for="on_label">Year & Section</label>
                                </FloatLabel>

                                <FloatLabel variant="on" class="w-full">
                                    <InputText id="on_label" v-model="form.name" class="w-full" />
                                    <label for="on_label">Student ID</label>
                                </FloatLabel>
                            </div>

                            <FloatLabel variant="on" class="w-full col-span-2">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Course</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">First Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <InputText id="on_label" v-model="form.name" class="w-full" />
                                <label for="on_label">Last Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full col-span-2">
                                <InputText id="on_label" v-model="value3" class="w-full" />
                                <label for="on_label">Course</label>
                            </FloatLabel>

                        </div> -->




                                <!-- <div class="relative z-0 w-full mb-5 group">
                                    <input type="text" name="floating_email" id="floating_email" v-model="form.name" readonly
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="floating_email"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Name</label>
                                </div>
                                <div class="relative z-0 w-full mb-5 group">
                                    <input type="email" name="floating_email" id="floating_email" v-model="form.email" readonly
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="floating_email"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email
                                        address</label>
                                </div>
                                <div class="grid md:grid-cols-2 md:gap-6">
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input type="text" name="floating_first_name" id="floating_first_name" v-model="form.first_name"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="floating_first_name"
                                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First
                                            name</label>
                                    </div>
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input type="text" name="floating_last_name" id="floating_last_name" v-model="form.last_name"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder="" />
                                        <label for="floating_last_name"
                                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last
                                            name</label>
                                    </div>
                                </div>
                                <div class="relative z-0 w-full mb-5 group">
                                    <input type="text" name="floating_password" id="floating_password" v-model="form.password"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="floating_password"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
                                </div>
                                <div class="relative z-0 w-full mb-5 group">
                                    <input type="text" name="repeat_password" id="floating_repeat_password" v-model="form.confirm_password"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="floating_repeat_password"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirm
                                        password</label>
                                </div>

                                <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Submit</button> -->
                        
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>



<!-- <div class="bg-gray-200 h-full space-y-2 flex flex-col items-center justify-start px-2 pt-2">
    <form class="max-w-md mx-auto" @submit.prevent="submit">
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="floating_email" id="floating_email" v-model="form.name" readonly
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " />
            <label for="floating_email"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Name</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="email" name="floating_email" id="floating_email" v-model="form.email" readonly
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " />
            <label for="floating_email"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email
                address</label>
        </div>
        <div class="grid md:grid-cols-2 md:gap-6">
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="floating_first_name" id="floating_first_name" v-model="form.first_name"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" " />
                <label for="floating_first_name"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First
                    name</label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="floating_last_name" id="floating_last_name" v-model="form.last_name"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder="" />
                <label for="floating_last_name"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last
                    name</label>
            </div>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="floating_password" id="floating_password" v-model="form.password"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " />
            <label for="floating_password"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="repeat_password" id="floating_repeat_password" v-model="form.confirm_password"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " />
            <label for="floating_repeat_password"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirm
                password</label>
        </div>

        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>

</div> -->
<script setup>
import { Head, Link, useForm, usePage} from '@inertiajs/vue3';
import { ref } from 'vue';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Calendar } from '@/Components/ui/calendar'

import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover'
import { cn } from '@/lib/utils'
import { DateFormatter, getLocalTimeZone,} from '@internationalized/date'
import { Calendar as CalendarIcon } from 'lucide-vue-next'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue,} from '@/Components/ui/select'
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'

const df = new DateFormatter('en-US', {
  dateStyle: 'long',
})


const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    first_name: '',
    last_name: '',
    password: '',
    confirm_password: '',
});

const submit = () => {
    form.post(route('student.verify-account.verifying'), {
        onFinish: () => form.reset(),
    });
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


//adding siblings
const formEntries = ref([
  { first_name: '', last_name: '', middle_name: '', age: '', occupation: '' }
]);

const addEntry = () => {
  formEntries.value.push({ first_name: '', last_name: '', middle_name: '', age: '', occupation: '' });
};

const removeEntry = (index) => {
  formEntries.value.splice(index, 1);
};

const organizations = ref([
  { name: '', membership_dates: '', position: '' } // Initial entry
]);

// Add a new organization entry
const addOrganization = () => {
  organizations.value.push({ name: '', membership_dates: '', position: '' });
};

// Remove an organization entry smoothly
const removeOrganization = (index) => {
  if (organizations.value.length > 1) {
    organizations.value.splice(index, 1);
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