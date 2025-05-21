<template>
    <div class="flex flex-col space-y-1">
        <label class="text-sm font-medium text-gray-700">
            Status
        </label>
        <select
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
            <option value="">All</option>
            <option value="Submitted">Completed</option>
            <option value="In progress">In progress</option>
            <option value="Locked">Locked</option>
            <option value="For Re-evaluation">For Re-evaluation</option>
            <option value="Monitored">Monitored</option>
            <option value="Deployed">Deployed</option>
        </select>
    </div>
</template>

<script setup>
defineProps(["modelValue"]);
defineEmits(["update:modelValue"]);
</script>