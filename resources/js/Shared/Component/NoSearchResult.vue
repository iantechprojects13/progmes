<template>
        <td class="no-results-cell" colspan="100%">
            <div class="no-results-content w-full">
                <div v-if="$page.props.filters?.search != null && $page.props.filters?.search != ''">
                    No results found for "<b>{{ $page.props.filters?.search }}</b>"
                </div>
                <div v-else>
                    No {{ text }} found
                </div>
            </div>
        </td>
</template>

<script setup>
const props = defineProps([
    'text',
]);
</script>

<style scoped>
.no-results-cell {
    padding: 2.5rem 1rem !important; /* py-10 */
    text-align: center !important;
    color: #6b7280 !important; /* text-gray-500 */
    border-bottom: none !important;
    background-color: transparent !important;
}

.no-results-content {
    display: flex;
    justify-content: center;
    width: 100%;
}

/* Override any hover effects */
:deep(tr:hover) .no-results-cell {
    background-color: transparent !important;
}
</style>