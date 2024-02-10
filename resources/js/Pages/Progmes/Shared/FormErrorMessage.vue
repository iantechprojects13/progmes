<template>
    <div v-if="message" class="text-sm text-red-500">
        {{ message }}
    </div>
</template>

<script setup>

defineProps([
    'message',
]);

</script>