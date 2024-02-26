<template>
    <div class="relative select-none text-gray-600 text-base" ref="dropdownContainer" @click="isOpen = true">
        <div>
            <slot name="button"></slot>
        </div>
        <div v-show="isOpen"
            class="flex h-auto flex-col items-start absolute top-12 shadow shadow-gray-500 border border-gray-400 bg-white rounded py-3 z-30 w-auto"
            :class="[{ 'right-0': position == 'right' }, { 'left-0': position == 'left' }]">
            <slot name="options"></slot>
        </div>
    </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue';

defineProps([
    'position',
]);

const dropdownContainer = ref(null);
const isOpen = ref(false);


const closeDropdown = (element) => {
    if (!dropdownContainer.value.contains(element.target)) {
        isOpen.value = false;
    }
}

onMounted(() => {
    window.addEventListener('click', closeDropdown);
});

onBeforeUnmount(() => {
    window.removeEventListener('click', closeDropdown);
});

</script>