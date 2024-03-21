<template>

    <Head title="My Dashboard" />
    <!-- <page-title title="Dashboard for ES" /> -->
    <div class="w-auto h-screen">
        <embed type="text/html" class="w-full h-full" src="assets/index.html">
    </div>
</template>

<script>
    import Layout from '../Shared/Layout.vue'

    export default {
        layout: Layout,
    }
</script>