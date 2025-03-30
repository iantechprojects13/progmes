<template>
    <div class="select-none flex items-center gap-4">
        <!-- Counter -->
        <div class="text-sm text-gray-600">
          {{ data_list.from }}-{{ data_list.to }} of {{ data_list.total }}
        </div>
    
        <!-- Navigation Buttons -->
        <div class="flex items-center gap-2">
          <button
            v-show="hideBtn(link.label)"
            v-for="link in data_list.links"
            :key="link.id"
            @click="submit(link.url)"
            :class="[
              'inline-flex h-9 w-9 items-center justify-center rounded-lg border transition-colors duration-200',
              link.url 
                ? 'border-gray-400 text-gray-600 hover:bg-gray-50 active:bg-gray-100 tooltipForActions' 
                : 'border-gray-100 text-gray-300 cursor-not-allowed'
            ]"
            :data-tooltip="link.label.includes('Previous') ? 'Previous' : 'Next'"
          >
            <!-- Previous Icon -->
            <svg
              v-if="link.label.includes('Previous')"
              class="h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
              />
            </svg>
    
            <!-- Next Icon -->
            <svg
              v-else-if="link.label.includes('Next')"
              class="h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5l7 7-7 7"
              />
            </svg>
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