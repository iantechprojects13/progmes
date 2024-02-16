<template>
    <Head title="Evaluation" />
    <div class="text-xl font-bold w-full mb-5">Program Evaluation</div>
    <!-- <div class="flex flex-col pb-4 mb-4 border-2 rounded border-gray-400 bg-gray-200 p-3">
        <div><b>Role:</b> {{ role[0].role }} </div>
    </div> -->
    <div>{{ role }}</div>
    <div v-if="$page.props.flash.failed">
        <Notification :message="$page.props.flash.failed" />
    </div>
</template>

<script setup>
defineProps([
    'role',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    layout: Layout,
}
</script>