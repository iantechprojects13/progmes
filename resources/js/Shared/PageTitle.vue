<template>
    <div class="pt-8 px-3 md:px-16 text-lg md:text-xl font-bold text-gray-700">
        {{ title }}
    </div>
</template>

<script setup>

defineProps([
    'title',
]);

</script>