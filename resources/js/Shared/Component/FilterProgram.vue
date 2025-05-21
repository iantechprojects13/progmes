<template>
    <div class="flex flex-col space-y-1">
        <label class="text-sm font-medium text-gray-700">
            Program
        </label>
        <select
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
            <option value="">All</option>
            <option v-for="item in data" :key="item.id" :value="item.id">
                {{ item.program }}
                <span v-if="item.major">
                    - {{ item.major }}
                </span>
            </option>
        </select>
    </div>
</template>

<script setup>
defineProps(["modelValue", "data"]);
defineEmits(["update:modelValue"]);
</script>