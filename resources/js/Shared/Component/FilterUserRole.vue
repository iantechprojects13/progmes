<template>
    <div class="flex flex-col space-y-1">
        <label class="text-sm font-medium text-gray-700">
            Role
        </label>
        <select
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
            <option value="">All</option>
            <option class="text-gray-800" v-show="type == 'All' || type == 'CHED'" value="Regional Director">Regional Director</option>
            <option class="text-gray-800" v-show="type == 'All' || type == 'CHED'" value="Chief Education Program Specialist">Chief Education Program Specialist</option>
            <option class="text-gray-800" v-show="type == 'All' || type == 'CHED'" value="Supervising Education Program Specialist">Supervising Education Program Specialist</option>
            <option class="text-gray-800" v-show="type == 'All' || type == 'CHED'" value="Education Supervisor">Education Supervisor</option>
            <option class="text-gray-800" v-show="type == 'All' || type == 'HEI'" value="Vice-President for Academic Affairs">VPAA/Dean of Multiple Disciplines</option>
            <option class="text-gray-800" v-show="type == 'All' || type == 'HEI'" value="Dean">Dean of a Specific Discipline</option>
            <option class="text-gray-800" v-show="type == 'All' || type == 'HEI'" value="Quality Assurance Officer">Quality Assurance Officer</option>
            <option class="text-gray-800" v-show="type == 'All' || type == 'HEI'" value="Program Head">Program Head</option>
            <option class="text-gray-800" v-show="type == 'All' || type == 'HEI'" value="Program Coordinator">Program Coordinator</option>
            <option class="text-gray-800" v-show="type == 'All' || type == 'HEI'" value="Librarian">Librarian</option>
        </select>
    </div>
</template>

<script setup>
defineProps(["modelValue", "type"]);
defineEmits(["update:modelValue"]);
</script>