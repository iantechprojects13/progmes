<template>
    <div class="flex flex-col space-y-1">
        <label for="discipline" class="text-sm font-medium text-gray-700">
            Discipline
        </label>
        <select
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            id="discipline"
            class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
            <option value="">All</option>
            <option v-for="item in list" :key="item.id" :value="item.id">{{ item.discipline }}</option>
        </select>
    </div>
</template>

<script setup>
defineProps(["modelValue", "list"]);

defineEmits(["update:modelValue"]);
</script>
