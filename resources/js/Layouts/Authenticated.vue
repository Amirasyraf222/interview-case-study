<script setup>
import { ref, onMounted } from 'vue';
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import BreezeNavLink from '@/Components/NavLink.vue';
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/inertia-vue3';

const showingNavigationDropdown = ref(false);
const cartCount = ref(0);

// Fetch cart data on component mount
const fetchCart = () => {
    var url = route('api.cart.index');

    window.axios.get(url).then((response) => {
        let details = response.data.data.data;
        cartCount.value = details.count;
    });
};

// Call fetchCart when component is mounted
onMounted(() => {
    fetchCart();
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-black border-b border-gray-800 shadow-md">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center h-16 relative">
                    <!-- Left Side of Navigation -->
                    <div class="flex items-center flex-grow">
                        <div class="hidden space-x-8 sm:-my-px sm:ml-6 sm:flex">
                            <BreezeNavLink :href="route('product.index')" :active="route().current('product.index')" class="text-white hover:text-yellow-400">
                                Products
                            </BreezeNavLink>
                            <BreezeNavLink :href="route('order.index')" :active="route().current('order.index')" class="text-white hover:text-yellow-400">
                                Order History
                            </BreezeNavLink>
                        </div>
                    </div>

                    <!-- Center Title -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <h1 class="text-white text-2xl font-bold" style="font-family: 'Georgia', serif;">ZALADA</h1>
                    </div>

                    <!-- Right Side of Navigation -->
                    <div class="flex items-center flex-grow justify-end space-x-4">
                        <!-- Cart Link -->
                        <a :href="route('cart.index')" 
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-gray-700 rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                <svg class="w-6 h-6 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2.5l.5 2h12l.5-2H21M3 3h0L4 12H20L21 3H4.5M6 20a2 2 0 1 0 4 0 2 2 0 1 0-4 0zm10 0a2 2 0 1 0 4 0 2 2 0 1 0-4 0z"></path>
                                </svg>
                                <span class="inline-flex items-center justify-center w-4 h-4 text-xs font-semibold text-white bg-gray-600 rounded-full">
                                    {{ cartCount }}
                                </span>
                            </a>


                        <!-- User Menu -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="ml-3 relative">
                                <BreezeDropdown align="right" width="48">
                                    <template #trigger>
                                        <button type="button" class="inline-flex items-center p-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-600">
                                            <!-- User Icon -->
                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M12 12a4 4 0 100-8 4 4 0 000 8zm0 2c-3.313 0-6 2.687-6 6v1h12v-1c0-3.313-2.687-6-6-6z" clip-rule="evenodd"/>
                                            </svg>
                                            <svg class="ml-2 -mr-0.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <BreezeDropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </BreezeDropdownLink>
                                    </template>
                                </BreezeDropdown>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-800 focus:outline-none focus:bg-gray-700 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <BreezeResponsiveNavLink :href="route('product.index')" :active="route().current('product.index')" class="text-white hover:bg-gray-800">
                        Products
                    </BreezeResponsiveNavLink>
                    <BreezeResponsiveNavLink :href="route('order.index')" :active="route().current('order.index')" class="text-white hover:bg-gray-800">
                        Order History
                    </BreezeResponsiveNavLink>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-800">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-100">{{ $page.props.auth.user.name }}</div>
                        <div class="font-medium text-sm text-gray-400">{{ $page.props.auth.user.email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <BreezeResponsiveNavLink :href="route('logout')" method="post" as="button" class="text-red-400 hover:bg-red-800">
                            Log Out
                        </BreezeResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white shadow-md" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </div>
</template>

<style scoped>
/* Ensure the title is centered in the navigation bar */
nav .absolute {
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Add specific styles for the title */
h1 {
    font-family: 'Georgia', serif;
}
</style>
