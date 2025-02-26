<template>
    <div class="flex flex-col gap-4">
      <!-- Content only - no toolbar needed for readonly -->
      <div 
        class="prose max-w-none p-4 min-h-[200px] border border-gray-200 rounded-lg bg-white"
        v-html="sanitizedContent"
      />
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue'
  import DOMPurify from 'dompurify'
  
  const props = defineProps({
    content: {
      type: String,
      default: ''
    }
  })
  
  const sanitizedContent = computed(() => {
    DOMPurify.setConfig({
      ADD_ATTR: ['target']
    })
    return DOMPurify.sanitize(props.content, {
      ADD_TAGS: ['p', 'a', 'ul', 'li', 'strong', 'em'],
      ALLOW_DATA_ATTR: false
    })
  })
  </script>
  
  <style>
  /* Preserve the styling from the original editor */
  ul {
    list-style-type: disc;
    padding-left: 1.5em;
  }
  
  ul li {
    margin-bottom: 0.5em;
  }
  
  a {
    color: #3b82f6; /* blue-500 */
    text-decoration: underline;
    cursor: pointer;
  }
  
  /* Add specific readonly styles */
  .prose {
    cursor: default;
    user-select: text;
  }
  </style>
  