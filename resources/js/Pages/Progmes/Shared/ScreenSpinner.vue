<template>
    <div v-show="showModal"
        class="flex items-center justify-center bg-white bg-opacity-50 h-screen w-full z-100 fixed top-0 left-0">
        <div class="bg-opacity-100 bg-white">
            <div class="text-center">
                <img src="/assets/spinner.png" width="50" class="animate-spin mx-auto">
            </div>
            <div class="mt-5 text-center">
                {{ text }}
            </div>
        </div>
    </div>
</template>

<script setup>
    import 'bootstrap/dist/js/bootstrap.bundle.min.js';
    const props = defineProps([
        'text',
        'showModal',
    ])
</script>