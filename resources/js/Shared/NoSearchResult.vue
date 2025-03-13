<template>
    <td class="text-center py-10 text-gray-500" colspan="100%">
        <div v-if="this.$page.props.filters.search != null && this.$page.props.filters.search != ''">
            No results found for "<b>{{ this.$page.props.filters.search }}</b>"
        </div>
        <div v-else>
            No {{ text }} found
        </div>
    </td>
</template>

<script setup>


const props = defineProps([
    'text',
]);
    
</script>