<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout class="shadow-md z-10">
        <div class="w-full bg-dirtywhite shadow-sm ">
            <h1 class="text-3xl font-bold font-sora text-left p-3 mx-10">My Profile</h1>
        </div>
        <div class="pt-3 pb-24 overflow-auto h-full scroll-py-2">
            <div class="mx-auto w-7/12 sm:px-6 lg:px-8 ">
                <div class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                        <!-- pic -->
                        <div class="border w-80 h-80 rounded-lg">
                            <img src="" alt="">
                        </div>
                        <!-- info -->
                        <div class="w-full h-1/12 flex flex-col items-left gap-1 pb-4 border-b-2">
                            <span class="text-gray-500 text-sm">Permanent Address</span>
                            <span class="text-gray-900 text-base font-semibold leading-tight"></span>
                            <div class="w-full flex flex-row gap-2 py-2">
                                <div class="w-[40%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Age</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{student.age}}</span>
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Date of Birth</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{student.placebirth}}</span>
                                </div>
                            </div>
                            <div class="w-full flex flex-row gap-2 py-2">
                                <div class="w-[40%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Civil Status</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{student.civil}}</span>
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Place of Birth</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{student.placebirth}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-full h-1/12 flex flex-col items-left gap-2 pb-4 border-b-2">
                            <div class="w-full flex flex-row gap-2">
                                <div class="w-[40%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Gender</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{student.gender}}</span>
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Religion</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{student.religion}}</span>
                                </div>
                            </div>
                        </div>
                        <!-- gmail -->
                        <div class="w-full h-1/12 flex items-center gap-2 p-1 pb-4 border-b-2">
                            <span class="p-2 bg-primary rounded-md text-2xl text-white font-albert font-bold">@</span>
                            <span class="pl-2 text-gray-900 text-base font-bold">{{ $page.props.auth.user.email }}</span>
                        </div>
                        <!-- qr -->
                        <div class="w-full h-1/12 bg-white shadow-lg rounded-lg flex flex-col flex-grow items-center justify-center gap-2 p-3">
                            <img src="../../../../assets/images/qrcodesample.png" alt="" class="w-20 h-20">
                            <span class="pl-2">Download your QR Code</span>
                        </div>
                    </div>
                    <div class="w-full h-full col-span-2 block flex-col items-center mx-auto max-w-8xl space-y-3">
                        <div class="w-full h-1/12">
                            <span class="font-italic font-sora text-3xl font-bold uppercase">Pangalan</span>
                        </div>

                        <div class="w-full h-1/12 bg-white shadow-md rounded-lg flex flex-col items-center space-y-2 gap-2 py-5 px-10">
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'graduation-cap']" class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">{{student.last_name}}, {{ student.first_name }}</span>
                            </div>
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'id-card-clip']" class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">Lagay mo dito</span>
                            </div>
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'school']" class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">Lagay mo dito</span>
                            </div>
                        </div>

                        <!-- education -->
                        <div class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-3 gap-2 py-5 px-10">
                            <h1 class="text-base">Education</h1>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Elementary
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{elementary.name }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{elementary.years}}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Junior High School
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{junior.name}}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{junior.years}}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Senior High School
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{senior.name}}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{senior.years}}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    College
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{college.name}}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{college.years}}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Vocational
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{vocational.name}}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{vocational.years}}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Post Graduate
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{postgrad.name}}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{postgrad.years}}</span>
                                </div>
                            </div>
                        </div>

                        <!-- family -->
                        <div class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-3 gap-2 py-5 px-10">
                            <h1 class="cols-span-2 text-base">Family</h1>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'person-dress']" class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="flex flex-col items-left gap-1">
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{mother.first_name}}</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{mother.occupation}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-col items-left ">
                                            <span class="text-gray-500 text-sm font-semibold leading-tight">Monthly Family Income</span>
                                            <span class="text-gray-900 text-3xl font-semibold leading-tight">100,000</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'person']" class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="flex flex-col items-left gap-1">
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{father.first_name}}</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{father.occupation}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-col items-left gap-1 py-1">
                                        <span class="text-gray-500 text-base font-semibold leading-tight">Family Housing Type</span>
                                        <span class="text-gray-900 text-lg font-semibold leading-tight">{{family.family_housing}}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'people-roof']" class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="flex flex-col items-left gap-1">
                                            <span class="text-gray-900 text-base font-semibold leading-tight">Kapatid1, Kapatid2,</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">Trabaho1, Trabaho2</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-col items-left gap-1 py-1">
                                        <span class="text-gray-500 text-base font-semibold leading-tight">Other Sources of Income</span>
                                        <span class="text-gray-900 text-lg font-semibold leading-tight">{{family.other_income}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
    student: Object,
    education: Object,
    family: Object,
});

const elementary = computed(() => {
  try {
    return JSON.parse(props.education.elementary);
  } catch (error) {
    console.error("Invalid JSON format", error);
    return {}; // Return empty object if parsing fails
  }
});

const junior = computed(() => {
  try {
    return JSON.parse(props.education.junior);
  } catch (error) {
    console.error("Invalid JSON format", error);
    return {}; // Return empty object if parsing fails
  }
});

const senior = computed(() => {
  try {
    return JSON.parse(props.education.senior);
  } catch (error) {
    console.error("Invalid JSON format", error);
    return {}; // Return empty object if parsing fails
  }
});

const college = computed(() => {
  try {
    return JSON.parse(props.education.college);
  } catch (error) {
    console.error("Invalid JSON format", error);
    return {}; // Return empty object if parsing fails
  }
});

const vocational = computed(() => {
  try {
    return JSON.parse(props.education.vocational);
  } catch (error) {
    console.error("Invalid JSON format", error);
    return {}; // Return empty object if parsing fails
  }
});

const postgrad = computed(() => {
  try {
    return JSON.parse(props.education.postgrad);
  } catch (error) {
    console.error("Invalid JSON format", error);
    return {}; // Return empty object if parsing fails
  }
});

const mother = computed(() => {
  try {
    return JSON.parse(props.family.mother);
  } catch (error) {
    console.error("Invalid JSON format", error);
    return {}; // Return empty object if parsing fails
  }
});

const father = computed(() => {
  try {
    return JSON.parse(props.family.father);
  } catch (error) {
    console.error("Invalid JSON format", error);
    return {}; // Return empty object if parsing fails
  }
});
</script>
