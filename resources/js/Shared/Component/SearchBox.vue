<template>
  <div class="relative">
    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
      <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </div>
    
    <input
      @keydown.enter="$emit('submit')"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      type="search"
      id="content-search"
      placeholder="Search"
      class="w-full py-2 pl-11 pr-14 bg-white border border-gray-400 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 duration-200"
    />
    
    <button 
      @click="$emit('submit')"
      class="absolute inset-y-0 right-0 px-3 flex items-center bg-blue-500 hover:bg-blue-600 text-white rounded-r-lg transition duration-200"
      aria-label="Search"
    >
      <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </button>
  </div>
</template>

<script>
export default {
  name: 'SearchBox',
  props: {
    modelValue: {
      type: String,
      default: ''
    }
  },
  emits: ['update:modelValue', 'submit']
}
</script>