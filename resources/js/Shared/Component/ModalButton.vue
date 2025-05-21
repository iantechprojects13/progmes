<template>
    <button
        class="select-none inline-flex items-center min-w-[80px] justify-center px-4 py-2.5 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 duration-200"
        :class="buttonClasses"
    >
        {{ text }}
    </button>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    text: {
        type: String,
        default: "Submit",
    },
    color: {
        type: String,
        default: "blue",
        validator: (value) => ["blue", "red", "green"].includes(value),
    },
});

const buttonClasses = computed(() => {
    const colorMap = {
        blue: "bg-blue-600 hover:bg-blue-700 focus:ring-blue-500",
        red: "bg-red-600 hover:bg-red-700 focus:ring-red-500",
        green: "bg-green-600 hover:bg-green-700 focus:ring-green-500",
    };

    return colorMap[props.color] || colorMap.blue;
});
</script>
