<template>
    <Head title="Program List" />
    <AdminPanel />
    <content-container :hasAction="true" placeholder="Search program" :data_list="program_list">
        <template v-slot:actions>
            <dropdown-option position="right">
                <template v-slot:button>
                    <button class="px-3 border border-gray-500 rounded h-10">
                        <i class="fas fa-plus"></i>
                        <i class="fas fa-caret-down ml-3"></i></button>
                </template>
                <template v-slot:options>
                    <div class="w-48">
                        <Link class="w-full" :href="route('admin.discipline.create')">
                        <button class="py-2 indent-2 flex flex-row w-full text-left hover:bg-gray-200 hover:text-black">
                            <div class="w-8">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div>Discipline</div>
                        </button>
                        </Link>
                        <Link class="w-full" :href="route('admin.program.create')">
                        <button class="flex flex-row py-2 indent-2 w-full text-left hover:bg-gray-200 hover:text-black">
                            <div class="w-8">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div>Program</div>
                        </button>
                        </Link>
                    </div>
                </template>
            </dropdown-option>
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
import { onMounted, onBeforeUnmount, ref } from 'vue';

const createDropdownContainer = ref(null);
const isCreateDropdownOpen = ref(false);


const closeCreateDropdown = (element) => {
    if (!createDropdownContainer.value.contains(element.target)) {
        isCreateDropdownOpen.value = false;
    }
}

onMounted(() => {
    window.addEventListener('click', closeCreateDropdown);
});

onBeforeUnmount(() => {
    window.removeEventListener('click', closeCreateDropdown);
});

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