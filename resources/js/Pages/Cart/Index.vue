<template>
  <Head title="Order" />

  <BreezeAuthenticatedLayout>
    <div class="w-full mt-10 mb-10 px-0">
      <div class="flex shadow-md my-10 w-full">
        <!-- Cart Section -->
        <div class="w-3/4 bg-white px-10 py-10">
          <div class="flex justify-between border-b pb-8">
            <h1 class="font-semibold text-2xl">Shopping Cart</h1>
          </div>

          <!-- Check if there are items in the cart -->
          <div v-if="carts.length === 0" class="mt-10 text-center">
            <h2 class="text-gray-600 text-xl">You have no items in your cart</h2>
          </div>

          <!-- If there are items, show cart details -->
          <div v-else>
            <div class="flex mt-10 mb-5 border-b pb-2">
              <h3 class="font-semibold text-gray-600 text-xs uppercase w-1/5">
                <input type="checkbox" v-model="selectAll" @change="toggleSelectAll" class="mr-2">All
              </h3>
              <h3 class="font-semibold text-gray-600 text-xs uppercase w-1/5 text-center">Product Details</h3>
              <h3 class="font-semibold text-gray-600 text-xs uppercase w-1/5 text-center">Quantity</h3>
              <h3 class="font-semibold text-gray-600 text-xs uppercase w-1/5 text-center">Price</h3>
              <h3 class="font-semibold text-gray-600 text-xs uppercase w-1/5 text-center">Total</h3>
            </div>

            <!-- Cart Items -->
            <div v-for="cart in carts" :key="cart.id" class="flex items-center hover:bg-gray-100 -mx-8 px-6 py-5 border-b">
              <!-- Product Image and Details -->
              <div class="mr-7">
                <input type="checkbox" :value="cart.id" v-model="selectedItems" class="mr-7">
              </div>
              <div class="w-24 text-center">
                <img 
                  class="h-24 cursor-pointer shadow-lg transition-transform transform hover:scale-105" 
                  :src="cart.product.imagePath" 
                  alt="" 
                  @click="openModal(cart.product.imagePath)"
                >
              </div>
              <div class="flex flex-col justify-between ml-4 flex-grow text-center">
                <span class="font-bold text-sm">{{ cart.product.name }}</span>
              </div>

              <div class="w-1/5 text-center">
                <span class="w-1/5 text-center font-semibold text-sm">{{ cart.quantity }}</span>
              </div>

              <span class="w-1/5 text-center font-semibold text-sm">RM{{ cart.product.price }}</span>

              <span class="w-1/5 text-center font-semibold text-sm">RM{{ formatPrice(cart.product.price * cart.quantity) }}</span>
            </div>
          </div>
        </div>

        <!-- Summary Section -->
        <div id="summary" class="w-1/4 px-8 py-10 bg-white shadow-md" v-if="carts.length > 0">
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
            <button @click="checkoutSelected" class="bg-gray-800 text-white font-semibold py-3 w-full rounded-lg shadow-md hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 mb-2">Checkout Selected</button>
            <button @click="deleteSelected" class="bg-red-600 text-white font-semibold py-3 w-full rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Delete Selected</button>
            <a :href="route('product.index')" class="flex items-center font-semibold text-indigo-600 text-sm mt-5 hover:text-indigo-800 transition-colors duration-300">
              <i class="fas fa-arrow-left mr-2 text-indigo-600 text-lg"></i>
              Back to Shop
            </a>
          </div>
        </div>
      </div>

      <!-- Image Modal -->
      <div 
        v-if="isModalOpen" 
        class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50"
        @click.self="closeModal"
      >
        <div class="relative bg-white p-4 rounded">
          <button 
            @click="closeModal" 
            class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-2xl"
          >
            &times;
          </button>
          <img :src="modalImageSrc" alt="" class="max-w-full max-h-screen">
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
      selectedItems: [], 
      isModalOpen: false,
      modalImageSrc: ''
    };
  },
  computed: {
    selectedTotalAmount() {
      return this.carts
        .filter(cart => this.selectedItems.includes(cart.id))
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
      Promise.all(this.selectedItems.map(cartId => {
        var url = route('api.cart.destroy', cartId);
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
      form.append("cart_ids", JSON.stringify(this.selectedItems)); 

      var url = route("api.order.checkout");
      const config = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };

      window.axios.post(url, form, config).then((res) => {
        if (res.status == 200 || res.status != 200) {
          this.successMessage = res.data.data.message;
          window.location.href = this.baseUrl + "/order/payment";
        }
      });
    },
    toggleSelectAll(event) {
      this.selectedItems = event.target.checked ? this.carts.map(cart => cart.id) : [];
    },
    formatPrice(amount) {
      return amount.toFixed(2); 
    },
    openModal(imageSrc) {
      this.modalImageSrc = imageSrc;
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
    }
  }
}
</script>
