<template>
    <div v-show="isLoading" class="w-full text-center md:text-right md:w-auto md:max-w-md z-100 bottom-0 right-0 fixed md:bottom-3 md:right-3 bg-neutral-800 text-white p-5 md:rounded-lg">
        <div>
            <span><i class="fas fa-spinner mr-3 animate-spin"></i></span>
            {{ message }}
        </div>
    </div>
</template>
<script setup>
const props = defineProps([
    'isLoading',
    'message',
])    
</script>

<style scoped>
    @keyframes show {
        0% {
            bottom: 12rem;
            opacity: 0;
            display: block;
        }

        2% {
            bottom: 1rem;
            opacity: 1;
        }

        98% {
            bottom: 0rem;
            opacity: 1;
        }

        100% {
            bottom: -0.75rem;
            opacity: 0;
            display: block;
        }
    }

    @keyframes hide {
        0% {
            bottom: 0rem;
            opacity: 1;
        }

        100% {
            bottom: -0.75rem;
            opacity: 0;
            display: none;
        }
    }

    .show {
        animation: show 6s forwards;
    }

    .hide {
        animation: hide 150ms forwards;
    }
</style>