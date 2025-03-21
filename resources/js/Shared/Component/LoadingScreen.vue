<template>
    
    <div
        class="fixed inset-0 z-[999] flex items-center justify-center bg-black bg-opacity-70"
    >
        <div class="text-center">
          <div class="loader-container">
            <div class="loader">
              <div class="box box1"></div>
              <div class="box box2"></div>
              <div class="box box3"></div>
              <div class="box box4"></div>
            </div>
          </div>
            <div class="text-white text-lg text-center select-none animate-pulse mt-5">
                Please wait...
            </div>
        </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'LoaderComponent'
  }
  </script>
  
  <style scoped>
  .loader-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    min-height: 120px;
  }
  
  .loader {
    position: relative;
    width: 80px;
    height: 80px;
    animation: rotate 2s infinite ease;
  }
  
  .box {
    position: absolute;
    width: 32px;
    height: 32px;
    border-radius: 4px;
  }
  
  .box1 {
    background-color: #cc3524; /* Red */
    top: 0;
    left: 0;
    animation: box1 2s infinite;
  }
  
  .box2 {
    background-color: #0371d7; /* Blue */
    top: 0;
    right: 0;
    animation: box2 2s infinite;
  }
  
  .box3 {
    background-color: #eee; /* Green */
    bottom: 0;
    right: 0;
    animation: box3 2s infinite;
  }
  
  .box4 {
    background-color: #f1db19; /* Yellow */
    bottom: 0;
    left: 0;
    animation: box4 2s infinite;
  }
  
  @keyframes rotate {
    0% {
      transform: rotate(0deg);
    }
    50% {
      transform: rotate(180deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
  
  @keyframes box1 {
    0% {
      top: 0;
      left: 0;
    }
    25% {
      top: 0;
      left: 48px;
    }
    50% {
      top: 48px;
      left: 48px;
    }
    75% {
      top: 48px;
      left: 0;
    }
    100% {
      top: 0;
      left: 0;
    }
  }
  
  @keyframes box2 {
    0% {
      top: 0;
      right: 0;
    }
    25% {
      top: 48px;
      right: 0;
    }
    50% {
      top: 48px;
      right: 48px;
    }
    75% {
      top: 0;
      right: 48px;
    }
    100% {
      top: 0;
      right: 0;
    }
  }
  
  @keyframes box3 {
    0% {
      bottom: 0;
      right: 0;
    }
    25% {
      bottom: 0;
      right: 48px;
    }
    50% {
      bottom: 48px;
      right: 48px;
    }
    75% {
      bottom: 48px;
      right: 0;
    }
    100% {
      bottom: 0;
      right: 0;
    }
  }
  
  @keyframes box4 {
    0% {
      bottom: 0;
      left: 0;
    }
    25% {
      bottom: 48px;
      left: 0;
    }
    50% {
      bottom: 48px;
      left: 48px;
    }
    75% {
      bottom: 0;
      left: 48px;
    }
    100% {
      bottom: 0;
      left: 0;
    }
  }
  </style>