<template>
    <Head title="Evaluation Form" />
    <AdminPanel />
    <content-container>
        <template v-slot:channel>
            <Link :href="route('admin.form.list')">
            <button class="text-gray-500 hover:text-gray-800 p-2 w-auto">
                <i class="fas fa-arrow-left mr-3"></i>
                Back
            </button>
            </Link>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th>Area</th>
                    <th>Minimum Requirement</th>
                </template>
            </content-table>
        </template>
    </content-container>
</template>

<script setup>
defineProps([
    'evaluationForm',
    'items',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue'

export default {
    layout: Layout,
}
</script>