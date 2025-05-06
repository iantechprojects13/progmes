<template>
    <select
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="px-4 pr-10 py-2.5 text-sm bg-white border border-gray-300 text-slate-700 rounded-lg hover:border-gray-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 focus:outline-none duration-200 cursor-pointer appearance-none whitespace-nowrap"
    >
        <option v-for="(year, index) in data" :key="index" :value="year">AY {{ year }}</option>
    </select>
</template>

<script setup>
defineProps(["modelValue"]);
defineEmits(["update:modelValue"]);

const data = [
    "2022-2023",
    "2023-2024",
    "2024-2025",
    "2025-2026",
    "2026-2027",
    "2027-2028",
    "2028-2029",
    "2029-2030",
];
</script>

