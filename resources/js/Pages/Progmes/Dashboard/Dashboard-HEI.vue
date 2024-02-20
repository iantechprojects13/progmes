<template>
    <Head title="Dashboard" />
    <div>
        HEI Dashboard
    </div>
</template>

<script setup>
defineProps([
    'complied',
    'notComplied',
    'compliedPercentage',
    'notCompliedPercentage',
    'totalItem',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue';

export default {
    layout: Layout,
}
</script>