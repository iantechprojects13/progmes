<template>
    <Head title="Admin Settings" />
    <AdminPanel />
    <div class="mt-5 font-bold">
        Admin Settings
    </div>
    <div class="mt-5">
        <label for="effectivity">Current Academic Year: </label><br>
        <select id="effectivity" class="rounded w-64">
            <option value="2023-2024">2023-2024</option>
            <option value="2023-2024">2024-2025</option>
            <option value="2023-2024">2025-2026</option>
        </select>
    </div>
</template>

<script setup>
defineProps([
    'settings',
]);
</script>

<script>
import Layout from '@/Shared/Layout.vue';
export default {
    layout: Layout,
}
</script>