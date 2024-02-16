<template>
    <div v-if="message" class="text-sm text-red-500 ml-3">
        {{ message }}
    </div>
</template>

<script setup>

defineProps([
    'message',
]);

</script>