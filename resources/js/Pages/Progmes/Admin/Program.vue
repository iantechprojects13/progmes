<template>
    <Head title="Program List" />
    <AdminPanel />
    <content-container placeholder="Search program" :data_list="program_list">
        <template v-slot:actions>
            <div class="relative text-gray-600" @mouseleave="openCreateDropdown = false">
                <button class="px-3 border border-gray-400 hover:border-black rounded h-10 text-gray-500 hover:text-black"
                    @click.prevent="openCreateDropdown = !openCreateDropdown">
                    <i class="fas fa-plus"></i>
                    <i class="fas fa-caret-down ml-3"></i></button>
                <div v-show="openCreateDropdown"
                    class="flex flex-col items-start absolute top-10 right-0 shadow border border-gray-300 bg-white rounded py-3 z-30 w-48"
                    @mouseover.prevent="openCreateDropdown = true">
                    <Link class="w-full" :href="route('admin.discipline.create')">
                    <button class="py-2 indent-2 flex flex-row w-full text-left hover:bg-gray-200 hover:text-black">
                        <div class="w-8">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>New discipline</div>
                    </button>
                    </Link>
                    <Link class="w-full" :href="route('admin.program.create')">
                    <button class="flex flex-row py-2 indent-2 w-full text-left hover:bg-gray-200 hover:text-black">
                        <div class="w-8">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>New program</div>
                    </button>
                    </Link>
                </div>
            </div>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3">
                        Program
                    </th>
                    <th class="p-3">
                        Discipline
                    </th>
                    <th class="p-3">
                        Major
                    </th>
                    <th scope="col" class="p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="program in program_list?.data" :key="program.id" class="border-b border-gray-400">
                        <th class="p-3">
                            {{ program.program }}
                        </th>
                        <td class="p-3">
                            {{ program.discipline }}
                        </td>
                        <td class="p-3">
                            {{ program.major }}
                        </td>
                        <td class="p-3 text-right">
                            <Link :href="'/admin/program/' + program.id + '/edit'">
                            <button
                                class="text-orange-500 hover:text-orange-600 hover:bg-gray-200 hover:border border-gray-400 w-8 h-8 rounded-full tooltipForActions"
                                data-tooltip="Edit">
                                <i class="text-lg fas fa-edit"></i>
                            </button>
                            </Link>
                        </td>
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
    'program_list',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,
    data() {
        return {
            openCreateDropdown: false,
        }
    }
}
</script>