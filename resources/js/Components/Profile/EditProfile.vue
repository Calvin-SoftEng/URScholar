<template>
    <div class="mx-auto w-7/12 sm:px-6 lg:px-8">
        <div class="relative w-full">
            <!-- Background Image -->
            <img src="../../../assets/images/profile_bg.jpg" alt="Profile Background" class="w-full h-48 object-cover rounded-t-lg">
            
            <!-- Profile Section -->
            <div class="absolute left-1/2 -bottom-14 transform -translate-x-1/2 w-[95%] bg-white/50 backdrop-blur-md border border-white/30 shadow-sm rounded-xl px-5 py-5 ">
                <div class="gap-4 flex flex-row items-center">
                    <!-- Profile Picture -->
                    <img src="" alt="Profile Picture" class="w-20 h-20 rounded-xl border-2 border-white shadow-sm">
                    
                    <!-- Name -->
                    <div class="flex flex-col">
                        <span class="text-lg font-semibold text-primary drop-shadow-md">Name</span>
                        <span class="text-base font-medium text-primary opacity-50">Coordinator</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative w-full flex justify-center mt-20 flex-col">
            <h2 class="text-xl font-semibold text-white text-center mb-4">Student Information</h2>

            <!-- Student ID -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">User ID</label>
                <input type="text" placeholder="Enter User ID" class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- First Name -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">First Name</label>
                <input type="text" placeholder="Enter First Name" class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Middle Name -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">Middle Name</label>
                <input type="text" placeholder="Enter Middle Name" class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">Last Name</label>
                <input type="text" placeholder="Enter Last Name" class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Campus -->
            <div class="mb-4">
                <label class="block text-white font-medium mb-1">Campus</label>
                <select class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Select Campus</option>
                    <option>Campus A</option>
                    <option>Campus B</option>
                    <option>Campus C</option>
                </select>
            </div>

            <!-- Age -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">Age</label>
                <input type="number" placeholder="Enter Age" class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">Role</label>
                <select class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Select Role</option>
                    <option>Student</option>
                    <option>Faculty</option>
                    <option>Admin</option>
                </select>
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">Address</label>
                <textarea placeholder="Enter Address" class="w-full h-[80px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

        </div>   

    </div>
</template>

<script setup>
import { defineProps, ref, watchEffect, onBeforeMount, reactive } from 'vue';
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue,} from '@/Components/ui/select'
</script>