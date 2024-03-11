<template>

    <Head title="Test Component" />
    <AdminPanel />
    <div class="w-full">
        Test Component
    </div>
</template>


<script>
    import Layout from '../Shared/Layout.vue';

    export default {
        layout: Layout,

        mounted() {
            console.log('test');

            window.addEventListener('beforeunload', this.beforeUnloadHandler);
        },


        methods: {
            beforeUnloadHandler(event) {
                console.log('asdasd');
            },
        },
    }
</script>