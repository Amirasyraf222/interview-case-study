<template>
  <Head title="Product Information" />

  <BreezeAuthenticatedLayout>
    <div class="py-12">
      <div class="bg-white">
        <div class="pt-6">
          <nav aria-label="Breadcrumb">
            <ol role="list" class="mx-auto flex max-w-2xl items-center space-x-2 px-4 sm:px-6 lg:max-w-7xl lg:px-8">
              <li>
                <div class="flex items-center">
                  <a href="#" class="mr-2 text-sm font-medium text-gray-900">{{ elements.category }}</a>
                  <svg width="16" height="20" viewBox="0 0 16 20" fill="currentColor" aria-hidden="true" class="h-5 w-4 text-gray-300">
                    <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
                  </svg>
                </div>
              </li>

              <li class="text-sm">
                <a href="#" aria-current="page" class="font-medium text-gray-500 hover:text-gray-600">{{ elements.name }}</a>
              </li>
            </ol>
          </nav>

          <div class="mx-auto max-w-2xl px-4 pb-16 pt-10 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:grid-rows-[auto,auto,1fr] lg:gap-x-8 lg:px-8 lg:pb-24 lg:pt-16">
            
            <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
              <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ elements.name }}</h1>
            </div>

            <!-- Quantity and Price Section -->
            <div class="mt-4 lg:row-span-3 lg:mt-0">
              <h2 class="sr-only">Product information</h2>
              <p class="text-3xl tracking-tight text-gray-900">RM{{ totalPrice }}</p>

              <form class="mt-10" @submit.prevent="addToCart" enctype="multipart/form-data">
                <div>
                  <h3 class="text-sm font-medium text-gray-900">Brand</h3>
                  <span class="inline-flex items-center rounded-md bg-blue-50 my-2 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">{{ elements.brand }}</span>
                </div>

                <div>
                  <h3 class="text-sm font-medium text-gray-900 mt-2">Quantity</h3>
                  <div class="flex items-center">
                    <button type="button" @click="decrementQuantity" class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-l-md text-gray-700 hover:bg-gray-300">-</button>
                    <span class="px-4 py-2 border-t border-b border-gray-300 text-gray-700">{{ quantity }}</span>
                    <button type="button" @click="incrementQuantity" class="px-4 py-2 bg-gray-200 border border-gray-300 rounded-r-md text-gray-700 hover:bg-gray-300">+</button>
                  </div>
                </div>

                <button type="submit" class="mt-2 flex w-full items-center justify-center rounded-md border border-transparent bg-gray-800 px-8 py-3 text-base font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">Add to cart</button>
                <a :href="route('product.index')" class="flex items-center font-semibold text-indigo-600 text-sm mt-10 hover:text-indigo-800 transition-colors duration-300">
                  <i class="fas fa-arrow-left mr-2 text-indigo-600 text-lg"></i>
                  Back to Shop
                </a>
              </form>
            </div>

            <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pb-16 lg:pr-8 lg:pt-6">
              <div>
                <h3 class="sr-only">Description</h3>
                <div class="space-y-6">
                  <p class="text-base text-gray-900">{{ elements.description }}</p>
                  <div class="lg:col-span-1 lg:border-r lg:border-gray-200 lg:pr-8">
                    <img :src="elements.imagePath" alt="Product Image" class="w-1/2 h-auto object-cover object-center rounded-lg">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
 
  </BreezeAuthenticatedLayout>
</template>

<script>
  import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
  import { Head } from '@inertiajs/inertia-vue3';

  export default {
      components: { BreezeAuthenticatedLayout, Head },
      props: {
        id: {
          type: String,
          default: "",                                      
        },
      },
      data() {
        return {
          csrf: window.csrf,
          elements: {},
          carts: [],
          cartCount: 0,
          baseUrl: window.location.origin,
          quantity: 1,
          errors: null,
          successMessage: null,
        };
      },
      computed: {
        totalPrice() {
          return (this.quantity * this.elements.price).toFixed(2);
        },
      },
      mounted() {
        this.fetchProduct();
        this.fetchCart();
      },
      methods: {
        fetchProduct() {
          var url = route('api.product.show', this.id);
          window.axios.get(url).then((response) => {
            let details = response.data.data.data;
            this.elements = details.DataArray;
          });
        },
        fetchCart() {
          var url = route('api.cart.index');
          window.axios.get(url).then((response) => {
            let details = response.data.data.data;
            this.carts = details.DataArray;
            this.cartCount = details.count;
          });
        },
        incrementQuantity() {
          this.quantity += 1;
        },
        decrementQuantity() {
          if (this.quantity > 1) {
            this.quantity -= 1;
          }
        },
        addToCart() {
          let form = new FormData();
          form.append("_method", "post");
          form.append("id", this.elements.id);
          form.append("quantity", this.quantity);

          var url = route("api.product.store");
          const config = {
            headers: {
              "content-type": "multipart/form-data",
            },
          };

          window.axios
            .post(url, form, config)
            .then((res) => {
              if (res.status === 200) {
                this.successMessage = res.data.data.message;
                location.reload();
              }
            });
        },
      },
  }
</script>

<style scoped>
  /* Enhanced styling for a modern look */
  .bg-gray-800 {
    background-color: #2d3748; /* Dark gray background */
  }
  .hover\:bg-gray-900:hover {
    background-color: #1a202c; /* Darker gray on hover */
  }
  .focus\:ring-gray-500:focus {
    box-shadow: 0 0 0 2px rgba(209, 213, 219, 0.5); /* Gray focus ring */
  }
</style>
