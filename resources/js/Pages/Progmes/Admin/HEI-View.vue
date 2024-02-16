<template>
    <Head :title="institution.name" />
    <AdminPanel />
    <content-container placeholder="Search HEI">
        <template v-slot:channel>
            <Link :href="route('admin.hei.list')">
            <button class="border border-gray-400 py-1 px-4 h-10 rounded-full">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </button>
            </Link>
        </template>
        <template v-slot:actions>
            <Link :href="route('admin.hei.create')">
            <button class="h-10 px-3 border rounded border-gray-400 text-gray-500">
                <i class="fas fa-edit"></i>
            </button>
            </Link>
        </template>
        <template v-slot:content-head>
            asdasd
        </template>
        <template v-slot:content>
            <div class="p-3">
                sadsad
            </div>
        </template>
    </content-container>
</template>

<script setup>


const props = defineProps({
    'institution': Object,
});


</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,
}
</script>

