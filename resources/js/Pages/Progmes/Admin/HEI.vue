<template>
    <Head title="Higher Education Institution List" />
    <AdminPanel />
    <content-container :hasAction="true" placeholder="Search HEI">
        <template v-slot:actions>
            <Link :href="route('admin.hei.create')">
            <button class="h-10 px-3 border bg-blue-500 hover:bg-blue-600 rounded text-white">
                <i class="fas fa-plus mr-2"></i>
                New
            </button>
            </Link>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="scope-col p-3">Higher Education Institution</th>
                    <th class="scope-col p-3">Address</th>
                    <th class="scope-col p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(institution, index) in institution_list" :key="institution"
                        class="border-b border-gray-300 cursor-pointer text-gray-700 hover:text-black hover:bg-gray-300"
                        :class="{ 'bg-gray-200': index % 2 == 0 }">
                        <th scope="row" class="p-3">
                            <i class="fas fa-university mr-2 text-base"></i>
                            {{ institution.name }}
                        </th>
                        <td class="p-3">{{ institution.address }}, {{ institution.cityOrMunicipality }}</td>
                        <td class="p-3 text-right">
                            <button @click="view(institution.id)"
                                class="text-gray-500 hover:text-black shadow shadow-gray-500 bg-gray-100 hover:bg-gray-200 border border-gray-500 w-10 h-10 rounded tooltipForActions"
                                data-tooltip="View">
                                <i class="text-lg fas fa-eye"></i>
                            </button>
                            <!-- <button @click="toggleModal(institution, 'deleteHEI', 'Delete HEI')"
                                class="text-red-500 hover:text-red-600 hover:bg-gray-200 hover:border border-gray-400 w-8 h-8 rounded-full tooltipForActions"
                                data-tooltip="Delete">
                                <i class="text-lg fas fa-trash"></i>
                            </button> -->
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <Confirmation v-show="openModal" @close="closeModal" :selected="selected" :title="title" :modaltype="modaltype" />
    <div v-if="$page.props.flash.success">
        <Notification :message="this.$page.props.flash.success" />
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps([
    'institution_list',
]);


function view(id) {
    router.get('/admin/higher-education-institutions/' + id + '/view');
}

</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    data() {
        return {
            openModal: false,
            title: '',
            modaltype: '',
            selected: '',
        }
    },
    layout: Layout,

    methods: {
        toggleModal(item, type, title) {
            this.openModal = !this.openModal;
            this.selected = item;
            this.modaltype = type;
            this.title = title;
        },

        closeModal() {
            this.openModal = false;
        }
    }
}
</script>