<template>

    <Head title="Evaluation" />
    <page-title title="Program Evaluation" />
    <div class="mx-3 md:mx-8">
        Evaluation For Admins and Super Admin
    </div>
</template>

<script setup>
defineProps([
    'data',
    'role',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    layout: Layout,
}
</script>