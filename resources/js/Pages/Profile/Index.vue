<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import axios from 'axios';

export default {
  components: { BreezeAuthenticatedLayout, Head },
  data() {
    return {
      csrf: window.csrf,
      form: {
        name: '',
        email: '',
        phone: '',
        address: ''
      },
      getProfileDatas: [],
      baseUrl: window.location.origin,
    };
  },
  mounted() {
    this.getProfileData();
  },
  methods: {
    // Fetch profile data and pre-fill the form
    getProfileData() {
      var url = route('api.user.profile.index');
      window.axios.get(url).then((response) => {
        let details = response.data.data.data;
        let profileData = details.DataArray[0]; // Assuming the first item is the user's data
        
        // Populate the form with existing data from API
        this.form.name = profileData.name || '';
        this.form.email = profileData.email || '';
        this.form.phone = profileData.phone || '';
        this.form.address = profileData.address || '';
        
        console.log(profileData);
      }).catch((error) => {
        console.error("Error fetching profile data:", error);
      });
    },

    // Update profile data
    updateProfile() {
      // FormData to send the data to the server
      let formData = new FormData();
      formData.append('name', this.form.name);
      formData.append('email', this.form.email);
      formData.append('phone', this.form.phone);
      formData.append('address', this.form.address);

      var url = route('api.user.profile.update');
      const config = {
        headers: {
          "content-type": "multipart/form-data",
        },
      };

      window.axios.post(url, formData, config).then((res) => {
        if (res.status == 200 || res.status != 200) {
          var redirectUrl = route('user.profile');
          window.location.href = redirectUrl;
        }
      });
    }
  }
};
</script>
<template>
    <Head title="Profile" />
  
    <BreezeAuthenticatedLayout>
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                  <!-- Display Profile -->
                  <div class="px-6 py-4">
                    <h2 class="text-gray-600 text-xl mb-4">Profile Details</h2>
  
                    <!-- Name -->
                    <div>
                      <label class="block font-medium text-sm text-gray-700">Name</label>
                      <input
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Enter your name"
                      />
                    </div>
  
                    <!-- Email -->
                    <div class="mt-4">
                      <label class="block font-medium text-sm text-gray-700">Email</label>
                      <input
                        v-model="form.email"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Enter your email"
                      />
                    </div>
  
                    <!-- Phone -->
                    <div class="mt-4">
                      <label class="block font-medium text-sm text-gray-700">Phone</label>
                      <input
                        v-model="form.phone"
                        type="tel"
                        class="mt-1 block w-full"
                        placeholder="Enter phone number"
                      />
                    </div>
  
                    <!-- Address -->
                    <div class="mt-4">
                      <label class="block font-medium text-sm text-gray-700">Address</label>
                      <input
                        v-model="form.address"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Enter address"
                      />
                    </div>
  
                    <!-- Action Buttons -->
                    <div class="flex justify-end mt-4">
                      <button @click="updateProfile" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                        Update Profile
                      </button>
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
  