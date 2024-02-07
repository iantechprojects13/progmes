<template>
    <Head title="Dashboard" />
    <div class="text-xl font-bold mb-5">
        Dashboard
    </div>
    <div class="w-full">
        This system must be finished within the month of February 2024.
    </div>
    <div v-if="this.$page.props.flash.success">
        {{ this.$page.props.flash.success }}
    </div>
</template>

<script>
import Layout from '../Shared/Layout.vue';

export default {
    layout: Layout,
}
</script>