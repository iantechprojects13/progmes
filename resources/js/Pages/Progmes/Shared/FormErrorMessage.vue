<template>
    <div v-if="message" class="text-sm ml-1"
        :class="[{ 'text-red-200': theme == 'light' }, { 'text-red-500': theme == 'dark' }]">
        {{ message }}
    </div>
</template>

<script setup>

defineProps([
    'message',
    'theme',
]);

</script>