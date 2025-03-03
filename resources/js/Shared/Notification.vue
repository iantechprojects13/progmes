<template>
    <div v-show="message != null" class="w-full">
      <div class="bg-stone-800 text-white fixed p-5 md:rounded-lg -bottom-20 md:left-auto md:right-3 md:w-max w-full left-0 right-0 md:max-w-md z-90"
        :class="{ 'show md:-translate-y-3': openNotification, 'hide': !openNotification }">
        <div class="flex justify-center md:justify-between items-center text-sm">
          <div class="mr-3">
            <div v-show="type == 'processing'">
              <img src="/assets/spinner-light.png" class="animate-spin" width="20">
            </div>
            <div v-show="type == 'success'"><i class="fas fa-check-circle text-xl text-green-400"></i></div>
            <div v-show="type == 'failed'"><i class="fas fa-times-circle text-xl text-red-400"></i></div>
          </div>
          <div>{{ message }}</div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        openNotification: false,
        timeout: null
      }
    },
  
    props: {
      message: String,
      type: String,
      duration: {
        type: Number,
        default: 6000
      }
    },
  
    watch: {
      message: {
        immediate: true,
        handler(newVal) {
          if (newVal) {
            this.showNotification()
          }
        }
      }
    },
  
    methods: {
      showNotification() {
        this.openNotification = true
        
        if (this.timeout) {
          clearTimeout(this.timeout)
        }
        
        this.timeout = setTimeout(() => {
          this.openNotification = false
          this.$emit('complete')
        }, this.duration)
      }
    },
  
    beforeUnmount() {
      if (this.timeout) {
        clearTimeout(this.timeout) 
      }
    }
  }
  </script>
  
  <style scoped>
  @keyframes show {
    0% {
      bottom: -0.75rem;
      opacity: 0;
    }
    100% {
      bottom: 0.75rem;
      opacity: 1;
    }
  }
  
  @keyframes hide {
    0% {
      bottom: 0.75rem;
      opacity: 1;
    }
    100% {
      bottom: -0.75rem;
      opacity: 0;
    }
  }
  
  .show {
    animation: show 0.3s forwards;
  }
  
  .hide {
    animation: hide 0.3s forwards;
  }
  </style>