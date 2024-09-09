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

      },
      methods: {
        element() {
            
          var url = route('api.order.history');
         
          window.axios.get(url).then((response) => {
            let details = response.data.data.data;
            this.elements = details.DataArray;
          });
        },
        payNow(orderId) {
          var url = route('order.payment');
          window.location.href = url;
        },
        cart() {
            var url = route('api.cart.index');

            window.axios.get(url).then((response) => {
                let details = response.data.data.data;
                this.carts = details.DataArray;
                this.cartCount = details.count;
            });
        },
      }
    }
</script>

<template>
  <Head title="Order" />

  <BreezeAuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col">
          <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
              <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <!-- Check if there are any elements -->
                <div v-if="elements.length === 0" class="text-center py-8">
                  <h2 class="text-gray-600 text-xl">You don't have any order history</h2>
                </div>

                <!-- If there are orders, display the table -->
                <div v-else>
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          No
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Order Id
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Total Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Order Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Item(s)
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Action
                        </th>
                      </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="(element, index) in elements" :key="index">
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-500">{{ index + 1 }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-500">{{ element.orderId }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-500">RM{{ element.totalAmount }}</div>
                        </td>
                        <td 
                          class="px-6 py-4 whitespace-nowrap text-sm"
                          :class="{
                            'text-green-500': element.orderStatus === 'Payment Confirmed',
                            'text-orange-500': element.orderStatus === 'Pending Payment',
                            'text-red-500': element.orderStatus !== 'Payment Confirmed' && element.orderStatus !== 'Pending Payment'
                          }"
                        >
                          {{ element.orderStatus }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          <ul>
                            <li v-for="(product, idx) in element.product" :key="idx">- {{ product }}</li>
                          </ul>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          <ul>
                            <li v-if="element.orderStatus === 'Pending Payment'">
                              <button 
                                @click="payNow(element.orderId)" 
                                class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600 focus:outline-none">
                                Pay Now
                              </button>
                            </li>
                            <li v-else>
                              <div class="text-sm text-green-500">Paid</div>
                            </li>
                          </ul>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>
