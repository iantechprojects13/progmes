<template>

    <Head title="Dashboard" />
    <page-title title="Dashboard" />
    <div class="mx-3 md:mx-8 mt-8 bg-white border border-gray-400 rounded">
        <div class="p-3 border-b border-gray-400">
            Compliance Progress
        </div>
        <div class="p-3">
            Content
        </div>
    </div>
</template>

<script setup>
const props = defineProps([
    'data',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue';

export default {
    layout: Layout,
}
</script>