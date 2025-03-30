<template>
    <div
      @click.self="handleBackdropClick"
      :class="[{ 'opacity-100': showModal }, { 'opacity-0 pointer-events-none': !showModal }]"
      class="px-3 fixed inset-0 bg-black/50 backdrop-blur-sm z-[997] transition-opacity duration-300 flex items-center justify-center"
    >
      <div
        class="min-h-[200px] w-full mx-auto bg-white rounded-lg shadow-xl transition-all duration-300"
        :class="[
          modalWidth,
          { 'translate-y-0 opacity-100': showModal },
          { 'translate-y-8 opacity-0': !showModal }
        ]"
      >
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900">
            {{ title }}
          </h3>
          <button
            @click="closeModal"
            class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors duration-200"
          >
            <svg
              class="w-5 h-5"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
  
        <!-- Content -->
        <div class="px-6 py-4 text-gray-700">
          <slot></slot>
        </div>
  
        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
          <button
            v-if="!hasNoCancelButton"
            @click="closeModal"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
          >
            Cancel
          </button>
          
          <button
            v-if="['upload', 'import', 'uploadEvidenceFile'].includes(type)"
            @click="handleAction"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
          >
            {{ actionButtonText }}
          </button>
  
          <button
            v-if="type === 'create'"
            @click="handleAction"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
          >
            Create
          </button>
  
          <slot name="custom-button"></slot>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed, watch, onMounted, onBeforeUnmount } from 'vue'
  
  const props = defineProps({
    title: {
      type: String,
      required: true
    },
    type: {
      type: String,
      default: ''
    },
    showModal: {
      type: Boolean,
      default: false
    },
    width: {
      type: String,
      default: 'lg'
    },
    hasNoCancelButton: {
      type: Boolean,
      default: false
    }
  })
  
  const emit = defineEmits(['close', 'upload', 'create'])
  
  const modalWidth = computed(() => ({
    'max-w-sm': props.width === 'sm',
    'max-w-md': props.width === 'md',
    'max-w-lg': props.width === 'lg',
    'max-w-xl': props.width === 'xl',
    'max-w-2xl': props.width === '2xl',
    'max-w-3xl': props.width === '3xl',
    'max-w-4xl': props.width === '4xl'
  }))
  
  const actionButtonText = computed(() => {
    const actions = {
      upload: 'Upload',
      import: 'Import',
      uploadEvidenceFile: 'Upload'
    }
    return actions[props.type] || ''
  })
  
  const handleAction = () => {
    if (props.type === 'create') {
      emit('create')
    } else {
      emit('upload')
    }
  }
  
  const handleBackdropClick = () => {
    // Optional: Add a subtle feedback animation
    emit('close')
  }
  
  const closeModal = () => {
    emit('close')
  }
  
  watch(() => props.showModal, (newVal) => {
    document.body.style.overflow = newVal ? 'hidden' : 'auto'
  })



  const handleKeydown = (event) => {
    // Only handle Escape key when modal is open
    if (event.key === 'Escape' && props.showModal) {
      closeModal()
    }
  }

  onMounted(() => {
    window.addEventListener('keydown', handleKeydown)
  })

  onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeydown)
  })
  </script>