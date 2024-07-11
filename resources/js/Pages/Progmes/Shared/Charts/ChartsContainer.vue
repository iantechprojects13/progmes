<template>
    <div class="mx-3 md:mx-8 mt-5 flex flex-col xl:flex-row justify-between">
        <div class="w-full border border-gray-300 bg-white rounded mt-3 lg:mt-0">
            <div class="p-3 border-b border-gray-300"><b>{{ title }}</b></div>
            <div class="flex flex-col items-center text-center w-full h-auto py-3 md:py-5 px-3 md:px-16">
                <div v-show="hasData" class="w-full mx-3 my-3 mb-5">
                    <slot name="data"></slot>
                </div>
                <slot name="chart"></slot>
                <div v-show="note" class="w-full text-left bg-blue-200 p-2 mt-5 rounded-md text-gray-700">
                    <i class="fas fa-info-circle text-sm mr-2"></i>{{ note }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps([
    'title',
    'note',
    'hasData',
]);

</script>