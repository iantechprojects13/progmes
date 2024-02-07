<template>
    <Head title="Higher Education Institution List" />
    <AdminPanel />
    <content-container placeholder="Search HEI">
        <template v-slot:channel>
            <button class="text-blue-500 underline font-bold mr-5">List</button>
            <Link :href="route('admin.hei.create')">
            <button class="text-gray-500 hover:text-gray-800">Create</button>
            </Link>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="scope-col p-3 pl-0">Higher Education Institution</th>
                    <th class="scope-col p-3">Address</th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="institution in institution_list" :key="institution" class="border-b border-gray-300">
                        <th scope="row" class="pr-3">
                            <Link :href="route('admin.hei.show', [institution])"
                                class="hover:bg-gray-300 bg-gray-200 p-1 rounded">
                            <i class="fas fa-university mr-2"></i>
                            {{ institution.name }}
                            </Link>
                        </th>
                        <td class="p-3">{{ institution.address }}, {{ institution.cityOrMunicipality }}</td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <div v-if="$page.props.flash.success">
        <Notification :message="this.$page.props.flash.success" />
    </div>
</template>

<script setup>


const props = defineProps([
    'institution_list',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,
}
</script>