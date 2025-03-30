<template>
    <div class="p-3 pt-5 md:p-8 md:pb-2 flex flex-col md:flex-row justify-between items-center">
        <!-- <div class="bg-gradient-to-r from-indigo-800 to-blue-600  p-8"> -->
    <div class="text-lg md:text-2xl font-bold text-gray-700 text-left w-full">{{title}}</div>
    <div class="w-full md:w-auto text-right mt-2 md:mt-0">
        <slot name="buttons"></slot>
    </div>
</div>
</template>

<script setup>

defineProps([
    'title',
]);

</script>


   