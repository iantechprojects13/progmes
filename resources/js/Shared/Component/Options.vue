<template>
  <div class="flex gap-2">
    <!-- Filter Button -->
    <button 
      @click="$emit('filter')"
      class="p-2.5 inline-flex items-center justify-center rounded-lg transition-all duration-200 tooltipForActions
             xl:text-gray-700 xl:hover:bg-gray-100 xl:border xl:border-gray-400
             bg-blue-600 text-white hover:bg-blue-700 xl:bg-white"
      data-tooltip="Filters"
    >
      <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
      </svg>
      <span class="hidden xl:inline-block ml-2 text-sm">Filters</span>
      <span class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 xl:hidden">
        Filters
      </span>
    </button>

    <!-- Refresh Button -->
    <Link :href="href">
      <button 
        class="p-2.5 inline-flex items-center justify-center rounded-lg transition-all duration-200 tooltipForActions
               xl:text-gray-700 xl:hover:bg-gray-100 xl:border xl:border-gray-400
               bg-blue-600 text-white hover:bg-blue-700 xl:bg-white"
        data-tooltip="Refresh page"
      >
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        <span class="hidden xl:inline-block ml-2 text-sm">Refresh</span>
        <span class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 xl:hidden">
          Refresh page
        </span>
      </button>
    </Link>
  </div>
</template>

<script>
export default {
  name: 'Options',
  props: {
    href: {
      type: String,
      default: '#'
    }
  },
  emits: ['filter']
}
</script>
