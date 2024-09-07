<template>
  <Head title="Order" />

  <BreezeAuthenticatedLayout>
      <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Cart
          </h2>
      </template>

      <div class="container mx-auto mt-10 mb-10">
          <div class="flex shadow-md my-10">
            <div class="w-3/4 bg-white px-10 py-10">
              <div class="flex justify-between border-b pb-8">
                <h1 class="font-semibold text-2xl">Shopping Cart</h1>
              </div>
              <div class="flex mt-10 mb-5">
                <h3 class="font-semibold text-gray-600 text-xs uppercase w-1/5">
                  <input type="checkbox" v-model="selectAll" @change="toggleSelectAll"> Select All
                </h3>
                <h3 class="font-semibold text-gray-600 text-xs uppercase w-2/5">Product Details</h3>
                <h3 class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5 text-center">Quantity</h3>
                <h3 class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5 text-center">Price</h3>
                <h3 class="font-semibold text-center text-gray-600 text-xs uppercase w-1/5 text-center">Total</h3>
              </div>

              <div v-for="cart in carts" :key="cart.id" class="flex items-center hover:bg-gray-100 -mx-8 px-6 py-5">
                <div class="flex w-1/5">
                  <input type="checkbox" :value="cart.product.id" v-model="selectedItems" class="mr-2">
                  <div class="w-20">
                    <img class="h-24" :src="cart.product.imagePath" alt="">
                  </div>
                  <div class="flex flex-col justify-between ml-4 flex-grow">
                    <span class="font-bold text-sm">{{ cart.product.name }}</span>
                    <span class="text-red-500 text-xs">{{ cart.brand.name }}</span>
                    <a v-on:click="removeItem(cart.id)" class="font-semibold hover:text-red-500 text-gray-500 text-xs">Remove</a>
                  </div>
                </div>
                <div class="flex justify-center w-1/5">
                  <input class="mx-2 border text-center w-12" type="text" :value="cart.quantity">
                </div>
                <span class="text-center w-1/5 font-semibold text-sm">RM{{ cart.product.price }}</span>
                <span class="text-center w-1/5 font-semibold text-sm">RM{{ cart.product.price * cart.quantity }}</span>
              </div>
            </div>

            <div id="summary" class="w-1/4 px-8 py-10">
              <h1 class="font-semibold text-2xl border-b pb-8">Order Summary</h1>
              <div class="flex justify-between mt-10 mb-5">
                <span class="font-semibold text-sm uppercase">Total Item = </span>
                <span class="font-semibold text-sm">{{ selectedItems.length }}</span>
              </div>

              <div class="border-t mt-8">
                <div class="flex font-semibold justify-between py-6 text-sm uppercase">
                  <span>Total Price = </span>
                  <span>RM{{ selectedTotalAmount }}</span>
                </div>
                <button @click="deleteSelected" class="bg-red-600 text-white font-semibold py-3 w-full rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Delete Selected</button>
                <button @click="checkoutSelected" class="bg-indigo-600 text-white font-semibold py-3 w-full rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 mt-2">Checkout Selected</button>
                <a :href="route('product.index')" class="flex items-center font-semibold text-indigo-600 text-sm mt-10 hover:text-indigo-800 transition-colors duration-300">
                  <i class="fas fa-arrow-left mr-2 text-indigo-600 text-lg"></i>
                  Back to Shop
                </a>
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
    elements: [],
    carts: [],
    cartCount: 0,
    totalAmount: 0,
    baseUrl: window.location.origin,
    errors: null,
    successMessage: null,
    selectAll: false,
    selectedItems: [], // This will hold the selected product IDs
  };
},
computed: {
  selectedTotalAmount() {
    return this.carts
      .filter(cart => this.selectedItems.includes(cart.product.id))
      .reduce((total, cart) => total + (cart.product.price * cart.quantity), 0);
  },
},
mounted() {
  this.cart();
},
methods: {
  cart() {
    var url = route('api.cart.index');

    window.axios.get(url).then((response) => {
      let details = response.data.data.data;
      this.carts = details.DataArray;
      this.cartCount = details.count;

      let total = this.carts.reduce((acc, cart) => acc + (cart.quantity * cart.product.price), 0);
      this.totalAmount = total;
    });
  },
  removeItem(itemId) {
    var url = route('api.cart.destroy', itemId);

    window.axios.get(url).then((response) => {
      if (response.status == 200) {
        this.cart();
      }
    });
  },
  deleteSelected() {
    if (this.selectedItems.length === 0) {
      alert('No items selected!');
      return;
    }
    Promise.all(this.selectedItems.map(productId => {
      var url = route('api.cart.destroy', productId);
      return window.axios.get(url);
    })).then(() => {
      this.cart();
      this.selectedItems = [];
    }).catch(error => {
      console.error('Error deleting selected items:', error);
    });
  },
  checkoutSelected() {
    if (this.selectedItems.length === 0) {
      alert('No items selected!');
      return;
    }

    let form = new FormData();
    form.append("_method", "post");
    form.append("id", this.elements.id);
    form.append("total_amount", this.selectedTotalAmount);
    form.append("product_ids", JSON.stringify(this.selectedItems)); // Append selected product IDs

    var url = route("api.order.checkout");
    const config = {
      headers: {
        "content-type": "multipart/form-data",
      },
    };

    window.axios
      .post(url, form, config)
      .then((res) => {
        if (res.status == 200 || res.status != 200 ) {
          this.successMessage = res.data.data.message;
          window.location.href = this.baseUrl + "/order/history";
        }
      });
  },
  toggleSelectAll(event) {
    this.selectedItems = event.target.checked ? this.carts.map(cart => cart.product.id) : [];
  }
}
}
</script>
