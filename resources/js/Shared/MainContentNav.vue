<template>
    <nav class="flex">

      <!-- User Management -->
      <Link :href="route('admin.users.list')" v-show="managementType == 'user'" 
            class="inline-flex items-center px-3 py-2 ">
        <button
          class="px-3 py-2 font-medium transition-colors duration-200 rounded-lg"
          :class="[
            page === 'userlist'
              ? 'bg-blue-50 text-blue-600'
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Active
        </button>
      </Link>
      
      <Link :href="route('admin.users.request')" v-show="managementType == 'user'" 
            class="inline-flex items-center px-3 py-2 -mb-px">
        <button
          class="px-3 py-2 font-medium transition-colors duration-200 rounded-lg relative"
          :class="[
            page === 'request'
              ? 'bg-blue-50 text-blue-600'
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Request
          <span
            v-show="requestCount !== 0"
            class="absolute -top-2 -right-2 flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full"
          >
            {{ requestCount }}
          </span>
        </button>
      </Link>
  
      <Link v-show="showInactive && managementType == 'user'" 
            :href="route('admin.users.inactive')"
            class="inline-flex items-center px-3 py-2 ">
        <button
          class="px-3 py-2 font-medium transition-colors duration-200 rounded-lg"
          :class="[
            page === 'inactive'
              ? 'bg-blue-50 text-blue-600'
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Inactive
        </button>
      </Link>

      <!-- CMO Management -->
      <Link v-show="managementType == 'cmo'" :href="route('admin.cmo.list')"
        class="inline-flex items-center px-3 py-2 ">
        <button
          class="px-3 py-2 font-medium transition-colors duration-200 rounded-lg relative"
          :class="[
            page === 'published'
              ? 'bg-blue-50 text-blue-600'
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Published
        </button>
      </Link>

      <Link v-show="managementType == 'cmo'" :href="route('admin.cmo.draft')"
        class="inline-flex items-center px-3 py-2 ">
        <button
          class="px-3 py-2 font-medium transition-colors duration-200 rounded-lg relative"
          :class="[
            page === 'draft'
              ? 'bg-blue-50 text-blue-600'
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Draft
        </button>
      </Link>


      <!-- Discipline & Program Management -->
      <Link  v-show="managementType == 'program'" :href="route('admin.program.list')"
      class="inline-flex items-center px-3 py-2 ">
      <button
        class="px-3 py-2 font-medium transition-colors duration-200 rounded-lg relative"
        :class="[
          page === 'program'
            ? 'bg-blue-50 text-blue-600'
            : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
        ]"
      >
        Program
      </button>
      </Link>

      <Link v-show="managementType == 'program'" :href="route('admin.discipline.list')"
        class="inline-flex items-center px-3 py-2 ">
        <button
          class="px-3 py-2 font-medium transition-colors duration-200 rounded-lg relative"
          :class="[
            page === 'discipline'
              ? 'bg-blue-50 text-blue-600'
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Discipline
        </button>
      </Link>
    </nav>
  </template>

<script setup>

const props = defineProps([
    'requestCount',
    'showInactive',
    'page',
    'managementType',
])
</script>

