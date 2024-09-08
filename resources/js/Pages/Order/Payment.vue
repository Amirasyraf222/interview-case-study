<script>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
    import { Head } from '@inertiajs/inertia-vue3';

    export default {
        components: { BreezeAuthenticatedLayout, Head },
        data() {
          return {
            csrf: window.csrf,
            elements: [],
            selectedOrders: [], // Stores selected order IDs
            selectAll: false, // Controls the select all checkbox
          };
      },
      mounted() {
        this.element();
      },
      methods: {
        element() {
          var url = route('api.order.pending');
         
          window.axios.get(url).then((response) => {
            let details = response.data.data.data;
            this.elements = details.DataArray;
          });
        },
        toggleSelectAll() {
          if (this.selectAll) {
            // Select all orders
            this.selectedOrders = this.elements.map(order => order.orderId);
          } else {
            // Deselect all orders
            this.selectedOrders = [];
          }
        },
        paySelected() {
          if (this.selectedOrders.length === 0) {
            alert('Please select at least one order to pay.');
            return;
          } 

          let form = new FormData();
          form.append("_method", "post");    
          form.append("order_ids", JSON.stringify(this.selectedOrders)); 

          var url = route("api.order.payment");
          const config = {
            headers: {
              "content-type": "multipart/form-data",
            },
          };

          window.axios.post(url, form, config).then((res) => {
            if (res.status == 200 || res.status != 200) {
              this.successMessage = res.data.data.message;
              var url = route('order.index');
              window.location.href = url;
            }
          });
        }
      },
      watch: {
        selectedOrders() {
          // Sync the state of the selectAll checkbox based on individual selections
          this.selectAll = this.selectedOrders.length === this.elements.length;
        }
      }
    }
</script>

<template>
    <Head title="Order Details" />

    <BreezeAuthenticatedLayout>
      <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
          
          <!-- Title Centered -->
          <div class="text-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700">Order Information</h2>
          </div>

          <!-- Single Card/Container -->
          <div class="bg-white shadow-md rounded-lg p-6">
            
            <!-- Select All Checkbox -->
            <div class="mb-4">
              <input 
                type="checkbox" 
                v-model="selectAll" 
                @change="toggleSelectAll"
                class="mr-2">
              <label for="selectAll" class="text-lg font-semibold text-gray-700">Select All</label>
            </div>
            <hr v-if="index !== elements.length - 1" class="my-4">

            <div v-for="(element, index) in elements" :key="index" class="mb-4">
            <p class="text-gray-600"><strong>Customer Name:</strong> {{ element.custName }}</p>
                  <p class="text-gray-600"><strong>Customer Phone:</strong> {{ element.custPhone }}</p>
                  <p class="text-gray-600"><strong>Customer Address:</strong> {{ element.custAddress }}</p>
            </div>
            <hr v-if="index !== elements.length - 1" class="my-4">


            <!-- Loop through Orders, separated by horizontal bar -->
            <div v-for="(element, index) in elements" :key="index">
              <div class="flex items-center">
                <input 
                  type="checkbox" 
                  :value="element.orderId" 
                  v-model="selectedOrders"
                  class="mr-4">
                
                <div>
                  <p class="text-gray-600"><strong>Order ID:</strong> {{ element.orderId }}</p>
                  <p class="text-gray-600"><strong>Total Amount:</strong> RM{{ element.totalAmount }}</p>
                  <p class="text-gray-600"><strong>Order Status:</strong> {{ element.orderStatus }}</p>
                </div>
              </div>

              <!-- Items Ordered -->
              <div class="mt-2 mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Items Ordered</h3>
                <ul class="list-disc list-inside text-gray-600">
                  <li v-for="(product, idx) in element.product" :key="idx">{{ product }}</li>
                </ul>
              </div>

              <!-- Horizontal bar to separate orders -->
              <hr v-if="index !== elements.length - 1" class="my-4">
            </div>

          </div>

          <!-- Pay Now Button for Selected Orders -->
          <div class="flex flex-col items-center mt-8 space-y-4">
  <!-- Pay Selected Orders Button -->
            <button @click="paySelected"
                    class="bg-gray-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600 focus:outline-none">
                Pay Selected Orders
            </button>

            <!-- Back to Shop Link -->
            <a :href="route('product.index')" class="flex items-center font-semibold text-indigo-600 text-sm hover:text-indigo-800 transition-colors duration-300">
                <i class="fas fa-arrow-left mr-2 text-indigo-600 text-lg"></i>
                Back to Shop
            </a>
            </div>

        </div>
      </div>
    </BreezeAuthenticatedLayout>
</template>
