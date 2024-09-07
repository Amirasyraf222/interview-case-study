<script setup>
import { Head } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Head title="ZALADA, ONE PIT STOP ONLINE SHOPPING" />

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 text-center">
            <!-- Main Title and Subtitle -->
            <div class="pt-8 sm:pt-0">
                <h1 class="text-6xl font-extrabold text-gray-900 dark:text-white">
                    ZALADA
                </h1>
                <h2 class="text-2xl font-medium text-gray-600 dark:text-gray-300 mt-4">
                    YOUR PREFERRED ONLINE SHOPPING PLATFORM
                </h2>
            </div>

            <!-- Authentication Links -->
            <div class="flex justify-center mt-8 space-x-4">
                <a href="/login" class="px-6 py-3 bg-blue-500 text-white rounded-lg shadow-lg hover:bg-blue-600 hover:shadow-xl transition-shadow duration-300">Login</a>
                <a href="/register" class="px-6 py-3 bg-green-500 text-white rounded-lg shadow-lg hover:bg-green-600 hover:shadow-xl transition-shadow duration-300">Register</a>
            </div>
        </div>
    </div>
</template>

<style>
body {
    margin: 0;
    font-family: 'Arial', sans-serif;
    background-color: #f7fafc;
}

h1 {
    font-family: 'Georgia', serif; /* You can use any exclusive font family here */
}

h2 {
    font-family: 'Verdana', sans-serif; /* Different font for the subtitle */
}
</style>
