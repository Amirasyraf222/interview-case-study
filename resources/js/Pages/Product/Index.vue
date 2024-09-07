<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';

export default {
    components: { BreezeAuthenticatedLayout, Head },
    data() {
        return {
            csrf: window.csrf,
            elements: [],
            carts: [],
            cartCount: 0,
            baseUrl: window.location.origin,
            brands: [],
            categories: [],
            selectedCategory: null,
            selectedBrand: null,
        };
    },
    computed: {
        rows() {
            return this.totalRows;
        },
    },
    mounted() {
        this.element();
        this.cart();
        this.brand();
        this.category();
    },
    methods: {
        element() {
            var url = route('api.product.index') + '?brand=' + this.selectedBrand + '&category=' + this.selectedCategory;
            window.axios.get(url).then((response) => {
                let details = response.data.data.data;
                this.elements = details.DataArray;
            });
        },
        cart() {
            var url = route('api.cart.index');
            window.axios.get(url).then((response) => {
                let details = response.data.data.data;
                this.carts = details.DataArray;
                this.cartCount = details.count;
            });
        },
        brand() {
            var url = route('api.product.brand');
            window.axios.get(url).then((response) => {
                let details = response.data.data.data;
                this.brands = details.DataArray;
            });
        },
        category() {
            var url = route('api.product.category');
            window.axios.get(url).then((response) => {
                let details = response.data.data.data;
                this.categories = details.DataArray;
            });
        },
        filter() {
            this.element();
        },
        clearFilter() {
            this.selectedCategory = null;
            this.element();
        },
    }
}
</script>

<template>
    <Head title="Product" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Products

                <a :href="route('cart.index')" class="float-right inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
          <!-- Cart Icon -->
          <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2.5l.5 2h12l.5-2H21M3 3h0L4 12H20L21 3H4.5M6 20a2 2 0 1 0 4 0 2 2 0 1 0-4 0zm10 0a2 2 0 1 0 4 0 2 2 0 1 0-4 0z"></path>
          </svg>

          <span class="inline-flex items-center justify-center w-4 h-4 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full">
            {{ cartCount }}
          </span>
        </a>

            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="lg:flex lg:items-center lg:justify-between">
                    <div class="min-w-0 flex-1">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Lists</h2>
                    </div>
                    <div class="mt-5 flex lg:ml-4 lg:mt-0 space-x-4">
                        <button
                            @click="clearFilter()"
                            :class="['px-4 py-2 rounded-md text-sm font-semibold', selectedCategory === null ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 border border-gray-300 hover:bg-gray-50']"
                        >
                            All
                        </button>
                        <button
                            v-for="category in categories"
                            :key="category.id"
                            :class="['px-4 py-2 rounded-md text-sm font-semibold', selectedCategory === category.id ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 border border-gray-300 hover:bg-gray-50']"
                            @click="selectedCategory = selectedCategory === category.id ? null : category.id; filter()"
                        >
                            {{ category.name }}
                        </button>
                    </div>
                </div>

                <!-- Card Grid -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <div v-for="element in elements" :key="element.id" class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <img :src="element.imagePath" alt="Product Image" class="w-full h-48 object-cover object-center">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <a :href="route('product.show', element.id)">
                                    {{ element.name}}
                                </a>
                            </h3>
                            <p class="text-gray-700 mt-2">Brand: {{ element.brand }}</p>
                            <p class="text-gray-700 mt-1">Description: {{ element.description }}</p>
                            <p class="text-gray-900 mt-2 font-bold">RM{{ element.price }}</p>
                        </div>
                        <div class="p-4 border-t border-gray-200">
                            <a :href="route('product.show', element.id)" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style scoped>
</style>
