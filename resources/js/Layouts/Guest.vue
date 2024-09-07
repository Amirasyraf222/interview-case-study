<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <Link href="/">
                <h1 class="text-6xl font-extrabold text-gray-900 dark:text-white">
                    ZALADA
                </h1>            
            </Link>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
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
