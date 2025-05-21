<template>
    <div class="flex flex-col space-y-1">
        <label class="text-sm font-medium text-gray-700">
            User Type
        </label>
        <select
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
            <option value="">All</option>
            <option value="CHED">CHED</option>
            <option value="HEI">HEI</option>
        </select>
    </div>
</template>

<script setup>
defineProps(["modelValue"]);
defineEmits(["update:modelValue"]);
</script>
