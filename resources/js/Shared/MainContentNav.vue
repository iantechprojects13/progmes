<template>
    <nav class="flex select-none">
      <Link :href="route(routeName)"
        class="inline-flex items-center px-3 py-2 ">
        <button
          class="px-3 py-2 font-medium transition-colors duration-200 rounded-lg relative"
          :class="[
            isActive
              ? 'bg-blue-100 text-blue-600'
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          {{ btnText }}
          <span
            v-show="requestCount != null && requestCount !== 0"
            class="absolute -top-2 -right-2 flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full"
          >
            {{ requestCount }}
            <span
              class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-500 opacity-75"
            ></span>
          </span>
        </button>
      </Link>
    </nav>
  </template>

<script setup>

const props = defineProps([
  'requestCount',
  'isActive',
  'btnText',
  'routeName',
])
</script>

