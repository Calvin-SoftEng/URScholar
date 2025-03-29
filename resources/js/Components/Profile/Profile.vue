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
            <h2 class="text-xl font-semibold text-white text-center mb-4">Profile Information</h2>

            <!-- Student ID -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">User ID</label>
                <span>User ID</span>
            </div>

            <!-- First Name -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">First Name</label>
                <span>User ID</span>
            </div>

            <!-- Middle Name -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">Middle Name</label>
                <span>User ID</span>
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">Last Name</label>
                <span>User ID</span>
            </div>

            <!-- Campus -->
            <div class="mb-4">
                <label class="block text-white font-medium mb-1">Campus</label>
                <span>User ID</span>
            </div>

            <!-- Age -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">Age</label>
                <span>User ID</span>
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">Role</label>
                <span>User ID</span>
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label class="block text-primary font-medium mb-1">Address</label>
                <span>User ID</span>
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