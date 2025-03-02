<template>
    <div class="select-none flex flex-row whitespace-nowrap">
        <div class="flex flex-row items-center h-10 mr-2 text-gray-500 text-sm">
            {{ data_list.from }}-{{ data_list.to }} of {{ data_list.total }}
        </div>
        <div class="flex items-center justify-center w-fit rounded">
            <button @click="submit(link.url)" v-show="hideBtn(link.label)" v-for="link in data_list.links" :key="link.id"
            :class="[{'text-gray-600 border-gray-400 hover:bg-gray-200 active:bg-gray-300': link.url}, {'text-gray-300 border-gray-300': !link.url}]"
                class="border rounded-xl p-1 text-center h-10 w-10 ml-1 tooltipForActions text-xl"
                :data-tooltip="link.label.includes('Previous')? 'Previous': 'Next'">
            <span v-if="link.label.includes('Previous')">&laquo;</span>
            <span v-else-if="link.label.includes('Next')">&raquo;</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';


defineProps([
    'data_list',
]);


function submit(link) {
    router.get(link, {}, {
        preserveScroll: true,
    });
}




const hideBtn = (label) => {
    if (label.includes("Previous") || label.includes("Next")) {
        return true;
    } else {
        return false;
    }
}

</script>