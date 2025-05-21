<template>
    <Head title="401 Unauthorized Access" />
  
    <div class="w-full h-screen flex flex-col justify-center items-center bg-gray-50">
      <div class="md:shadow-lg md:shadow-gray-300 p-8 md:border md:border-gray-200 max-w-lg md:rounded-lg bg-white">
        <div class="flex flex-col md:flex-row items-center">
          <div class="mr-6 text-7xl text-red-500">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
          <div class="border-md text-center md:text-start mt-4 md:mt-0">
            <h1 class="font-bold text-2xl text-gray-800">401 Unauthorized Access</h1>
            <p class="text-base text-gray-600 mt-2">
              Sorry, but you don't have the necessary permissions to access this resource.
            </p>
          </div>
        </div>
        
        <div class="mt-8 w-full flex justify-center">
          <button 
            @click="goBack" 
            class="px-6 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-md transition duration-200 flex items-center"
          >
            <i class="fas fa-arrow-left mr-2"></i>
            Back
          </button>
        </div>
      </div>
    </div>
  </template>

<script setup>
import Layout from '@/Shared/Layout.vue';
defineOptions({ layout: Layout });

function goBack() {
  window.history.back();
}

</script>