<template>
    <div class="flex flex-col space-y-1">
        <label class="text-sm font-medium text-gray-700">
            Academic Year
        </label>
        <select
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
            <option v-for="(year, index) in data" :key="index" :value="year">{{ year }}</option>
        </select>
    </div>
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